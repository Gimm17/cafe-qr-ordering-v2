<x-admin-layout>
  <h1 class="text-xl font-bold mb-4">Kategori</h1>

  <form class="rounded-xl bg-white border p-4 mb-4" method="POST" action="{{ route('admin.categories.store') }}">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <input class="rounded border px-3 py-2" name="name" placeholder="Nama kategori" required>
      <input class="rounded border px-3 py-2" type="number" name="sort_order" placeholder="Sort (0..)" value="0">
      <button class="rounded bg-gray-900 text-white px-4 py-2 font-semibold">Tambah</button>
    </div>
  </form>

  <div class="rounded-xl bg-white border p-4">
    <table class="w-full text-sm">
      <thead class="text-gray-500">
        <tr><th class="text-left py-2">Nama</th><th class="text-left py-2">Sort</th><th class="text-left py-2">Aksi</th></tr>
      </thead>
      <tbody>
        @foreach($categories as $c)
          <tr class="border-t">
            <td class="py-2">{{ $c->name }}</td>
            <td class="py-2">{{ $c->sort_order }}</td>
            <td class="py-2">
              <form id="delete-category-{{ $c->id }}" method="POST" action="{{ route('admin.categories.delete', $c) }}" class="hidden">@csrf</form>
              <button type="button" onclick="confirmDelete('delete-category-{{ $c->id }}', '{{ $c->name }}')" class="text-red-600 hover:text-red-800">Delete</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</x-admin-layout>
