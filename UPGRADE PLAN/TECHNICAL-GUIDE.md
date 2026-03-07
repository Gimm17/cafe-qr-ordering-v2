# Technical Implementation Guide

## Peningkatan Backend — Cafe QR Project

> Ditulis dari sesi Soraya Cafe · 6–7 Maret 2026
> Siap diaplikasikan ke project cafe lain

---

## ⚠️ Prasyarat

- Laravel 10/11
- Midtrans Snap sebagai payment gateway
- Model: `Order`, `Payment` dengan kolom `payment_status`, `order_status`
- Kolom `order_status` values: `DITERIMA`, `DIPROSES`, `SELESAI`, dll

---

## 1. Fix SSL Midtrans di Localhost

**Masalah:** cURL error SSL certificate saat call Midtrans API di local dev.

**File:** `app/Services/Midtrans/MidtransClient.php`

```php
// Ganti nilai CURLOPT_SSL_VERIFYPEER:
// SEBELUM
CURLOPT_SSL_VERIFYPEER => true,

// SESUDAH — matikan hanya di non-production
CURLOPT_SSL_VERIFYPEER => app()->environment('production'),
```

Terapkan di **semua** fungsi yang pakai cURL: `createSnapToken()` dan `getStatus()`.

---

## 2. Client-Side Payment Verify (Bypass Webhook Localhost)

**Masalah:** Webhook Midtrans tidak bisa menjangkau localhost. Setelah Snap popup sukses, status order tidak berubah ke PAID.

**Solusi:** Setelah `onSuccess` atau `onPending` dipanggil Snap JS, browser trigger endpoint verify di server. Server yang query ke Midtrans API (bukan percaya browser).

### 2a. Route

**File:** `routes/cafe.php` (atau web.php)

```php
Route::post('/pay/{order:order_code}/verify', [PaymentController::class, 'verifyPayment'])
    ->name('cafe.pay.verify');
```

### 2b. Controller

**File:** `app/Http/Controllers/Cafe/PaymentController.php`

```php
public function verifyPayment(Order $order)
{
    $status = $this->paymentService->syncPaymentStatus($order);
    return response()->json(['status' => $status]);
}
```

### 2c. Frontend Snap JS

**File:** `resources/views/cafe/pay.blade.php`

```javascript
const VERIFY_URL = @json(route('cafe.pay.verify', ['order' => $order->order_code]));
const CSRF_TOKEN = @json(csrf_token());
const ORDER_URL  = @json(route('cafe.order.show', ['order' => $order->order_code]));

function verifyAndRedirect(delay = 500) {
    fetch(VERIFY_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
    })
    .then(res => res.json())
    .then(() => setTimeout(() => window.location.href = ORDER_URL, delay))
    .catch(() => setTimeout(() => window.location.href = ORDER_URL, delay));
}

window.snap.pay(SNAP_TOKEN, {
    onSuccess: function(result) { verifyAndRedirect(500); },
    onPending: function(result) { verifyAndRedirect(2000); },
    onError:   function(result) { /* tampilkan pesan error */ },
    onClose:   function()       { /* tampilkan tombol bayar lagi */ }
});
```

---

## 3. Admin Realtime Polling Orders (ETag + Backoff)

**Masalah:** Admin harus refresh manual untuk lihat order baru dari customer.

**Solusi:** JavaScript di halaman admin polling endpoint JSON tiap N detik. Server pakai ETag. Browser kirim `If-None-Match`. Jika data sama → `304 Not Modified` tanpa transfer data.

### 3a. Route

**File:** `routes/cafe_admin.php`

```php
Route::get('/admin/orders/poll', [AdminOrderController::class, 'ordersJson'])
    ->name('admin.orders.json');
```

### 3b. Controller — JSON Endpoint

**File:** `app/Http/Controllers/Admin/AdminOrderController.php`

