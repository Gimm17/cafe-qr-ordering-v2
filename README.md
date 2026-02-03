# Cafe QR Ordering (Laravel 12) — Feature Pack

Ini **feature-pack** (overlay) untuk Laravel 12: QR per meja → menu → cart → checkout → bayar iPaymu → tracking status → admin panel → feedback 1x per order (hanya setelah SELESAI).

> Kenapa “feature pack”? Karena di environment ini tidak ada Composer untuk membangkitkan skeleton Laravel. Jadi kamu:
> 1) buat project Laravel 12 seperti biasa,
> 2) extract zip ini ke root project,
> 3) jalankan migrate + seed.

---

## 1) Cara Pasang (Local)

### A. Buat project Laravel 12
```bash
composer create-project laravel/laravel cafe-qr-ordering
cd cafe-qr-ordering
```

### B. Extract ZIP ini ke root project
Extract dan **merge** folder `app/`, `routes/`, `database/`, `resources/`, `config/`.

### C. Install dependency tambahan (QR Code)
```bash
composer require endroid/qr-code:^6.0
```

### D. Update `.env`
Tambahkan/atur:
```env
APP_URL=http://127.0.0.1:8000

IPAYMU_ENV=sandbox
IPAYMU_VA=YOUR_VA
IPAYMU_API_KEY=YOUR_API_KEY

# URL callback/notify harus publik saat live (gunakan ngrok saat local)
IPAYMU_RETURN_URL=${APP_URL}/ipaymu/return
IPAYMU_CANCEL_URL=${APP_URL}/ipaymu/cancel
IPAYMU_NOTIFY_URL=${APP_URL}/ipaymu/notify
```

### E. Include routes (WAJIB)
Buka `routes/web.php` milik project Laravel kamu, lalu tambahkan di bagian bawah:
```php
require __DIR__.'/cafe.php';
require __DIR__.'/cafe_admin.php';
```

### F. Migrasi & Seeder demo
```bash
php artisan migrate
php artisan db:seed --class=\Database\Seeders\CafeDemoSeeder
php artisan db:seed --class=\Database\Seeders\AdminUserSeeder
```

### G. Jalankan
```bash
php artisan serve
```

---

## 2) Akun Admin (default)
Seeder akan membuat:
- Email: `admin@local.test`
- Password: `admin123`

**Wajib ganti** password di produksi.

---

## 3) Alur pakai cepat
1. Login admin: `/admin/login`
2. Buat meja & generate token/QR: `/admin/tables`
3. Buka QR image, print, tempel di meja
4. Scan QR → masuk `/t/{token}` → menu → checkout → bayar iPaymu
5. Admin update status: `/admin/orders` (DITERIMA → DIPROSES → READY → SELESAI)
6. Setelah SELESAI, customer bisa kirim feedback (1x) di halaman order.

---

## 4) Catatan penting iPaymu
- Signature v2: `HTTPMethod:Va:lowercase(sha256(jsonBody)):ApiKey` lalu HMAC-SHA256 dengan ApiKey. (lihat `app/Services/Ipaymu/IpaymuSigner.php`)
- Endpoint Redirect Payment:
  - Sandbox: `https://sandbox.ipaymu.com/api/v2/payment`
  - Production: `https://my.ipaymu.com/api/v2/payment`

Jika kamu dapat 401 Unauthorized, biasanya karena signature/timestamp/body hash tidak sesuai atau VA/APIKEY salah.

---

## 5) Struktur Fitur
- Public:
  - `/t/{token}` set session table → redirect
  - `/menu`, `/product/{slug}`, `/cart`, `/checkout`
  - `/order/{order_code}` tracking + feedback (SELESAI only)
- Payment:
  - `/pay/{order_code}` create session redirect iPaymu
  - `/ipaymu/notify` webhook (idempotent)
- Admin:
  - `/admin/orders` kanban status
  - `/admin/menu` CRUD kategori & produk
  - `/admin/tables` QR generator per meja
  - `/admin/feedback` list feedback + hide/unhide
  - `/admin/reports` grafik sederhana

Semoga membantu. Kalau kamu mau, next step aku bisa bikinin versi:
- realtime (WebSocket/SSE),
- multi-kasir/barista role,
- printer kitchen ticket,
- modifier (size/sugar/add-on) full UI.
