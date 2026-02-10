# SENTINEL Audit Report — CafeQR Ordering

**Target:** https://cafeqr.gimmhost.my.id/  
**Audit date:** 10 Feb 2026 (Asia/Jakarta)  
**Scope:** Public customer flow (QR table → menu/cart/checkout/order), admin panel, iPaymu integration, and codebase snapshot (`cafe-qr-ordering-source-code.zip`).  
**Method:** Static code review + threat modeling (OWASP-style) + performance & responsive checklist.

---

## Executive summary

**Overall risk: HIGH.**

Paling berbahaya adalah **kebocoran kredensial/secret** di artefak deploy/source, serta **callback payment yang bisa “mensukseskan” order tanpa verifikasi server-to-server**. Ini harus dianggap **P0** dan diselesaikan dulu sebelum optimasi UI/performance.

### Top 5 tindakan darurat (P0)
1) **Revoke & rotate semua secret yang kebocoran** (GitHub token, DB credential, iPaymu key, APP_KEY bila pernah ter-deploy).  
2) **Hapus informasi “default admin” di login page** dan pastikan admin production tidak memakai default password.  
3) **Webhook iPaymu: jangan set PAID hanya dari payload callback** → wajib verifikasi transaksi ke endpoint Check Transaction.  
4) **Admin login: tambah rate limit + stop “remember me” default.**  
5) **Authorization order-per-table:** pastikan order yang dibuka/status-check **harus match** `session('cafe_table_id')`.

---

## Severity scale

- **P0 (Critical):** bisa menyebabkan takeover, pembayaran palsu, data leak besar.  
- **P1 (High):** risiko tinggi, butuh patch segera setelah P0.  
- **P2 (Medium/Low):** hardening & optimasi.

---

## Findings (ringkas)

| ID | Severity | Temuan | Impact singkat |
|---|---|---|---|
| F-01 | P0 | Secret/kredensial ada di file deploy/source (`cpanel.txt`, `.env`, seeder default) | Account takeover / data leak |
| F-02 | P0 | Admin login menampilkan default credential di UI | Mempermudah brute-force/akses ilegal |
| F-03 | P0 | Webhook iPaymu langsung set `PAID` tanpa verifikasi server-to-server | Order bisa dianggap bayar padahal tidak |
| F-04 | P1 | Admin login tanpa rate-limit, dan `remember=true` by default | Brute-force + session persistence risk |
| F-05 | P1 | Order endpoints tidak memverifikasi order milik table session yang sama | Potensi IDOR lintas meja |
| F-06 | P1 | Logging sensitif di iPaymu client | Secret/PII bisa bocor ke log |
| F-07 | P1 | `is_admin` ada di `$fillable` | Potensi privilege escalation di masa depan |
| F-08 | P2 | Tailwind via CDN pada banyak halaman | Perf lebih berat & kontrol build minim |
| F-09 | P2 | Polling order status perlu throttle & cache-control konsisten | Risiko beban server |

---

## Detailed findings

### F-01 — Secret/kredensial ada di file deploy/source (P0)

**Evidence:**
- `cpanel.txt` berisi credential DB dan *GitHub personal access token* (PAT) — **harus dianggap sudah bocor**. (contoh line: 9)
- `.env` di bundle berisi `APP_KEY` dan iPaymu sandbox key (contoh line: 70).
- Seeder membuat admin default: `database/seeders/AdminUserSeeder.php` line 14.

**Impact:**
- Jika file-file ini pernah tersimpan di server/repo publik atau terbaca pihak lain → **takeover** (GitHub repo, database, dan aplikasi).

**Fix (wajib):**
1) **Revoke token GitHub** dan buat token baru dengan scope minimum (idealnya pakai deploy key / GitHub Actions, bukan PAT manual).  
2) **Rotate password database** + cek user/permission (least privilege).  
3) Pastikan `.env` **tidak pernah** masuk repo publik, dan **document root** benar-benar ke folder `public/` (bukan root project).  
4) Ganti default admin password dan/atau hapus seeder default untuk production.

---

### F-02 — Default credential ditampilkan di Admin Login (P0)

**Evidence:**
- `resources/views/admin/login.blade.php` line 33: menampilkan “Default (demo): …”.

**Impact:**
- Mempermudah serangan brute-force/credential stuffing.
- Jika seeder default sempat dipakai di production, risiko takeover meningkat.

**Fix:**
- Hapus teks default credential dari view.
- Pastikan admin production **unik** & password kuat.
- Tambah rate limit (lihat F-04).

---

### F-03 — Webhook iPaymu set `PAID` tanpa verifikasi (P0)

**Evidence:**
- Route webhook menerima GET+POST: `routes/web.php` line 17.
- Webhook controller:
  - menyimpan event dengan `is_valid => true` walau ada TODO validasi (line 51),
  - dan jika `status_code == 1` langsung set `payment_status => PAID` (line 63).

**Impact:**
- Callback bisa dikirim ulang / dipalsukan → order berstatus **PAID** tanpa pembayaran sah.
- Ini risiko finansial & fraud.

**Fix (recommended minimal safe design):**
1) Ubah notify endpoint menjadi **POST-only** untuk pemrosesan (GET boleh balas OK tanpa update).  
2) Saat menerima callback sukses, lakukan **server-to-server verification** ke endpoint **Check Transaction** sebelum update status ke PAID.  
3) Simpan `PaymentEvent.is_valid = false` sampai verifikasi selesai, dan gunakan idempotency berdasarkan `trx_id`.

