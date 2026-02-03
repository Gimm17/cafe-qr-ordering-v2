## Catatan instalasi middleware (WAJIB)

Tambahkan middleware ini ke `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
  // ...
  'admin' => \App\Http\Middleware\EnsureAdmin::class,
  'cafe.table' => \App\Http\Middleware\EnsureTableContext::class,
];
```

Laravel 12 memakai bootstrap/app.php untuk middleware alias, jadi jika file Kernel tidak ada,
gunakan cara Laravel 11/12:

Buka `bootstrap/app.php`, cari `->withMiddleware(function (Middleware $middleware) { ... })`
lalu tambahkan:

```php
$middleware->alias([
  'admin' => \App\Http\Middleware\EnsureAdmin::class,
  'cafe.table' => \App\Http\Middleware\EnsureTableContext::class,
]);
```
