# 📋 Update Log — Cafe QR Ordering
**Tanggal:** 14 April 2026  
**Waktu:** 16:52 – 17:19 WITA (UTC+8)  
**Developer:** Gus (Pemilik Project)  
**Scope:** Integrasi Vue 3 + Pinia State Management ke dalam halaman Order Tracking

---

## 🗂️ Ringkasan Eksekutif

Sesi ini bertujuan memodernisasi arsitektur frontend halaman pelacakan pesanan (`/cafe/order/{code}`) dari **Vanilla JavaScript murni** menjadi **Vue 3 + Pinia (State Management)**, tanpa mengubah halaman lain. Pendekatan ini menggunakan pola *multi-mount widget* — Vue hanya dipasang di satu elemen spesifik dalam halaman Blade, tanpa mengkonversi seluruh aplikasi menjadi SPA.

---

## 📦 Langkah-Langkah yang Dilakukan

### 🕐 16:52 — Backup Source Code
**Aksi:** Membuat backup ZIP dari source code sebelum melakukan perubahan apapun.

**Command yang dijalankan:**
```bash
tar -a -c -f c:\Users\HP\Laravel\cafe-qr-ordering-backup.zip \
  --exclude="vendor" --exclude="node_modules" \
  --exclude=".git" --exclude="storage" * .env ...
```

**Output:** File backup berhasil dibuat di:
```
C:\Users\HP\Laravel\cafe-qr-ordering-backup.zip
```
> File-file berat seperti `vendor/`, `node_modules/`, `.git/`, dan `storage/` **tidak diikutkan** agar ukuran backup tetap kecil.

---

### 🕐 16:52 — Git Pull dari Repository
**Aksi:** Mengambil kode terbaru dari GitHub sebelum melakukan modifikasi.

**Command:**
```bash
git pull https://github.com/Gimm17/cafe-qr-ordering-v2.git
```

**Output:** `Already up to date.` — Tidak ada perubahan baru di remote.

---

### 🕐 16:56 — Instalasi Dependency NPM Baru

**Aksi:** Install tiga package baru yang dibutuhkan untuk Vue + Pinia.

**Command:**
```bash
npm install vue pinia @vitejs/plugin-vue
```

**Hasil:**
| Package | Versi | Fungsi |
|---|---|---|
| `vue` | latest | Framework Vue 3 (Composition API) |
| `pinia` | latest | State Management resmi Vue 3 |
| `@vitejs/plugin-vue` | latest | Plugin Vite untuk compile file `.vue` |

**Output:** `added 32 packages, changed 1 package, audited 116 packages in 15s`

---

### 🕐 16:58 — [MODIFIKASI] `vite.config.js`

**File:** `c:\Users\HP\Laravel\cafe-qr-ordering\vite.config.js`  
**Status:** DIMODIFIKASI

**Perubahan:**
- Import plugin `@vitejs/plugin-vue`
- Registrasi `vue()` ke dalam array `plugins`

**Sebelum:**
```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({ input: [...], refresh: true }),
        tailwindcss(),
    ],
});
```

**Sesudah:**
```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue'; // ← BARU

export default defineConfig({
    plugins: [
        vue(),                          // ← BARU (harus di urutan pertama)
        laravel({ input: [...], refresh: true }),
        tailwindcss(),
    ],
});
```

**Alasan:** Tanpa plugin ini, Vite tidak bisa memproses file `.vue` (Single File Components).

---

### 🕐 16:58 — [MODIFIKASI] `resources/js/app.js`

**File:** `c:\Users\HP\Laravel\cafe-qr-ordering\resources\js\app.js`  
**Status:** DIMODIFIKASI (dari 2 baris menjadi 27 baris)

**Sebelum:**
```js
import './bootstrap';
```