```php
public function ordersJson(Request $request)
{
    $orders = Order::with('table')
        ->where('created_at', '>=', now()->subDays(7))
        ->whereNotIn('order_status', ['SELESAI'])
        ->orderByDesc('created_at')
        ->get(['id', 'order_code', 'order_status', 'payment_status',
               'customer_name', 'total', 'created_at', 'table_id']);

    $payload = $orders->map(fn($o) => [
        'id'             => $o->id,
        'order_code'     => $o->order_code,
        'order_status'   => $o->order_status,
        'payment_status' => $o->payment_status,
        'customer_name'  => $o->customer_name,
        'total'          => $o->total,
        'table'          => $o->table?->name,
        'created_at'     => $o->created_at->diffForHumans(),
    ]);

    // ETag — 304 Not Modified jika tidak ada perubahan
    $etag = '"' . md5($payload->toJson()) . '"';
    if (request()->header('If-None-Match') === $etag) {
        return response('', 304)->header('ETag', $etag);
    }

    return response()->json($payload)
        ->header('ETag', $etag)
        ->header('Cache-Control', 'no-cache');
}
```

### 3c. Frontend JS Polling

**File:** `resources/views/admin/orders.blade.php` — di bagian `<script>`

```javascript
const POLL_URL  = @json(route('admin.orders.json'));
const CSRF_META = document.querySelector('meta[name="csrf-token"]')?.content;

let pollEtag         = null;
let pollInterval     = 10000; // mulai 10 detik
let pollFailCount    = 0;
const POLL_MAX_INT   = 120000; // max 2 menit

function pollOrders() {
    const headers = { 'Accept': 'application/json' };
    if (pollEtag) headers['If-None-Match'] = pollEtag;

    fetch(POLL_URL, { headers })
        .then(res => {
            pollEtag = res.headers.get('ETag') || pollEtag;
            pollFailCount = 0;
            pollInterval  = 10000; // reset ke normal

            if (res.status === 304) return; // tidak ada perubahan
            if (!res.ok) throw new Error('Server error');

            return res.json().then(data => {
                // Panggil fungsi update UI kamu di sini
                // Contoh: updateOrderKanban(data);
                console.log('Orders updated:', data.length);
            });
        })
        .catch(() => {
            pollFailCount++;
            // Exponential backoff: 10s → 20s → 40s → max 2 menit
            pollInterval = Math.min(pollInterval * 2, POLL_MAX_INT);
        })
        .finally(() => {
            setTimeout(pollOrders, pollInterval);
        });
}

// Mulai polling
pollOrders();

// Stop saat tab tidak aktif, resume saat aktif lagi
document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') pollOrders();
});
```

---

## 4. Fix Tombol "Lihat Struk" Setelah SELESAI

**Masalah:** Tombol Lihat Struk / Download Struk baru muncul setelah customer mengisi review. Jika tidak review, tidak bisa akses struk.

**Fix:** Ubah kondisi kemunculan tombol dari `hasReview` menjadi `order_status === SELESAI`.

**File:** `resources/views/cafe/order.blade.php`

```blade
{{-- SEBELUM (salah) --}}
@if($order->order_status === 'SELESAI' && $order->feedback)
    <a href="{{ route('cafe.order.receipt', $order->order_code) }}">Lihat Struk</a>
@endif

{{-- SESUDAH (benar) --}}
@if($order->order_status === 'SELESAI')
    <a href="{{ route('cafe.order.receipt', $order->order_code) }}">Lihat Struk</a>
@endif

{{-- Review tetap terpisah dan opsional --}}
@if($order->order_status === 'SELESAI' && !$order->feedback)
    {{-- Tampilkan form review --}}
@endif
```

---

## 5. SweetAlert2 — Konfirmasi Semua Tombol CRUD

**Masalah:** Tombol hapus/toggle di admin ada yang langsung eksekusi tanpa konfirmasi.

**Setup:** Pastikan SweetAlert2 sudah di-include (CDN atau npm).

```html
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
```

### Template Mixin (taruh di layout atau bottom scripts)

