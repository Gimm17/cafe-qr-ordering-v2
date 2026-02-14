<x-admin-layout title="Menu">
  <div class="ui-card p-6">
    <h1 class="text-2xl font-extrabold tracking-tight mb-2">Menu</h1>
    <p class="text-sm text-muted mb-6">Kelola kategori dan produk yang tampil di halaman cafe.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
      <a class="tap-44 px-5 py-4 rounded-2xl border border-line bg-white hover:bg-gray-50 transition-colors flex items-center justify-between" href="{{ route('admin.categories') }}">
        <div>
          <p class="font-semibold text-gray-900">Kategori</p>
          <p class="text-xs text-muted">Buat & atur urutan</p>
        </div>
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>

      <a class="tap-44 px-5 py-4 rounded-2xl border border-line bg-white hover:bg-gray-50 transition-colors flex items-center justify-between" href="{{ route('admin.products') }}">
        <div>
          <p class="font-semibold text-gray-900">Produk</p>
          <p class="text-xs text-muted">Tambah, edit, best seller</p>
        </div>
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>
  </div>
</x-admin-layout>