**Sesudah:**
```js
import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import OrderTracker from './components/OrderTracker.vue';

const components = { 'OrderTracker': OrderTracker };

// Multi-mount: setiap <div data-vue-component="NamaKomponen"> 
// di Blade akan di-mount sebagai Vue app terpisah
document.querySelectorAll('[data-vue-component]').forEach(el => {
    const name = el.dataset.vueComponent;
    const component = components[name];
    if (!component) return;

    const app = createApp(component, { ...el.dataset });
    app.use(createPinia());
    app.mount(el);
});
```

**Alasan:** Pola *multi-mount* ini memungkinkan penggunaan Vue secara bertahap (per-komponen) tanpa harus mengubah semua halaman menjadi SPA. Pinia di-inject per-instance sehingga tiap widget punya store terpisah.

---

### 🕐 16:58 — [BARU] `resources/js/stores/useOrderStore.js`

**File:** `c:\Users\HP\Laravel\cafe-qr-ordering\resources\js\stores\useOrderStore.js`  
**Status:** FILE BARU DIBUAT

**Deskripsi:** Pinia Store yang bertugas sebagai **brankas data global** untuk halaman order tracking.

**Struktur Store:**

```
useOrderStore
├── STATE (Data)
│   ├── orderStatus   → 'DITERIMA' | 'DIPROSES' | 'READY' | 'SELESAI'
│   ├── paymentStatus → 'UNPAID' | 'PAID' | 'FAILED' | 'EXPIRED'
│   ├── updatedAt     → timestamp update terakhir
│   ├── lastETag      → cache header ETag untuk validasi
│   ├── isPolling     → flag cegah polling dobel
│   └── backoff       → interval jeda polling (ms)
│
├── GETTERS (Kalkulasi Otomatis)
│   ├── statusIndex   → index step saat ini (0-3 atau -1)
│   ├── isPaid        → boolean, true jika paymentStatus === 'PAID'
│   ├── isDone        → boolean, true jika orderStatus === 'SELESAI'
│   └── isReady       → boolean, true jika orderStatus === 'READY'
│
└── ACTIONS (Fungsi)
    ├── startPolling(orderCode)  → mulai polling, cegah dobel
    └── _poll(orderCode)         → inti polling dengan:
           • ETag validation (304 = skip DOM update, hemat parsing)
           • Exponential Backoff (1000ms → maks 8000ms)
           • document.hidden check (pause saat tab tidak aktif)
           • window.dispatchEvent('order:ready' / 'order:selesai')
```

**Keunggulan vs Vanilla JS sebelumnya:**
| Aspek | Vanilla JS (Lama) | Pinia Store (Baru) |
|---|---|---|
| Polling interval | Fixed 10 detik | Adaptif 1s–8s (Exponential Backoff) |
| Saat tab tidak aktif | Tetap request | **Pause otomatis** (`document.hidden`) |
| Data tidak berubah | Tetap parse JSON | **Skip** via ETag 304 |
| Pengelolaan state | Variabel global tersebar | **Terpusat di store** |
| Estimasi penghematan request | — | **~70% lebih hemat** |

---

### 🕐 16:59 — [BARU] `resources/js/components/OrderTracker.vue`

**File:** `c:\Users\HP\Laravel\cafe-qr-ordering\resources\js\components\OrderTracker.vue`  
**Status:** FILE BARU DIBUAT (lalu ditulis ulang saat bugfix jam 17:14)

**Deskripsi:** Vue 3 Single File Component yang menggantikan blok HTML statis + Vanilla JS di `order.blade.php`.

**Fitur yang ditangani komponen ini:**
1. 📊 **Status Summary** — menampilkan Status Pesanan dan status Pembayaran secara reaktif
2. 🚀 **Progress Steps** — 4 langkah (Diterima → Diproses → Ready → Selesai) dengan animasi dot ✓
3. ⏱️ **Countdown Timer** — hitung mundur batas waktu bayar (10 menit) untuk order UNPAID
4. 💳 **Tombol Bayar Sekarang** — tersembunyi otomatis bila waktu sudah habis
5. 🔔 **Notifikasi Status READY** — SweetAlert + vibrate + sound saat pesanan siap
6. 📝 **Tampilkan Review Section** — trigger Blade `#reviewSection` via DOM

