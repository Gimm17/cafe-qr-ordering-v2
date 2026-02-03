<x-admin-layout>
  <h1 class="text-xl font-bold mb-4">Menu</h1>
  <div class="flex gap-2">
    <a class="px-4 py-2 rounded bg-gray-900 text-white" href="{{ route('admin.categories') }}">Kategori</a>
    <a class="px-4 py-2 rounded bg-gray-900 text-white" href="{{ route('admin.products') }}">Produk</a>
  </div>
</x-admin-layout>