---

### F-04 — Admin login tanpa rate limit + remember-me selalu aktif (P1)

**Evidence:**
- `routes/cafe_admin.php`: route POST login tidak memakai throttle middleware.
- `AdminAuthController.php` line 23: `Auth::attempt($data, true)` (remember selalu true).

**Impact:**
- Brute-force lebih mudah.
- Remember-me memperpanjang session di device yang tidak aman.

**Fix:**
- Tambah middleware `throttle` pada POST login (contoh `throttle:10,1`).
- Ubah `Auth::attempt($data, false)` atau buat checkbox “Remember me”.
- Opsional: 2FA untuk admin.

---

### F-05 — Order endpoints tidak memverifikasi order milik table session yang sama (P1)

**Evidence:**
- `EnsureTableContext` hanya cek session ada (tidak cek relasi order-table).
- `OrderController::show()` dan `statusJson()` tidak cek `order.table_id == session('cafe_table_id')`.

**Impact:**
- Potensi IDOR lintas meja (jika order_code bocor/tertebak atau terbagi).

**Fix:**
- Tambahkan guard di controller/policy:
  - deny bila `order->table_id !== session('cafe_table_id')`.
- Pastikan semua route order berada di middleware `cafe.table` (sudah), tapi tetap perlu *ownership check*.

---

### F-06 — Logging sensitif di iPaymu client (P1)

**Evidence:**
- `app/Services/Ipaymu/IpaymuClient.php` line 39 melakukan `Log::info(...)` berisi `va`, `jsonBody`, `signature`.

**Impact:**
- Log dapat berisi secret/PII dan memperbesar blast radius jika log terbaca.

**Fix:**
- Hapus log sensitif di production atau mask value.
- Pastikan channel log & permission aman.

---

### F-07 — `is_admin` ada di `$fillable` (P1)

**Evidence:**
- `app/Models/User.php` line 16.

**Impact:**
- Jika suatu saat ada endpoint create/update user (register/admin edit) yang mass assignment, ini bisa jadi privilege escalation.

**Fix:**
- Keluarkan `is_admin` dari `$fillable`; set hanya via seeder/command internal.

---

### F-08 — Tailwind via CDN (P2)

**Evidence:**
- Banyak blade memuat `https://cdn.tailwindcss.com` (contoh: admin layout, cafe layout, landing, order).

**Impact:**
- Perf lebih berat (no build optimizations), kontrol versi terbatas.

**Fix:**
- Pindah ke Tailwind build via Vite → output di `public/build` + cache headers.

---

### F-09 — Polling status order perlu throttle & cache-control (P2)

**Evidence:**
- `OrderController::statusJson()` sudah punya ETag dan 304 response (bagus), tapi endpoint tetap bisa dipanggil agresif.

**Fix:**
- Tambah `throttle` (misal 60 req/menit per IP) untuk route status.
- Tambah Cache-Control yang konsisten untuk response JSON.

---

## Hardening checklist (deploy/cPanel)

1) **Document Root** harus mengarah ke folder `public/`. Jangan expose root project.  
2) Block dotfiles & sensitive files (kalau docroot terpaksa di root):
   - deny `.env`, `cpanel.txt`, `composer.*`, `storage/`, `vendor/`, dll.
3) Set env production:
   - `APP_ENV=production`, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`, `SESSION_HTTP_ONLY=true`, `SESSION_SAME_SITE=lax/strict`.
4) Jalankan cache:
   - `php artisan config:cache`, `route:cache`, `view:cache`.
5) Monitoring:
   - rate-limit admin, audit log, backup.

---

## Extreme responsive QA (mobile-first)

Device target minimum:
- 360×640, 390×844, 414×896, 768×1024, 1366×768

Checklist:
- Tidak ada horizontal scroll (overflow-x).
- Button/tap target >= 44px.
- Cart/checkout CTA tidak ketutup keyboard.
- Modal/bottom-sheet: scroll area benar + body scroll lock.
- Navbar responsive: collapse rapi, aksesibilitas fokus & ESC close.
- CLS rendah: gambar punya ukuran, skeleton loader, font swap aman.

---

## Patch plan (step-by-step)

### Phase 0 — Emergency (hari ini)
- Revoke GitHub token & rotate DB password + audit akses.
- Hapus default credential display di admin login.
- Pastikan admin production password kuat + seeder default tidak dipakai di production.
- Pastikan document root benar (public/).

### Phase 1 — Payment integrity
- Webhook POST-only untuk proses.
- Implement check-transaction sebelum `PAID`.
- Simpan event is_valid=false sampai verified.
- Idempotency: ignore callback kalau order sudah PAID / trx_id sudah diproses.

### Phase 2 — Admin security
- Rate limit login.
- Nonaktifkan remember-me default.
- (Opsional) 2FA untuk admin.

### Phase 3 — Authorization correctness
- Ownership check order vs table session.
- Tambah policy/unit tests sederhana untuk akses order.

### Phase 4 — Performance & frontend build
- Tailwind via Vite build.
- Throttle polling endpoint status.
- Enable Laravel caches.

---

## References

- iPaymu Signature Documentation v2 (timestamp format + StringToSign): https://storage.googleapis.com/ipaymu-docs/ipaymu-api/iPaymu-signature-documentation-v2.pdf  
- iPaymu Public API v2 (Postman docs, termasuk “Callback Params” dan “Check Transaction”): https://documenter.getpostman.com/view/40296808/2sB3WtseBT  