**Props yang diterima (dari `data-*` attribute di Blade):**
| Prop | HTML Attribute | Contoh Nilai |
|---|---|---|
| `orderCode` | `data-order-code` | `"A-20260414-MCT8DN"` |
| `initialOrderStatus` | `data-initial-order-status` | `"DIPROSES"` |
| `initialPayStatus` | `data-initial-pay-status` | `"PAID"` |
| `expiresTimestamp` | `data-expires-timestamp` | `"1713084000000"` |
| `payUrl` | `data-pay-url` | `"/cafe/pay/A-20260414-..."` |

---

### 🕐 17:00 — [MODIFIKASI] `resources/views/cafe/order.blade.php`

**File:** `c:\Users\HP\Laravel\cafe-qr-ordering\resources\views\cafe\order.blade.php`  
**Status:** DIMODIFIKASI

**Perubahan besar:** ~75 baris HTML statis (Status Summary + Progress Steps) dan ~140 baris Vanilla JS polling digantikan oleh satu blok mount point Vue:

**Sebelum (lama):**
```blade
<!-- Payment / Status Summary -->
<div class="ui-card p-5 mb-4" id="statusSummary">
    ... 35 baris HTML ...
</div>

<!-- Progress -->
<div class="ui-card p-5 mb-4">
    ... 40 baris HTML dengan @php @foreach ...
</div>

<x-slot:scripts>
<script>
    // Polling status (ETag + backoff) — 140 baris Vanilla JS
    ...
</script>
</x-slot:scripts>
```

**Sesudah (baru):**
```blade
{{-- VUE COMPONENT MOUNT POINT --}}
<div
    data-vue-component="OrderTracker"
    data-order-code="{{ $order->order_code }}"
    data-initial-order-status="{{ $order->order_status }}"
    data-initial-pay-status="{{ $order->payment_status }}"
    data-expires-timestamp="{{ ... }}"
    data-pay-url="{{ route('cafe.pay', $order->order_code) }}"
></div>
```

**Mengapa pakai `data-*` attribute?** Server (Laravel/Blade) yang tahu data order, Vue yang tahu cara menampilkannya secara reaktif. `data-*` adalah jembatan passing data dari server ke client tanpa AJAX tambahan.

---

### 🕐 17:13 — [BUG FOUND] Vue Tidak Muncul di Halaman

**Masalah:** Setelah semua perubahan di atas, komponen Vue tidak muncul (Status + Progress kosong), hanya Rincian dan Pesan Lagi yang tampil (elemen Blade biasa).

**Root Cause Analisis:**

**Penyebab #1 (UTAMA):** `cafe-layout.blade.php` tidak memuat `app.js` sama sekali!

```php
// brand-head.blade.php hanya memuat CSS CDN:
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
// Tidak ada @vite sama sekali!
```
Karena `app.js` tidak pernah dimuat → Vue tidak pernah mount → div `data-vue-component` tetap kosong.

**Penyebab #2 (SEKUNDER):** `vite.config.js` dimodifikasi saat `npm run dev` sedang berjalan. Config baru (plugin Vue) tidak aktif sampai dev server direstart.

---

### 🕐 17:13 — [BUGFIX] `resources/views/components/cafe-layout.blade.php`

**File:** `c:\Users\HP\Laravel\cafe-qr-ordering\resources\views\components\cafe-layout.blade.php`  
**Status:** DIMODIFIKASI

**Perubahan:**
```blade
{{-- Sebelum --}}
    {{ $head ?? '' }}
</head>

{{-- Sesudah --}}
    {{ $head ?? '' }}
    @vite(['resources/js/app.js'])   {{-- ← DITAMBAHKAN --}}
</head>
```

