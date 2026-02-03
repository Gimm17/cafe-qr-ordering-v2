<x-admin-layout title="Modifiers">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <p class="text-gray-600">Kelola modifier (Size, Sugar, Ice, Add-ons) untuk produk</p>
        <a href="{{ route('admin.modifiers.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Modifier Group
        </a>
    </div>

    <!-- Modifier Groups List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($modGroups as $modGroup)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800">{{ $modGroup->name }}</h3>
                    <p class="text-xs text-gray-500">
                        {{ $modGroup->type === 'SINGLE' ? 'Pilih Satu' : 'Pilih Banyak' }}
                        @if($modGroup->is_required) • <span class="text-red-500">Wajib</span> @endif
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-1 text-xs rounded-full font-medium {{ $modGroup->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $modGroup->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <a href="{{ route('admin.modifiers.edit', $modGroup) }}" class="p-2 text-gray-400 hover:text-primary-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Options -->
            <div class="divide-y divide-gray-50">
                @forelse($modGroup->options as $option)
                <div class="px-4 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full {{ $option->is_active ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                        <span class="text-gray-700">{{ $option->name }}</span>
                    </div>
                    @if($option->price_modifier != 0)
                    <span class="text-sm font-medium {{ $option->price_modifier > 0 ? 'text-primary-600' : 'text-green-600' }}">
                        {{ $option->formatted_price_modifier }}
                    </span>
                    @endif
                </div>
                @empty
                <div class="px-4 py-6 text-center text-gray-400 text-sm">
                    Belum ada opsi
                </div>
                @endforelse
            </div>

            <!-- Footer -->
            <div class="p-4 bg-gray-50 flex justify-between items-center">
                <span class="text-xs text-gray-500">{{ $modGroup->options->count() }} opsi</span>
                <form id="delete-modifier-{{ $modGroup->id }}" action="{{ route('admin.modifiers.delete', $modGroup) }}" method="POST" class="hidden">@csrf</form>
                <button type="button" onclick="confirmDelete('delete-modifier-{{ $modGroup->id }}', '{{ $modGroup->name }}')" class="text-xs text-red-500 hover:text-red-700 transition-colors">Hapus</button>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-2xl p-12 shadow-sm border border-gray-100 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Modifier</h3>
                <p class="text-gray-500 mb-4">Buat modifier group untuk menambahkan opsi pada produk</p>
                <a href="{{ route('admin.modifiers.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Modifier Group
                </a>
            </div>
        </div>
        @endforelse
    </div>
</x-admin-layout>
