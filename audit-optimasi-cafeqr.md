# Audit Kode & Rencana Optimasi — Cafe QR Ordering (Laravel 12)

> Dokumen ini merangkum hasil pemeriksaan proyek `cafe-qr-ordering_v2.zip` dan rencana optimasi agar aplikasi lebih ringan, responsif, dan minim request berlebihan.

## Ringkasan Masalah Utama yang Terlihat Cepat

- **Polling status pesanan tiap 10 detik** di halaman user (`resources/views/cafe/order.blade.php`) → berpotensi membebani server jika user banyak.

- **SESSION + CACHE + QUEUE pakai database** (di `.env`) → DB bisa jadi bottleneck (3 beban sekaligus).

- **Scheduler cleanup tiap menit** (`routes/console.php`) → kalau cron `schedule:run` dipasang per menit, ini bisa jadi beban konstan.

- **Query `whereDate()`** di dashboard admin → bisa membuat index `created_at` tidak kepakai (lebih lambat untuk dataset besar).

- **Admin order board memuat semua order** (tanpa batas/periode) → makin lama bisa berat.

## Struktur Proyek (tree ringkas)

```text
cafe-qr-ordering
├── app
│   ├── Console
│   │   └── Commands
│   │       ├── CleanupPendingOrders.php
│   │       └── TestIpaymu.php
│   ├── Http
│   │   ├── Controllers
│   │   │   ├── Admin
│   │   │   ├── Cafe
│   │   │   ├── Controller.php
│   │   │   └── HomeController.php
│   │   └── Middleware
│   │       ├── EnsureAdmin.php
│   │       └── EnsureTableContext.php
│   ├── Models
│   │   ├── CafeTable.php
│   │   ├── Category.php
│   │   ├── ModGroup.php
│   │   ├── ModOption.php
│   │   ├── Order.php
│   │   ├── OrderFeedback.php
│   │   ├── OrderItem.php
│   │   ├── OrderItemMod.php
│   │   ├── Payment.php
│   │   ├── PaymentEvent.php
│   │   ├── Product.php
│   │   ├── TableToken.php
│   │   └── User.php
│   ├── Providers
│   │   ├── AppServiceProvider.php
│   │   └── CafeServiceProvider.php
│   └── Services
│       ├── Ipaymu
│       │   ├── IpaymuClient.php
│       │   └── IpaymuSigner.php
│       ├── CartService.php
│       ├── OrderService.php
│       └── QrService.php
├── bootstrap
│   ├── cache
│   │   ├── .gitignore
│   │   ├── packages.php
│   │   └── services.php
│   ├── app.php
│   └── providers.php
├── config
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── ipaymu.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   └── session.php
├── database
│   ├── factories
│   │   └── UserFactory.php
│   ├── migrations
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2026_02_03_000001_create_cafe_tables.php
│   │   ├── 2026_02_03_000002_create_table_tokens.php
│   │   ├── 2026_02_03_000003_create_categories_products.php
│   │   ├── 2026_02_03_000004_create_orders.php
│   │   ├── 2026_02_03_000005_create_payments.php
│   │   ├── 2026_02_03_000006_create_order_feedback.php
│   │   ├── 2026_02_03_000007_add_is_admin_to_users.php
│   │   ├── 2026_02_03_000008_create_modifiers.php
│   │   └── 2026_02_03_000009_add_fields_to_products.php
│   ├── seeders
│   │   ├── AdminUserSeeder.php
│   │   ├── CafeDemoSeeder.php
│   │   └── DatabaseSeeder.php
│   ├── .gitignore
│   └── database.sqlite
├── public
│   ├── storage
│   │   ├── products
│   │   │   └── hBiQv51CFy7KqpW23vHCE6PzjPGh2uCRCvJX251S.jpg
│   │   └── .gitignore
│   ├── .htaccess
│   ├── favicon.ico
│   ├── index.php
│   └── robots.txt
├── resources
│   ├── css
│   │   └── app.css
│   ├── js
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views
│       ├── admin
│       │   ├── categories.blade.php
│       │   ├── dashboard.blade.php
│       │   ├── feedback.blade.php
│       │   ├── login.blade.php
│       │   ├── menu_index.blade.php
│       │   ├── modifier_form.blade.php
│       │   ├── modifiers.blade.php
│       │   ├── order_show.blade.php
│       │   ├── orders.blade.php
│       │   ├── product_form.blade.php
│       │   ├── products.blade.php
│       │   ├── reports.blade.php
│       │   └── tables.blade.php
│       ├── cafe
│       │   ├── cart.blade.php
│       │   ├── checkout.blade.php
│       │   ├── history.blade.php
│       │   ├── invalid_qr.blade.php
│       │   ├── menu.blade.php
│       │   ├── order.blade.php
│       │   ├── product.blade.php
│       │   └── table_landing.blade.php
│       ├── components
│       │   ├── admin-layout.blade.php
│       │   └── cafe-layout.blade.php
│       ├── home.blade.php
│       └── welcome.blade.php
├── routes
│   ├── cafe.php
│   ├── cafe_admin.php
│   ├── console.php
│   └── web.php
├── storage
│   ├── app
│   │   ├── private
│   │   │   └── .gitignore
│   │   ├── public
│   │   │   ├── products
│   │   │   └── .gitignore
│   │   └── .gitignore
│   ├── framework
│   │   ├── cache
│   │   │   ├── data
│   │   │   └── .gitignore
│   │   ├── sessions
│   │   │   └── .gitignore
│   │   ├── testing
│   │   │   └── .gitignore
│   │   ├── views
│   │   │   └── .gitignore
│   │   └── .gitignore
│   └── logs
│       ├── .gitignore
│       └── laravel.log
├── tests
│   ├── Feature
│   │   └── ExampleTest.php
│   ├── Unit
│   │   └── ExampleTest.php
│   └── TestCase.php
├── .editorconfig
├── .env
├── .env.example
├── .gitattributes
├── .gitignore
├── artisan
├── composer.json
├── composer.lock
├── cpanel.txt
├── DO_NOT_OVERWRITE_NOTE.txt
├── INSTALL_NOTES.md
├── package.json
├── phpunit.xml
├── RANCANGAN_CAFE_QR_ORDERING.md
├── README.md
├── REGISTER_PROVIDER.md
└── vite.config.js
```

