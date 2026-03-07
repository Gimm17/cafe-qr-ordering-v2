# Dokumentasi Lengkap Fitur POS / Kasir

## Soraya Cafe QR — Referensi Implementasi

---

## 1. Overview

Fitur Kasir memungkinkan admin/barista membuat order **secara manual** langsung dari panel admin, untuk pelanggan walk-in yang tidak mau scan QR. Fitur ini terpisah dari alur order customer biasa dan memiliki UI, payment flow, dan kode order sendiri.

**Perbedaan Order Kasir vs Order Customer:**

| Aspek                | Order Customer (QR)     | Order Kasir (POS)     |
| -------------------- | ----------------------- | --------------------- |
| Dibuat oleh          | Customer (self-service) | Admin/Barista         |
| Kode order           | `A-YYYYMMDD-XXXXXX`     | `K-YYYYMMDD-XXXXXX`   |
| `order_source` di DB | `QR`                    | `POS`                 |
| Pembayaran           | Midtrans Snap (online)  | Cash atau QRIS Statis |
| Status awal          | UNPAID → tunggu bayar   | CASH = langsung PAID  |
| Akses                | URL dari QR scan        | `/admin/pos`          |

---

## 2. Database Changes

### Migration: `add_pos_fields_to_orders_and_payments`

```php
Schema::table('orders', function (Blueprint $table) {
    $table->string('order_source', 10)->default('QR')->after('order_code');
    // Values: 'QR' | 'POS'
});

Schema::table('payments', function (Blueprint $table) {
    $table->integer('cash_received')->nullable()->after('raw_response');
    $table->integer('cash_change')->nullable()->after('cash_received');
});
```

### Model Updates

**`app/Models/Order.php`** — tambah ke `$fillable`:

```php
'order_source',
```

**`app/Models/Payment.php`** — tambah ke `$fillable` dan `$casts`:

```php
protected $fillable = [..., 'cash_received', 'cash_change'];
protected $casts    = [..., 'cash_received' => 'integer', 'cash_change' => 'integer'];
```

---

## 3. Settings (Key-Value di Tabel `settings`)

| Setting Key               | Default      | Keterangan                                                 |
| ------------------------- | ------------ | ---------------------------------------------------------- |
| `pos_cash_change_enabled` | `'1'`        | Tampilkan kolom uang tunai & kembalian                     |
| `pos_audio_enabled`       | `'1'`        | Audio notifikasi TTS saat QRIS masuk                       |
| `pos_qris_mode`           | `'external'` | `'external'` = QRIS gambar statis / `'midtrans'` = dynamic |
| `pos_qris_image`          | `null`       | Path ke gambar QRIS yang di-upload                         |

---

## 4. Backend

### 4a. Routes — `routes/cafe_admin.php`

Taruh di dalam group `middleware(['web','admin'])`:

```php
use App\Http\Controllers\Admin\AdminPosController;

// POS / Kasir
Route::get('/pos', [AdminPosController::class, 'index'])->name('admin.pos');
Route::get('/pos/products', [AdminPosController::class, 'products'])->name('admin.pos.products');
Route::post('/pos/order', [AdminPosController::class, 'store'])->name('admin.pos.order');
Route::post('/pos/confirm-qris/{order}', [AdminPosController::class, 'confirmQris'])->name('admin.pos.confirm-qris');
Route::get('/pos/check-payment/{order}', [AdminPosController::class, 'checkPayment'])->name('admin.pos.check-payment');

// Settings POS
Route::post('/settings/pos', [AdminSettingController::class, 'updatePos'])->name('admin.settings.pos');
```

---

### 4b. AdminPosController

**`app/Http/Controllers/Admin/AdminPosController.php`**

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CafeTable;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Services\OrderService;
use Illuminate\Http\Request;

class AdminPosController extends Controller
{
    public function __construct(private OrderService $orders) {}

