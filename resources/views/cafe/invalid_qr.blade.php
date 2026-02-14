<x-cafe-layout title="QR Tidak Valid">
  <div class="max-w-lg mx-auto px-4">
    <div class="ui-card p-6">
      <div class="flex items-start gap-4">
        <div class="w-12 h-12 rounded-2xl bg-primary-50 flex items-center justify-center border border-line">
          <svg class="w-6 h-6 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div class="flex-1">
          <h1 class="text-lg font-semibold tracking-tight">QR tidak valid</h1>
          <p class="mt-2 text-sm text-muted">QR ini tidak ditemukan / sudah tidak aktif. Silakan minta bantuan kasir atau scan ulang QR di meja.</p>
          <div class="mt-5 flex gap-3">
            <a href="{{ route('home') }}" class="ui-btn tap-44 inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white font-semibold rounded-xl shadow-soft2">
              Kembali
            </a>
            <a href="{{ route('admin.login') }}" class="ui-btn tap-44 inline-flex items-center justify-center px-4 py-2.5 bg-white border border-line text-gray-800 font-semibold rounded-xl">
              Admin Login
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-cafe-layout>