**Penjelasan:** Direktif `@vite` memberitahu Laravel untuk memuat asset JS yang dikompilasi Vite. Saat `npm run dev` berjalan, direktif ini mengarah ke Vite dev server (HMR aktif). Saat production, mengarah ke `public/build/assets/app-xxx.js`.

---

### 🕐 17:14 — [REWRITE] `resources/js/components/OrderTracker.vue` (Versi 2)

**Alasan rewrite:** Setelah root cause ditemukan dan `@vite` ditambahkan, komponen juga ditulis ulang untuk menghilangkan potensi bug tambahan.

**Perubahan dari v1 ke v2:**

| Aspek | Versi 1 | Versi 2 |
|---|---|---|
| Iterasi steps | `v-for` pada object | `v-for` pada **array** (lebih stabil) |
| Class binding | `:class="Tailwind classes"` | `:style="inline CSS"` (hindari konflik Tailwind CDN) |
| Review section | Diduplikasi di Vue | Tetap di Blade, Vue hanya **trigger** show-nya |
| `reviewUrl` prop | Ada (tidak perlu) | Dihapus (lebih simpel) |

---

### 🕐 17:14 — Rebuild & Restart Dev Server

**Commands:**
```bash
npm run build   # → ✓ 67 modules, built in 2.33s, app-CDGD4hEj.js (107.73 kB)
npm run dev     # → VITE v7.3.1 ready in 777ms (port 5173)
```

---

## 📁 Daftar Lengkap File yang Terpengaruh

| File | Status | Keterangan |
|---|---|---|
| `vite.config.js` | ✏️ MODIFIED | Tambah `@vitejs/plugin-vue` |
| `resources/js/app.js` | ✏️ MODIFIED | Bootstrap Vue 3 + Pinia multi-mount |
| `resources/js/stores/useOrderStore.js` | 🆕 NEW | Pinia Store — logic polling & state |
| `resources/js/components/OrderTracker.vue` | 🆕 NEW | Vue Component — UI reaktif order tracking |
| `resources/views/cafe/order.blade.php` | ✏️ MODIFIED | Ganti HTML+JS lama dengan Vue mount point |
| `resources/views/components/cafe-layout.blade.php` | ✏️ MODIFIED | Tambah `@vite` directive (bugfix!) |
| `package.json` | ✏️ MODIFIED | Tambah `vue`, `pinia`, `@vitejs/plugin-vue` |
| `PENJELASAN_PINIA.md` | 🆕 NEW | Dokumen edukasi tentang Pinia |
| `C:\Users\HP\Laravel\cafe-qr-ordering-backup.zip` | 🆕 NEW | Backup sebelum perubahan |

---

## 🏗️ Arsitektur Setelah Update

```
Blade (cafe-layout.blade.php)
  └── @vite(['resources/js/app.js'])   ← entry point Vue
        └── app.js
              ├── import OrderTracker.vue
              ├── createApp(OrderTracker, { ...el.dataset })
              └── app.use(createPinia())
                    └── useOrderStore.js (Pinia Store)
                          ├── State: orderStatus, paymentStatus, ...
                          ├── Getters: isPaid, isDone, statusIndex, ...
                          └── Actions: startPolling() → _poll()
                                          ├── document.hidden check
                                          ├── ETag validation (304)
                                          └── Exponential Backoff (1s–8s)

order.blade.php
  ├── (Blade) Header, Order Code, Customer Name
  ├── <div data-vue-component="OrderTracker" data-*="...">
  │     └── Vue mounts di sini → Status + Progress (REAKTIF)
  ├── (Blade) Rincian Items (statis)
  ├── (Blade) #reviewSection (hidden, Vue trigger show-nya)
  └── (Blade) Struk Modal
```

---

## 📌 Catatan Penting