    /** Halaman utama POS */
    public function index()
    {
        $categories = Category::orderBy('sort_order')->get();
        $products   = Product::with('activeModGroups.options')
            ->active()->where('is_sold_out', false)->orderBy('name')->get();
        $tables = CafeTable::orderBy('table_no')->get();

        return view('admin.pos', [
            'categories'          => $categories,
            'products'            => $products,
            'tables'              => $tables,
            'posQrisMode'         => Setting::getValue('pos_qris_mode', 'external'),
            'posQrisImage'        => Setting::getValue('pos_qris_image'),
            'posCashChangeEnabled'=> Setting::getValue('pos_cash_change_enabled', '1'),
            'posAudioEnabled'     => Setting::getValue('pos_audio_enabled', '1'),
        ]);
    }

    /** JSON produk untuk JS */
    public function products()
    {
        $products = Product::with('activeModGroups.options')
            ->active()->where('is_sold_out', false)->orderBy('name')->get()
            ->map(fn($p) => [
                'id'          => $p->id,
                'name'        => $p->name,
                'base_price'  => $p->base_price,
                'image_url'   => $p->image_url ? asset($p->image_url) : null,
                'category_id' => $p->category_id,
                'modGroups'   => $p->activeModGroups->map(fn($g) => [
                    'id'          => $g->id,
                    'name'        => $g->name,
                    'is_required' => $g->is_required,
                    'max_select'  => $g->max_select,
                    'options'     => $g->options->map(fn($o) => [
                        'id'             => $o->id,
                        'name'           => $o->name,
                        'price_modifier' => $o->price_modifier,
                    ]),
                ]),
            ]);

        return response()->json($products);
    }