## Review Terstruktur (File-by-File)

### 1) Bootstrap & Routing

- `bootstrap/app.php`
  - Mengaktifkan route groups: `web.php` (root), `cafe.php` (prefix `/cafe`), `cafe_admin.php` (prefix `/admin`).
  - Catatan: Pastikan QR route yang dipakai user konsisten (root `/t/{token}` atau `/cafe/t/{token}`), jangan dobel.

- `routes/web.php`
  - Home (`/`), QR enter (`/t/{token}`), dan callback iPaymu (`/ipaymu/*`).
  - Catatan: endpoint `notify` hanya POST. Jika gateway melakukan validasi URL via GET/HEAD, bisa ditolak. Pertimbangkan `match(['GET','POST'])` untuk `/ipaymu/notify`.

- `routes/cafe.php`
  - Semua halaman customer di prefix `/cafe/*`.
  - Catatan: Ada duplikasi `t/{token}` di sini juga (jadi `/cafe/t/{token}`), pilih salah satu untuk production.

- `routes/cafe_admin.php`
  - Semua halaman admin/kasir di prefix `/admin/*`.


### 2) Middleware

- `app/Http/Middleware/EnsureTableContext.php`
  - Memastikan session punya `table_id` dan `table_label` sebelum akses halaman cafe.
  - Catatan: Redirect ke `/` kalau belum ada. Untuk UX, bisa arahkan ke halaman info “scan QR dulu”.

- `app/Http/Middleware/EnsureAdmin.php`
  - Memastikan user login admin.


### 3) Customer Controllers (`app/Http/Controllers/Cafe/*`)

- `TableSessionController.php`
  - Validasi token QR → set session table.
  - Catatan: route `enterByNumber` berguna untuk testing, tapi rawan disalahgunakan (orang bisa guess nomor meja). Batasi hanya admin / non-production.