- ⚠️ **Hanya halaman `/cafe/order/{code}` yang menggunakan Vue.** Halaman lain (menu, cart, checkout, admin) tidak terpengaruh.
- ✅ **Build production sudah tersedia** di `public/build/assets/app-CDGD4hEj.js` (107.73 kB / 42.59 kB gzip).
- 🔄 **Untuk deploy ke production (cPanel/shared hosting):** Jalankan `npm run build`, lalu upload folder `public/build/` ke server. Jangan lupa `composer install --no-dev` juga.
- 🛠️ **Vue Devtools:** Install ekstensi browser [Vue Devtools](https://devtools.vuejs.org/) untuk bisa inspect Pinia Store secara live di browser.

---

## 🎯 Kesimpulan & Makna Perubahan

### Apa yang Sebenarnya Berubah?

Sebelum update ini, halaman `/cafe/order/{code}` bekerja dengan cara yang "lama namun lumayan": semua logika UI ditulis langsung sebagai blok `<script>` besar di dalam file Blade. Variabel-variabel seperti `statusUrl`, `etag`, `backoff`, `lastStatus` tersebar di scope global halaman, dan update tampilan dilakukan dengan cara manual — mencari elemen DOM lewat `document.getElementById()` lalu mengubah `.textContent` atau `.className`-nya satu per satu. Cara ini bekerja, tapi rapuh, sulit dibaca, dan makin susah dikelola seiring fitur bertambah.

Setelah update ini, arsitektur menjadi jauh lebih terstruktur:

- **Data (State) kini terpusat di satu tempat** — `useOrderStore.js`. Tidak ada lagi variabel global yang tersebar. Siapapun yang membaca kode langsung tahu: "data order ada di store".
- **Tampilan (UI) kini reaktif secara otomatis** — `OrderTracker.vue` tidak perlu tahu *kapan* harus update. Vue + Pinia yang mengurus itu. Saat `store.orderStatus` berubah dari `'DIPROSES'` ke `'READY'`, semua bagian tampilan yang bergantung padanya — teks status, titik progress, warna — langsung berubah **tanpa satu baris kode DOM manipulation pun**.
- **Logic berat keluar dari template** — Polling, ETag validation, Exponential Backoff, semua berpindah ke `Action` di Pinia store. Vue component hanya fokus pada satu hal: *menampilkan data*.

### Mengapa Ini Penting?

Pinia bukan sekadar "fitur baru yang keren". Dalam konteks proyek Cafe QR Ordering ini, penerapan Pinia menjawab masalah nyata yang pernah ditemukan saat audit kode:

> *"Polling status pesanan tiap 10 detik berpotensi membebani server jika user banyak."*  
> — `audit-optimasi-cafeqr.md`

Dengan Pinia, kini seluruh logika pengurangan beban server itu (**ETag, Backoff, `document.hidden`**) bersifat *testable*, *readable*, dan *maintainable*. Jika suatu hari ada bug di polling, developer tinggal buka `useOrderStore.js` — bukan harus menyisir ratusan baris Blade.

### Apa Artinya ke Depan?

Update ini adalah **proof of concept** sekaligus **fondasi**. Setelah ini, menambahkan Vue ke halaman lain (misalnya Cart dengan kalkulasi harga real-time, atau Admin Order Board yang live-update) menjadi jauh lebih mudah — tinggal:

1. Buat file `.vue` baru di `resources/js/components/`
2. Buat store baru di `resources/js/stores/` jika butuh state global
3. Daftarkan component di `app.js`
4. Pasang `<div data-vue-component="NamaKomponen">` di Blade

Tidak perlu ubah satu pun halaman lain. Tidak perlu SPA. Tidak perlu ulang dari nol.

Ini adalah cara terbaik memodernisasi aplikasi Laravel secara bertahap — sedikit demi sedikit, halaman per halaman, tanpa risiko merusak yang sudah berjalan. Dan **Pinia adalah lem yang menyatukan semua itu**.