    /** Buat order POS */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name'              => ['required', 'string', 'max:60'],
            'table_id'                   => ['nullable', 'integer', 'exists:cafe_tables,id'],
            'fulfillment_type'           => ['required', 'in:DINE_IN,PICKUP'],
            'payment_method'             => ['required', 'in:CASH,QRIS'],
            'cash_received'              => ['nullable', 'integer', 'min:0'],
            'items'                      => ['required', 'array', 'min:1'],
            'items.*.product_id'         => ['required', 'integer'],
            'items.*.name'               => ['required', 'string'],
            'items.*.unit_price'         => ['required', 'integer'],
            'items.*.qty'                => ['required', 'integer', 'min:1'],
            'items.*.note'               => ['nullable', 'string', 'max:200'],
            'items.*.modifiers'          => ['nullable', 'array'],
            'items.*.modifiers.*.id'         => ['required', 'integer'],
            'items.*.modifiers.*.group_name' => ['required', 'string'],
            'items.*.modifiers.*.option_name'=> ['required', 'string'],
            'items.*.modifiers.*.price_modifier' => ['required', 'integer'],
        ]);

        $subtotal = collect($data['items'])->sum(fn($i) => $i['unit_price'] * $i['qty']);
        $totals   = ['subtotal' => $subtotal, 'tax' => 0, 'service' => 0,
                     'discount' => 0, 'grand_total' => $subtotal];

        $order = $this->orders->createPosOrder(
            $data['table_id'] ?? null,
            $data['customer_name'],
            $data['fulfillment_type'],
            $data['items'],
            $totals,
            $data['payment_method'],
            $data['cash_received'] ?? null
        );

        return response()->json([
            'success' => true,
            'order'   => [
                'id'             => $order->id,
                'order_code'     => $order->order_code,
                'grand_total'    => $order->grand_total,
                'payment_status' => $order->payment_status,
                'payment_method' => $data['payment_method'],
                'receipt_url'    => route('admin.orders.receipt', $order),
            ],
        ]);
    }

    /** Konfirmasi QRIS manual oleh kasir */
    public function confirmQris(Order $order)
    {
        if ($order->payment_status === 'PAID') {
            return response()->json(['success' => false, 'message' => 'Sudah dibayar.']);
        }
        $order->update(['payment_status' => 'PAID']);
        $order->payment?->update(['status' => 'settlement']);

        return response()->json(['success' => true, 'grand_total' => $order->grand_total]);
    }

    /** Polling status pembayaran QRIS */
    public function checkPayment(Order $order)
    {
        return response()->json([
            'payment_status' => $order->payment_status,
            'grand_total'    => $order->grand_total,
        ]);
    }
}
```

---

### 4c. OrderService — method `createPosOrder()`

Tambahkan ke **`app/Services/OrderService.php`**:

```php
public function createPosOrder(
    ?int $tableId,
    string $customerName,
    string $fulfillmentType,
    array $cart,
    array $totals,
    string $paymentMethod,  // 'CASH' | 'QRIS'
    ?int $cashReceived = null
): Order {
    return DB::transaction(function () use ($tableId, $customerName, $fulfillmentType,
                                            $cart, $totals, $paymentMethod, $cashReceived) {
        $orderCode = $this->generateOrderCode('K'); // prefix K- untuk kasir

        $order = Order::create([
            'order_code'      => $orderCode,
            'order_source'    => 'POS',           // ← tandai sebagai order kasir
            'table_id'        => $tableId,
            'customer_name'   => $customerName,
            'fulfillment_type'=> $fulfillmentType,
            'order_status'    => 'DITERIMA',
            'payment_status'  => $paymentMethod === 'CASH' ? 'PAID' : 'UNPAID',
            'subtotal'        => $totals['subtotal'],
            'tax_amount'      => $totals['tax']      ?? 0,
            'service_amount'  => $totals['service']  ?? 0,
            'discount_amount' => $totals['discount'] ?? 0,
            'grand_total'     => $totals['grand_total'],
        ]);

        // Buat order items + modifiers
        foreach ($cart as $item) {
            $lineTotal = $item['unit_price'] * $item['qty'];
            $orderItem = OrderItem::create([
                'order_id'    => $order->id,
                'product_id'  => $item['product_id'],
                'product_name'=> $item['name'],
                'unit_price'  => $item['unit_price'],
                'qty'         => $item['qty'],
                'note'        => $item['note'] ?? null,
                'line_total'  => $lineTotal,
            ]);

            foreach (($item['modifiers'] ?? []) as $mod) {
                OrderItemMod::create([
                    'order_item_id'  => $orderItem->id,
                    'mod_option_id'  => $mod['id'],
                    'mod_group_name' => $mod['group_name'],
                    'mod_option_name'=> $mod['option_name'],
                    'price_modifier' => $mod['price_modifier'],
                ]);
            }
        }

        // Buat payment record
        $cashChange = ($paymentMethod === 'CASH' && $cashReceived)
            ? $cashReceived - $totals['grand_total'] : null;

        Payment::create([
            'order_id'     => $order->id,
            'gateway'      => $paymentMethod,          // 'CASH' or 'QRIS'
            'status'       => $paymentMethod === 'CASH' ? 'settlement' : 'pending',
            'cash_received'=> $paymentMethod === 'CASH' ? $cashReceived : null,
            'cash_change'  => $cashChange,
        ]);

        return $order;
    });
}

