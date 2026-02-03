<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
  <div class="max-w-md mx-auto min-h-screen flex items-center justify-center p-4">
    <div class="w-full rounded-2xl bg-white border p-6">
      <h1 class="text-xl font-bold">Admin Login</h1>
      <p class="text-sm text-gray-600 mt-1">Masuk untuk atur order & menu.</p>

      @if(session('error'))
        <div class="mt-3 rounded-lg bg-red-100 p-3 text-sm">{{ session('error') }}</div>
      @endif

      <form class="mt-4 space-y-3" method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        <div>
          <label class="text-sm font-semibold">Email</label>
          <input class="mt-1 w-full rounded-lg border px-3 py-2" type="email" name="email" required>
        </div>
        <div>
          <label class="text-sm font-semibold">Password</label>
          <input class="mt-1 w-full rounded-lg border px-3 py-2" type="password" name="password" required>
        </div>
        <button class="w-full rounded-xl bg-gray-900 text-white py-3 font-semibold">Login</button>
      </form>

      <div class="mt-4 text-xs text-gray-500">
        Default (demo): admin@local.test / admin123
      </div>
    </div>
  </div>
</body>
</html>
