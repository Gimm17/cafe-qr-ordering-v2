<x-admin-layout title="Kategori">
    <div class="mb-6">
        <p class="text-sm text-muted">Kelola kategori menu untuk memudahkan pelanggan mencari produk.</p>
    </div>

    <!-- Add Category -->
    <div class="ui-card p-5 mb-6">
        <h2 class="font-semibold tracking-tight mb-4">Tambah kategori</h2>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            @csrf
            <input class="tap-44 rounded-2xl border border-line px-4 py-3 bg-white ui-focus" name="name" placeholder="Nama kategori" required>
            <input class="tap-44 rounded-2xl border border-line px-4 py-3 bg-white ui-focus" type="number" name="sort_order" placeholder="Sort (0.. )" value="0">
            <button class="tap-44 rounded-2xl bg-primary-600 hover:bg-primary-700 text-white px-5 py-3 font-semibold transition-colors">Tambah</button>
        </form>
    </div>

    <!-- List -->
    <div class="ui-card overflow-hidden">
        <div class="p-5 border-b ui-divider">
            <h2 class="font-semibold tracking-tight">Daftar kategori</h2>
        </div>

        <!-- Desktop table -->
        <div class="hidden md:block">
            <table class="w-full text-sm">
                <thead class="text-muted">
                    <tr>
                        <th class="text-left py-3 px-5">Nama</th>
                        <th class="text-left py-3 px-5">Sort</th>
                        <th class="text-right py-3 px-5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y ui-divider">
                    @foreach($categories as $c)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-5 font-semibold text-gray-900">{{ $c->name }}</td>
                            <td class="py-3 px-5 text-gray-700">{{ $c->sort_order }}</td>
                            <td class="py-3 px-5 text-right">
                                <form id="delete-category-{{ $c->id }}" method="POST" action="{{ route('admin.categories.delete', $c) }}" class="hidden">@csrf</form>
                                <button type="button" onclick="confirmDelete('delete-category-{{ $c->id }}', 'kategori {{ $c->name }}')" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-50 border border-red-200 text-red-700 font-semibold hover:bg-red-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile cards -->
        <div class="md:hidden divide-y ui-divider">
            @foreach($categories as $c)
                <div class="p-4 flex items-center justify-between gap-3">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $c->name }}</p>
                        <p class="text-xs text-muted">Sort: {{ $c->sort_order }}</p>
                    </div>
                    <form id="delete-category-m-{{ $c->id }}" method="POST" action="{{ route('admin.categories.delete', $c) }}" class="hidden">@csrf</form>
                    <button type="button" onclick="confirmDelete('delete-category-m-{{ $c->id }}', 'kategori {{ $c->name }}')" class="tap-44 px-3 py-2 rounded-xl bg-red-50 border border-red-200 text-red-700 font-bold">
                        Hapus
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</x-admin-layout>