// Pastikan generateOrderCode menerima $prefix
private function generateOrderCode(string $prefix = 'A'): string
{
    $date = now()->format('Ymd');
    $rand = strtoupper(Str::random(6));
    return "{$prefix}-{$date}-{$rand}";
}
```

---

### 4d. AdminSettingController — method `updatePos()`

```php
public function updatePos(Request $request)
{
    $request->validate([
        'pos_qris_image' => ['nullable', 'image', 'max:2048'],
        'pos_qris_mode'  => ['nullable', 'in:external,midtrans'],
    ]);

    Setting::setValue('pos_cash_change_enabled',
        $request->has('pos_cash_change_enabled') ? '1' : '0');
    Setting::setValue('pos_audio_enabled',
        $request->has('pos_audio_enabled') ? '1' : '0');
    Setting::setValue('pos_qris_mode',
        $request->input('pos_qris_mode', 'external'));

    if ($request->hasFile('pos_qris_image')) {
        $path = $request->file('pos_qris_image')
            ->store('uploads', 'public');
        Setting::setValue('pos_qris_image', 'storage/' . $path);
    }

    return back()->with('success', 'Pengaturan POS disimpan.');
}
```

---

## 5. Frontend — UI Split-Screen

**Layout dasar `admin.pos` blade:**

```
┌─────────────────────────────┬──────────────────────────┐
│         PANEL KIRI          │        PANEL KANAN       │
│      Katalog Produk         │       Panel Order        │
│                             │                          │
│  [Search bar]               │  Nama Customer  No.Meja  │
│  [Tab: Semua|Kopi|Snack...] │  ─────────────────────   │
│                             │  [Item 1] qty  Rp 22.000 │
│  [Produk Card] [Produk Card]│  [Item 2] qty  Rp 30.000 │
│  [Produk Card] [Produk Card]│  ─────────────────────   │
│                             │  Subtotal       Rp 52.000│
│                             │  Total          Rp 52.000│
│                             │  ─────────────────────   │
│                             │  [Tunai] [QRIS]          │
│                             │  Uang Diterima: [______] │
│                             │  Kembalian:    Rp 48.000 │
│                             │  ─────────────────────   │
│                             │  [Proses Pesanan]        │
└─────────────────────────────┴──────────────────────────┘
```

**Alur JS utama:**

```javascript
// 1. State Management
let cart = []; // Array item di keranjang
let selectedProduct = null;

// 2. Klik produk
function addToCart(product) {
  if (product.modGroups.length > 0) {
    openModifierModal(product); // Tampilkan modal pilih varian
  } else {
    pushToCart(product, [], product.base_price);
  }
}

// 3. Tambah ke keranjang
function pushToCart(product, modifiers, totalPrice) {
  const key = product.id + "_" + modifiers.map((m) => m.id).join("_");
  const existing = cart.find((i) => i.key === key);
  if (existing) {
    existing.qty++;
  } else {
    cart.push({
      key,
      product_id: product.id,
      name: product.name,
      unit_price: totalPrice,
      qty: 1,
      note: "",
      modifiers,
    });
  }
  renderCart();
}

// 4. Kalkulasi kembalian
document.getElementById("cash_received").addEventListener("input", function () {
  const received = parseInt(this.value) || 0;
  const change = received - grandTotal;
  document.getElementById("kembalian").textContent = formatRupiah(change);
  document.getElementById("kembalian").style.color =
    change >= 0 ? "green" : "red";
});

// 5. Submit order
async function prosesOrder() {
  const payload = {
    customer_name: document.getElementById("customer_name").value,
    table_id: document.getElementById("table_id").value,
    fulfillment_type: "DINE_IN",
    payment_method: selectedPayment, // 'CASH' | 'QRIS'
    cash_received:
      parseInt(document.getElementById("cash_received").value) || null,
    items: cart.map((i) => ({
      product_id: i.product_id,
      name: i.name,
      unit_price: i.unit_price,
      qty: i.qty,
      note: i.note,
      modifiers: i.modifiers,
    })),
  };

  const res = await fetch(POS_ORDER_URL, {
    method: "POST",
    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": CSRF_TOKEN },
    body: JSON.stringify(payload),
  });
  const data = await res.json();

  if (data.success) {
    if (selectedPayment === "QRIS") {
      openQrisWaitingModal(data.order); // Tampilkan modal tunggu
    } else {
      openSuccessModal(data.order); // Langsung sukses
    }
  }
}
```

---

## 6. Audio Notifikasi QRIS (Web Speech API)

```javascript
// Konversi angka ke kata Indonesia
function numberToIndonesianWords(n) {
    const units = ['', 'satu', 'dua', 'tiga', 'empat', 'lima',
                   'enam', 'tujuh', 'delapan', 'sembilan'];
    const teens = ['sepuluh', 'sebelas', 'dua belas', /* ... */ 'sembilan belas'];
    // ... implementasi lengkap ratusan, ribuan, jutaan, miliar

    if (n >= 1000000000) return numberToIndonesianWords(Math.floor(n/1000000000)) + ' miliar ' + ...
    if (n >= 1000000)    return numberToIndonesianWords(Math.floor(n/1000000)) + ' juta ' + ...
    if (n >= 1000)       return numberToIndonesianWords(Math.floor(n/1000)) + ' ribu ' + ...
    // ...
}

