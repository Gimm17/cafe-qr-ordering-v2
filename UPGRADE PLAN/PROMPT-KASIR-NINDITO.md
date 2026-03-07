# Prompt: Tambahkan Fitur POS/Kasir ke Cafe Nindito

> Copy seluruh teks di bawah ini dan paste ke AI saat mengerjakan project Nindito.

---

```
Saya punya project Laravel cafe QR ordering system bernama "Nindito".
Saya ingin kamu menambahkan fitur POS (Point of Sale) / Kasir ke project ini.

Fitur ini memungkinkan admin/barista membuat pesanan manual langsung dari
panel admin, untuk pelanggan walk-in yang tidak mau scan QR.

Framework: Laravel

Sebelum mulai, tolong analisis dulu:
1. Struktur model Order, Payment, Product, Category, CafeTable yang ada
2. Kolom-kolom yang sudah ada di tabel orders dan payments
3. Apakah sudah ada OrderService atau logika buat order di suatu Service class
4. Struktur routes admin dan middleware yang digunakan

---

BAGIAN A — DATABASE

Buat migration baru untuk menambah:
a) Kolom `order_source` (string, default 'QR') di tabel orders
   Nilai: 'QR' (order dari customer) | 'POS' (order dari kasir)

b) Kolom `cash_received` (integer, nullable) di tabel payments
c) Kolom `cash_change` (integer, nullable) di tabel payments

Setelah migration dibuat, update:
- Order model: tambah 'order_source' ke $fillable
- Payment model: tambah 'cash_received', 'cash_change' ke $fillable dan $casts (integer)

---

BAGIAN B — SETTINGS

Tambahkan 4 setting key baru (simpan di tabel settings dengan pattern
Setting::setValue / Setting::getValue yang sudah ada di project):

| Key                      | Default     | Keterangan                            |
|--------------------------|-------------|---------------------------------------|
| pos_cash_change_enabled  | '1'         | Tampilkan kolom uang tunai & kembalian |
| pos_audio_enabled        | '1'         | Audio TTS saat QRIS masuk             |
| pos_qris_mode            | 'external'  | 'external' atau 'midtrans'            |
| pos_qris_image           | null        | Path gambar QRIS statis               |

---

BAGIAN C — BACKEND

Buat AdminPosController dengan 5 method:

1) index()
   - Load: categories, products (with modGroups+options, active, not sold out), tables
   - Load POS settings dari Setting::getValue
   - Return view 'admin.pos' dengan semua data

2) products()
   - JSON endpoint — return produk sebagai array dengan struktur:
     [{id, name, base_price, image_url, category_id, modGroups: [{id, name,
       is_required, max_select, options: [{id, name, price_modifier}]}]}]

3) store(Request $request)
   - Validasi: customer_name (required), table_id (nullable), fulfillment_type
     (DINE_IN|PICKUP), payment_method (CASH|QRIS), cash_received (nullable int),
     items[] dengan product_id, name, unit_price, qty, note, modifiers[]
   - Hitung grand_total dari items
   - Call OrderService::createPosOrder() (lihat Bagian D)
   - Return JSON: {success, order: {id, order_code, grand_total, payment_status,
     payment_method, receipt_url}}

4) confirmQris(Order $order)
   - Jika payment_status sudah PAID, return error
   - Update order: payment_status = 'PAID'
   - Update payment: status = 'settlement'
   - Return JSON: {success, grand_total}

5) checkPayment(Order $order)
   - Return JSON: {payment_status, grand_total}

---

BAGIAN D — ORDER SERVICE

Tambahkan method createPosOrder() ke OrderService (atau buat baru jika belum ada):

Parameter:
- ?int $tableId
- string $customerName
- string $fulfillmentType
- array $cart
- array $totals (subtotal, tax, service, discount, grand_total)
- string $paymentMethod ('CASH' | 'QRIS')
- ?int $cashReceived

Logika dalam DB::transaction:
1. Generate kode order dengan prefix 'K-' (format: K-YYYYMMDD-XXXXXX)
2. Buat Order dengan:
   - order_source = 'POS'
   - payment_status = CASH ? 'PAID' : 'UNPAID'
3. Buat OrderItem untuk setiap item di cart
4. Buat OrderItemMod untuk setiap modifier per item
5. Buat Payment dengan:
   - gateway = payment_method ('CASH' atau 'QRIS')
   - status = CASH ? 'settlement' : 'pending'
   - cash_received = (jika CASH)
   - cash_change = cash_received - grand_total (jika CASH)

---

BAGIAN E — SETTINGS CONTROLLER

Tambahkan method updatePos() ke AdminSettingController:
- Validasi: pos_qris_image (nullable, image, max 2MB), pos_qris_mode (external|midtrans)
- Simpan pos_cash_change_enabled (checkbox → '1'/'0')
- Simpan pos_audio_enabled (checkbox → '1'/'0')
- Simpan pos_qris_mode
- Jika ada upload gambar QRIS → store ke public disk, simpan path-nya

---

BAGIAN F — ROUTES

Tambahkan ke routes admin (dalam middleware auth admin):

GET  /admin/pos              → AdminPosController@index    (admin.pos)
GET  /admin/pos/products     → AdminPosController@products (admin.pos.products)
POST /admin/pos/order        → AdminPosController@store    (admin.pos.order)
POST /admin/pos/confirm-qris/{order} → AdminPosController@confirmQris
GET  /admin/pos/check-payment/{order} → AdminPosController@checkPayment
POST /admin/settings/pos     → AdminSettingController@updatePos (admin.settings.pos)

---

BAGIAN G — VIEW admin/pos.blade.php

Buat halaman POS dengan layout split-screen:

Panel Kiri (katalog produk):
- Search bar real-time (filter produk tanpa reload)
- Tab filter per kategori (Semua + semua kategori yang ada)
- Grid produk: image, nama, harga, tombol tambah
- Produk dengan modifier → buka modal pilih varian saat diklik

Panel Kanan (panel order):
- Input nama customer (required)
- Dropdown pilih no. meja (optional)
- Daftar item di keranjang:
  * Nama produk + ringkasan modifier (tampil kecil di bawah nama)
  * Tombol qty − / angka / +
  * Input catatan per item
  * Tombol hapus item
  * Harga per baris
- Subtotal dan Grand Total
- Toggle metode bayar: [Tunai] [QRIS]
- Jika Tunai (setting enabled): input "Uang Diterima" + tampilkan "Kembalian"
  (kembalian = input − total, hijau jika cukup, merah jika kurang)
- Tombol "Proses Pesanan" (trigger modal konfirmasi dulu, baru submit)

Modal Modifier (muncul saat klik produk yang punya varian):
- Tampilkan nama produk + harga dasar
- Setiap grup modifier: nama grup + pilihan (radio jika max_select=1, checkbox jika >1)
- Harga modifier ditambahkan ke harga total
- Tombol "Tambahkan ke Keranjang"

Modal QRIS (muncul setelah order QRIS dibuat):
- Judul "Menunggu Pembayaran QRIS"
- Tampilkan gambar QRIS dari Settings
- Tampilkan total yang harus dibayar
- Tombol "✅ Sudah Dibayar" → call confirmQris → jika sukses, lanjut ke modal sukses
- Polling otomatis GET check-payment tiap 3 detik (untuk auto-update jika ada webhook)

Modal Sukses (setelah order berhasil):
- Teks "Pesanan Berhasil!" + centang hijau
- Nomor order (K-YYYYMMDD-XXXXXX)
- Total
- Tombol "🖨️ Cetak Struk" (buka receipt_url di tabs baru)
- Tombol "➕ Pesanan Baru" (reset seluruh form + keranjang)

JavaScript yang harus ada:
- State: cart array, selectedPayment, grandTotal
- addToCart(product): cek apakah ada modifier → modal atau langsung ke cart
- renderCart(): render ulang tampilan keranjang dan total
- submitOrder(): fetch POST ke admin.pos.order dengan JSON payload
- Audio TTS untuk QRIS (Web Speech API):
  * speakNominal(amount): konversi angka ke kata Indonesia, baca via speechSynthesis
  * Panggil setelah konfirmasi QRIS berhasil (jika setting audio enabled)

---

BAGIAN H — SETTINGS PAGE

Tambahkan section "Pengaturan Kasir (POS)" di halaman Settings:

Form (POST ke admin.settings.pos):
1. Toggle: "Tampilkan kolom uang tunai & kembalian" (checkbox, name=pos_cash_change_enabled)
2. Toggle: "Audio notifikasi TTS saat QRIS" (checkbox, name=pos_audio_enabled)
3. Select: Mode QRIS (external = gambar statis | midtrans = generate per transaksi)
4. Upload gambar QRIS statis (file input, jika mode = external)
   - Preview gambar yang sudah di-upload

---

BAGIAN I — SIDEBAR

Tambahkan link "Kasir" di sidebar admin (desktop dan mobile):
- Ikon register/POS
- Route: admin.pos
- Active state saat berada di halaman POS

---

BAGIAN J — RECEIPT / STRUK

Di view receipt/struk, tambahkan kondisi:
- Jika payment.gateway === 'CASH': tampilkan label "💵 Tunai"
  dan jika ada cash_received: tampilkan "Uang Diterima" dan "Kembalian"
- Jika payment.gateway === 'QRIS': tampilkan label "📱 QRIS"
- Jika order.order_source === 'POS': tampilkan keterangan kecil "Order via Kasir"

---

URUTAN IMPLEMENTASI YANG DISARANKAN:
1. Buat dan jalankan migration
2. Update model Order dan Payment
3. Tambah createPosOrder() ke OrderService
4. Buat AdminPosController
5. Tambah updatePos() ke AdminSettingController
6. Daftarkan routes
7. Buat view admin/pos.blade.php
8. Tambah section POS di settings page
9. Tambah link Kasir di sidebar
10. Update receipt/struk

Tolong mulai dari analisis project saya terlebih dahulu,
sesuaikan nama model/kolom/relation dengan yang sudah ada,
baru lanjut implementasi
```