- `MenuController.php`
  - Menampilkan menu + kategori.
  - Catatan: cocok diberi **cache** karena data menu tidak berubah setiap detik.

- `CartController.php` + `CartService.php`
  - Keranjang disimpan di session; `CartService` menambahkan format rupiah.
  - Catatan: Pastikan view selalu memakai `getFormattedItems()`; kalau ada view yang pakai raw session item, bisa muncul error key yang tidak ada.

- `CheckoutController.php`
  - Membuat order dan items dari cart.
  - Catatan: sudah transaksi + validasi dasar. Bisa tambah `throttle` / anti double-submit.

- `PaymentController.php`
  - Membuat payment redirect ke iPaymu.
  - Catatan: 401 unauthorized signature sering terkait payload/URL callback. Lihat bagian “Fix iPaymu & Hardening” di bawah.

- `OrderController.php`
  - `show()` menampilkan detail order; `statusJson()` untuk polling.
  - Catatan: endpoint `statusJson` adalah titik utama request berulang. Optimasi dengan ETag/backoff atau real-time.

- `FeedbackController.php`
  - Ulasan anonymous setelah order selesai.

- `IpaymuWebhookController.php`
  - Menerima notify dari iPaymu.
  - Catatan: Belum ada verifikasi signature webhook (wajib untuk keamanan).


### 4) Admin Controllers (`app/Http/Controllers/Admin/*`)

- `AdminAuthController.php`
  - Login/logout admin; memeriksa `is_admin`.

- `AdminOrderController.php`
  - Kanban order per status; update status.
  - Catatan: `index()` mengambil semua order; sebaiknya batasi ke hari ini / last 2-3 hari.

- `AdminDashboardController.php`
  - Ringkasan metrik + chart.
  - Catatan: `whereDate()` perlu diganti ke range `whereBetween(created_at, [start,end])`.

- `AdminReportController.php`
  - Laporan 7 hari / 30 hari.
  - Catatan: sudah batasi periode; tambah cache untuk query yang sama.

- `AdminMenuController.php`, `AdminCategoryController.php`, `AdminProductController.php`
  - CRUD menu.
  - Catatan: pastikan upload image memakai validation dan storage link.

- `AdminFeedbackController.php`
  - Moderasi komentar.


### 5) Models & Database

- Model utama: `Order`, `OrderItem`, `Payment`, `Product`, `Category`, `CafeTable`, `TableToken`, `OrderFeedback`, dll.

- Migrations:
  - `create_orders.php`: punya `order_code` unique, FK table_id.
  - `create_payments.php`: `order_id` unique.
  - `create_order_feedback.php`: `order_id` unique, cascadeOnDelete.
  - Catatan: Tambah index untuk kolom yang sering difilter: `order_status`, `payment_status`, `created_at`.


### 6) Views (UI)

- Customer:
  - `cafe/order.blade.php` melakukan polling `fetch(statusJson)` tiap 10 detik dan reload bila status berubah.
  - Rekomendasi: update DOM tanpa reload + backoff + pause ketika tab tidak aktif.

- Admin:
  - halaman order admin tidak polling; butuh realtime jika mau live.


### 7) Console & Scheduler

- `routes/console.php` menjalankan `orders:cleanup-pending` **setiap menit**.

- `CleanupPendingOrders.php` melakukan loop delete per order.
  - Rekomendasi: gunakan cascade delete DB + `chunkById()` atau delete massal untuk mengurangi query.


## Rencana Optimasi Terstruktur (Plan)

### Phase 0 — Baseline (wajib sebelum optimasi)

1. Catat metrik: TTFB, query count, slow query, CPU/mem, ukuran log.
2. Aktifkan `APP_DEBUG=false`, turunkan `LOG_LEVEL` (production). Simpan debug hanya saat tracing.


### Phase 1 — Quick Wins (paling cepat terasa)

1. Jalankan cache produksi:
   - `php artisan optimize` (atau minimal `config:cache`, `route:cache`, `view:cache`).
2. Aktifkan OPcache di cPanel (kalau tersedia).
3. Ganti driver berat:
   - `CACHE_STORE=file` (atau redis)
   - `SESSION_DRIVER=file` (atau redis)
   - `QUEUE_CONNECTION=database` tetap boleh untuk awal, tapi pastikan job table ter-maintain.