```javascript
const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 3000,
});

// Konfirmasi destructive (hapus)
function confirmDelete(formOrUrl, message = "Data akan dihapus permanen!") {
  Swal.fire({
    title: "Yakin?",
    text: message,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dc2626",
    cancelButtonColor: "#6b7280",
    confirmButtonText: "Ya, Hapus!",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      if (typeof formOrUrl === "string") {
        window.location.href = formOrUrl;
      } else {
        formOrUrl.submit();
      }
    }
  });
}

// Konfirmasi toggle (aktif/nonaktif)
function confirmToggle(form, namaItem, statusBaru) {
  Swal.fire({
    title: `${statusBaru} ${namaItem}?`,
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#1d4ed8",
    cancelButtonColor: "#6b7280",
    confirmButtonText: "Ya",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) form.submit();
  });
}
```

### Penggunaan di Blade

```blade
{{-- Tombol Hapus --}}
<button type="button"
    onclick="confirmDelete(document.getElementById('form-hapus-{{ $item->id }}'))">
    Hapus
</button>
<form id="form-hapus-{{ $item->id }}" method="POST"
    action="{{ route('admin.items.destroy', $item) }}" style="display:none;">
    @csrf @method('DELETE')
</form>

{{-- Toggle Status --}}
<button type="button"
    onclick="confirmToggle(document.getElementById('form-toggle-{{ $item->id }}'),
             '{{ $item->name }}', '{{ $item->is_active ? "Nonaktifkan" : "Aktifkan" }}')">
    {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
</button>
<form id="form-toggle-{{ $item->id }}" method="POST"
    action="{{ route('admin.items.toggle', $item) }}" style="display:none;">
    @csrf
</form>
```

---

## 6. Sistem Pembayaran 3 Jalur (Multi-Project Midtrans)

**Masalah:** 1 akun Midtrans hanya punya 1 webhook URL. Jika dipakai di banyak project, hanya 1 yang dapat notifikasi.

**Solusi:** Tambahkan Cron Job sebagai jalur ke-3 yang tidak bergantung webhook.

```
Jalur 1: Client-Side  → browser trigger → server cek API (cepat)
Jalur 2: Webhook      → Midtrans push (jika URL terdaftar)
Jalur 3: Cron Job     → server poll tiap 5 menit (safety net)
```

### 6a. PaymentService (Service Terpusat)

**File:** `app/Services/PaymentService.php` — **buat baru**

```php
<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Services\Midtrans\MidtransClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(private MidtransClient $midtrans) {}

    public function syncPaymentStatus(Order $order): string
    {
        if ($order->payment_status === 'PAID') return 'PAID';

        $statusData = $this->midtrans->getStatus($order->order_code);

        if (!$statusData) {
            Log::warning('PaymentService: Cannot reach Midtrans', ['order' => $order->order_code]);
            return 'unknown';
        }

        $txStatus    = strtolower($statusData['transaction_status'] ?? '');
        $fraudStatus = strtolower($statusData['fraud_status'] ?? 'accept');
        $txId        = $statusData['transaction_id'] ?? null;

        return DB::transaction(function () use ($order, $txStatus, $fraudStatus, $txId, $statusData) {
            $order = Order::lockForUpdate()->find($order->id); // Anti race condition

            if ($order->payment_status === 'PAID') return 'PAID'; // Double-check

            $payment = Payment::firstOrCreate(
                ['order_id' => $order->id],
                ['gateway'  => 'midtrans', 'status' => 'PENDING']
            );

            if (in_array($txStatus, ['capture', 'settlement'])) {
                if ($txStatus === 'capture' && $fraudStatus !== 'accept') return 'challenge';

                $order->update([
                    'payment_status' => 'PAID',
                    'order_status'   => $order->order_status === 'DITERIMA' ? 'DIPROSES' : $order->order_status,
                ]);
                $payment->update(['status' => 'PAID', 'gateway_trx_id' => $txId, 'raw_response' => $statusData]);

                Log::info('PaymentService: PAID', ['order' => $order->order_code]);
                return 'PAID';
            }

            if ($txStatus === 'pending') {
                $order->update(['payment_status' => 'PENDING']);
                $payment->update(['status' => 'PENDING', 'gateway_trx_id' => $txId]);
                return 'PENDING';
            }

            if (in_array($txStatus, ['deny', 'cancel'])) {
                $order->update(['payment_status' => 'FAILED']);
                $payment->update(['status' => 'FAILED', 'gateway_trx_id' => $txId]);
                return 'FAILED';
            }

            if ($txStatus === 'expire') {
                $order->update(['payment_status' => 'EXPIRED']);
                $payment->update(['status' => 'EXPIRED', 'gateway_trx_id' => $txId]);
                return 'EXPIRED';
            }

            return $txStatus;
        });
    }
}
```