function speakNominal(amount, method) {
    if (!window.speechSynthesis) return;
    const words = numberToIndonesianWords(amount);
    const text  = `Pembayaran ${method} diterima. ${words} rupiah.`;
    // Contoh: "Pembayaran QRIS diterima. Dua puluh tujuh ribu rupiah."

    const utterance = new SpeechSynthesisUtterance(text);
    utterance.lang  = 'id-ID';
    utterance.rate  = 0.9;
    window.speechSynthesis.speak(utterance);
}
```

---

## 7. Alur Pembayaran

### Tunai (Cash)

```
Kasir input order + uang diterima
    ↓
POST /admin/pos/order
    ↓
createPosOrder('CASH', cash_received)
    ↓
Order: payment_status = 'PAID' (langsung)
Payment: gateway = 'CASH', status = 'settlement'
    ↓
Frontend: tampilkan modal sukses
Cetak struk (buka receipt URL)
```

### QRIS Statis (External)

```
Kasir input order → pilih QRIS
    ↓
POST /admin/pos/order
    ↓
createPosOrder('QRIS', null)
    ↓
Order: payment_status = 'UNPAID'
Payment: gateway = 'QRIS', status = 'pending'
    ↓
Frontend: tampilkan Modal QRIS (gambar dari Settings)
    ↓
Polling GET /admin/pos/check-payment/{order} tiap 3 detik
    ↓
Kasir klik "✅ Sudah Dibayar"
    ↓
POST /admin/pos/confirm-qris/{order}
    ↓
Order: payment_status = 'PAID'
    ↓ (jika audio enabled)
speakNominal(grand_total, 'QRIS')
    ↓
Modal sukses → Cetak struk
```

---

## 8. Struk / Receipt

Struk untuk order POS menampilkan info tambahan:

```blade
@if($order->payment?->gateway === 'CASH')
    💵 Tunai
    @if($order->payment->cash_received)
        Uang Diterima: Rp {{ number_format($order->payment->cash_received) }}
        Kembalian:     Rp {{ number_format($order->payment->cash_change) }}
    @endif
@elseif($order->payment?->gateway === 'QRIS')
    📱 QRIS
@endif

@if($order->order_source === 'POS')
    <small>Order via Kasir</small>
@endif
```

---

## 9. Link Kasir di Sidebar

Tambahkan di `admin-layout.blade.php`, di dalam list menu:

```blade
@if(Setting::getValue('pos_enabled', '1'))
<li>
    <a href="{{ route('admin.pos') }}"
       class="{{ request()->routeIs('admin.pos*') ? 'active' : '' }}">
        {{-- Ikon register/kasir --}}
        <svg>...</svg>
        <span>Kasir</span>
    </a>
</li>
@endif
```

---

## 10. Checklist Implementasi

```
[ ] 1.  Buat migration (order_source, cash_received, cash_change)
[ ] 2.  Jalankan php artisan migrate
[ ] 3.  Update Order::$fillable + Payment::$fillable & $casts
[ ] 4.  Tambah method createPosOrder() ke OrderService
[ ] 5.  Update generateOrderCode() agar terima $prefix
[ ] 6.  Buat AdminPosController (5 methods)
[ ] 7.  Tambah method updatePos() ke AdminSettingController
[ ] 8.  Daftarkan 6 routes POS di cafe_admin.php
[ ] 9.  Buat view admin/pos.blade.php (split-screen UI)
[ ] 10. Tambah section POS di halaman settings
[ ] 11. Tambah link Kasir di sidebar (desktop + mobile)
[ ] 12. Update receipt.blade.php untuk tampilkan tunai/QRIS + kembalian
[ ] 13. Test: buat order cash → cek payment_status PAID
[ ] 14. Test: buat order QRIS → confirm manual → cek status PAID
[ ] 15. Test: audio TTS terbaca
[ ] 16. Test: cetak struk dari modal sukses
```