### Phase 2 — Database & Query

1. Tambah index:
   - `orders(order_status, created_at)`
   - `orders(payment_status, created_at)`
   - `orders(table_id, created_at)`
2. Hindari `whereDate()` untuk tabel besar, gunakan range `whereBetween(created_at, ...)`.
3. Audit N+1: pastikan `with()` dipakai bila loop memanggil relasi.


### Phase 3 — Caching Aplikasi

1. Cache menu (kategori+produk) 30-120 detik atau cache panjang + invalidasi saat CRUD.
2. Cache dashboard (today/weekly/monthly) 30-60 detik.
3. Cache report agregasi 1-5 menit.


### Phase 4 — Real-time & Pengurangan Request

Opsi A (paling bagus): WebSocket (Laravel Reverb / Pusher)
- Broadcast event saat status order berubah → user langsung update tanpa polling.


Opsi B (lebih simple di shared hosting): Polling yang “cerdas”
- Tambah ETag/If-None-Match agar response 304 ketika tidak ada perubahan.
- Exponential backoff (10s → 20s → 40s) selama status belum berubah.
- Pause polling ketika `document.hidden`.


### Phase 5 — Background Jobs & Webhook Safety

1. Pindahkan proses berat ke queue (misal: log payment event, kirim notifikasi, generate report).
2. Implement verifikasi signature webhook iPaymu.
3. Pastikan endpoint notify menerima POST dan response cepat (< 1-2 detik).


### Phase 6 — Hardening & Operasional

1. Rate limit:
   - `/cafe/order/{code}/status` (mis. 30/min per IP)
   - `/ipaymu/*` secukupnya
2. Security:
   - nonaktifkan `enterByNumber` di production.
   - validasi input lebih ketat.
3. Cron di cPanel:
   - `php artisan schedule:run` tiap 1-5 menit (sesuaikan)
   - queue worker (jika ada) atau `queue:work --stop-when-empty` via cron.


## Patch yang Direkomendasikan (Snippet)

### 1) Ubah notify agar aman untuk validasi GET/HEAD

```php
// routes/web.php
Route::match(['GET','POST'], '/ipaymu/notify', [IpaymuWebhookController::class, 'notify'])->name('ipaymu.notify');
```

```php
// app/Http/Controllers/Cafe/IpaymuWebhookController.php
public function notify(Request $req)
{
    if ($req->isMethod('get')) {
        return response('OK', 200);
    }
    // ... lanjut handle POST seperti sekarang
}
```

### 2) Ganti whereDate jadi range

```php
$start = now()->startOfDay();
$end   = now()->endOfDay();
$todaySales = Order::whereBetween('created_at', [$start, $end])
    ->where('payment_status', 'PAID')
    ->sum('grand_total');
```

### 3) ETag untuk statusJson (mengurangi payload & request berat)

```php
public function statusJson(string $order_code, Request $req)
{
    $order = Order::where('order_code', $order_code)->firstOrFail();
    $etag = 'W/"'.$order->updated_at->timestamp.'-'.$order->order_status.'-'.$order->payment_status.'"';

    if ($req->headers->get('If-None-Match') === $etag) {
        return response('', 304)->setEtag($etag);
    }

    return response()->json([
        'order_status'   => $order->order_status,
        'payment_status' => $order->payment_status,
        'updated_at'     => $order->updated_at->toIso8601String(),
    ])->setEtag($etag);
}
```

## Sumber Referensi (untuk pendalaman)

- Laravel Deployment / Optimize: https://laravel.com/docs/12.x/deployment

- Laravel Cache: https://laravel.com/docs/12.x/cache

- Laravel Broadcasting: https://laravel.com/docs/12.x/broadcasting

- Laravel Reverb: https://laravel.com/docs/12.x/reverb

- Laravel Queues: https://laravel.com/docs/12.x/queues

- whereDate & index: https://github.com/laravel/framework/issues/41522

- Diskusi session driver: https://laracasts.com/discuss/channels/general-discussion/database-vs-cookies-vs-redis-whats-best-for-sessions
