## Register ServiceProvider (WAJIB)

Tambahkan `App\Providers\CafeServiceProvider::class` ke daftar providers.

- Jika project kamu masih punya `config/app.php` dengan array `providers`, tambahkan di sana.
- Jika project Laravel 12 kamu memakai auto-discovery, kamu bisa juga register manual lewat `bootstrap/providers.php` jika ada.

Jika tidak mau pakai provider, kamu bisa hapus file ini dan biarkan Laravel auto-resolve via container (tetap jalan),
tapi provider ini membantu bind singleton service.
