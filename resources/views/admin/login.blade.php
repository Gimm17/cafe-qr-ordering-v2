<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login</title>

  @include('partials.brand-head')

  <style>
    body{ background: var(--bone); color: var(--ink); }
    .ui-card{ background:#fff; border:1px solid var(--line); border-radius:22px; box-shadow:0 18px 50px rgba(4,3,96,.10); }
    .ui-focus:focus{ outline:none; box-shadow:0 0 0 4px rgba(35,74,230,.16); border-color:rgba(35,74,230,.45); }
    .tap-44{ min-height:44px; }
  </style>
</head>
<body>
  <div class="max-w-md mx-auto min-h-screen flex items-center justify-center p-4">
    <div class="w-full ui-card p-6">
      <div class="mb-6">
        <div class="flex items-center gap-3">
          <img src="/assets/brand/logo.webp" alt="Logo" class="w-11 h-11 rounded-2xl ring-1 ring-[color:var(--line)]" loading="lazy">
          <div class="leading-tight">
            <h1 class="text-xl font-extrabold tracking-tight">Admin Login</h1>
            <p class="text-xs text-muted">Cafe QR Ordering</p>
          </div>
        </div>
      </div>

      @if(session('error'))
        <div class="mb-4 rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">{{ session('error') }}</div>
      @endif

      <form class="space-y-3" method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        <div>
          <label class="text-sm font-semibold">Email</label>
          <input class="mt-1 w-full tap-44 rounded-2xl border border-[color:var(--line)] px-4 py-3 bg-white ui-focus" type="email" name="email" required autocomplete="username">
        </div>
        <div>
          <label class="text-sm font-semibold">Password</label>
          <input class="mt-1 w-full tap-44 rounded-2xl border border-[color:var(--line)] px-4 py-3 bg-white ui-focus" type="password" name="password" required autocomplete="current-password">
        </div>

        <button type="submit" class="w-full tap-44 ui-btn bg-primary-600 hover:bg-primary-700 text-white py-3 font-semibold transition-colors">
          Login
        </button>
      </form>

      <div class="mt-5 text-xs text-muted">
        Tip: gunakan akun admin yang sudah kamu set di database / seeder.
      </div>
    </div>
  </div>
</body>
</html>
