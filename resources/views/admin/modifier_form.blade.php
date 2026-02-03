<x-admin-layout :title="$title">
    <!-- Back Button -->
    <a href="{{ route('admin.modifiers') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-6 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Modifier Group Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">{{ $modGroup ? 'Edit' : 'Buat' }} Modifier Group</h3>
            
            <form action="{{ $modGroup ? route('admin.modifiers.update', $modGroup) : route('admin.modifiers.store') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Group</label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $modGroup->name ?? '') }}"
                               placeholder="Contoh: Size, Sugar Level, Ice Level"
                               required
                               class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Pilihan</label>
                        <select name="type" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                            <option value="SINGLE" {{ old('type', $modGroup->type ?? '') === 'SINGLE' ? 'selected' : '' }}>Pilih Satu (Radio)</option>
                            <option value="MULTIPLE" {{ old('type', $modGroup->type ?? '') === 'MULTIPLE' ? 'selected' : '' }}>Pilih Banyak (Checkbox)</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_required" value="1" {{ old('is_required', $modGroup->is_required ?? false) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-gray-700">Wajib dipilih</span>
                        </label>
                        
                        @if($modGroup)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $modGroup->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-gray-700">Aktif</span>
                        </label>
                        @endif
                    </div>
                </div>

                <button type="submit" class="mt-6 w-full py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors">
                    {{ $modGroup ? 'Simpan Perubahan' : 'Buat Modifier Group' }}
                </button>
            </form>
        </div>

        <!-- Options List (Only show when editing) -->
        @if($modGroup)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Opsi dalam {{ $modGroup->name }}</h3>

            <!-- Add Option Form -->
            <form action="{{ route('admin.modifiers.options.store', $modGroup) }}" method="POST" class="mb-6 p-4 bg-gray-50 rounded-xl">
                @csrf
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <input type="text" 
                           name="name" 
                           placeholder="Nama opsi (contoh: Small)"
                           required
                           class="px-4 py-2 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                    <input type="number" 
                           name="price_modifier" 
                           placeholder="Selisih harga"
                           value="0"
                           required
                           class="px-4 py-2 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                </div>
                <p class="text-xs text-gray-500 mb-3">Selisih harga: positif untuk tambahan, negatif untuk diskon</p>
                <button type="submit" class="w-full py-2 bg-gray-800 text-white font-medium rounded-lg hover:bg-gray-900 transition-colors">
                    + Tambah Opsi
                </button>
            </form>

            <!-- Options List -->
            <div class="space-y-2">
                @forelse($modGroup->options as $option)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full {{ $option->is_active ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                        <span class="font-medium text-gray-700">{{ $option->name }}</span>
                        @if($option->price_modifier != 0)
                        <span class="text-sm {{ $option->price_modifier > 0 ? 'text-primary-600' : 'text-green-600' }}">
                            {{ $option->formatted_price_modifier }}
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('admin.modifiers.options.update', $option) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="name" value="{{ $option->name }}">
                            <input type="hidden" name="price_modifier" value="{{ $option->price_modifier }}">
                            <input type="hidden" name="is_active" value="{{ $option->is_active ? '0' : '1' }}">
                            <button type="submit" class="text-xs px-2 py-1 rounded {{ $option->is_active ? 'bg-gray-200 text-gray-600' : 'bg-green-100 text-green-600' }} hover:opacity-80 transition-opacity">
                                {{ $option->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.modifiers.options.delete', $option) }}" method="POST" class="inline" onsubmit="return confirm('Hapus opsi ini?')">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <p>Belum ada opsi. Tambahkan opsi di atas.</p>
                </div>
                @endforelse
            </div>
        </div>
        @endif
    </div>
</x-admin-layout>