### 6b. Artisan Command

**File:** `app/Console/Commands/CheckPendingPayments.php` — **buat baru**

```php
<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckPendingPayments extends Command
{
    protected $signature = 'payments:check-pending
                            {--hours=24 : Hanya cek order dalam N jam terakhir}
                            {--limit=10 : Maksimal order per run}';

    protected $description = 'Cek status pembayaran pending ke Midtrans API (cron fallback)';

    public function handle(PaymentService $paymentService): int
    {
        $pendingOrders = Order::where('payment_status', 'PENDING')
            ->where('created_at', '>=', now()->subHours($this->option('hours')))
            ->whereHas('payment', fn($q) => $q->where('gateway', 'midtrans'))
            ->orderBy('created_at', 'desc')
            ->limit($this->option('limit'))
            ->get();

        if ($pendingOrders->isEmpty()) {
            $this->info('Tidak ada pending payment.');
            return self::SUCCESS;
        }

        $this->info("Memeriksa {$pendingOrders->count()} order...");

        foreach ($pendingOrders as $order) {
            try {
                $status = $paymentService->syncPaymentStatus($order);
                $this->line("  [{$order->order_code}] → {$status}");
                if ($pendingOrders->count() > 1) sleep(1); // Hindari rate limit
            } catch (\Throwable $e) {
                Log::error('CheckPendingPayments error', ['order' => $order->order_code, 'error' => $e->getMessage()]);
                $this->error("  [{$order->order_code}] ERROR: {$e->getMessage()}");
            }
        }

        return self::SUCCESS;
    }
}
```

### 6c. Daftarkan Schedule

**File:** `routes/console.php`

```php
use Illuminate\Support\Facades\Schedule;

// Cek pending payment tiap 5 menit (fallback multi-project)
Schedule::command('payments:check-pending')->everyFiveMinutes();
```

### 6d. Aktifkan di Server (Production)

Tambahkan ke crontab server:

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

### 6e. Inject PaymentService ke Controller

**File:** `app/Http/Controllers/Cafe/PaymentController.php`

```php
// Tambah import
use App\Services\PaymentService;

// Update constructor
public function __construct(
    private MidtransClient $midtrans,
    private PaymentService $paymentService,  // ← tambah ini
) {}

// Sederhanakan verifyPayment
public function verifyPayment(Order $order)
{
    $status = $this->paymentService->syncPaymentStatus($order);
    return response()->json(['status' => $status]);
}
```

---

## Checklist Implementasi

```
[ ] 1. Fix SSL: CURLOPT_SSL_VERIFYPEER => app()->environment('production')
[ ] 2. Tambah route POST /pay/{order}/verify
[ ] 3. Tambah verifyPayment() di PaymentController
[ ] 4. Update Snap JS: onSuccess → verifyAndRedirect()
[ ] 5. Tambah route GET /admin/orders/poll
[ ] 6. Tambah ordersJson() di AdminOrderController
[ ] 7. Tambah JS polling di admin orders view
[ ] 8. Fix kondisi Lihat Struk (hapus && $order->feedback)
[ ] 9. Setup SweetAlert2 CDN + helper functions
[ ] 10. Ganti confirm() browser dengan confirmDelete() / confirmToggle()
[ ] 11. Buat app/Services/PaymentService.php
[ ] 12. Buat app/Console/Commands/CheckPendingPayments.php
[ ] 13. Daftarkan schedule di routes/console.php
[ ] 14. Inject PaymentService ke PaymentController
[ ] 15. Tambah crontab di server production
```
