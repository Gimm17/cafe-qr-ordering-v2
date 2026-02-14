<x-admin-layout :title="$title">
    <a href="{{ route('admin.modifiers') }}" class="inline-flex items-center text-sm text-muted hover:text-ink mb-6 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Modifiers
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Modifier Group Form -->
        <div class="ui-card p-6">
            <h3 class="font-semibold tracking-tight text-gray-900 mb-4">{{ $modGroup ? 'Edit' : 'Buat' }} Modifier Group</h3>

            <form action="{{ $modGroup ? route('admin.modifiers.update', $modGroup) : route('admin.modifiers.store') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Nama Group</label>
                        <input type="text" name="name" value="{{ old('name', $modGroup->name ?? '') }}" placeholder="Contoh: Size, Sugar Level" required
                               class="w-full tap-44 px-4 py-3 rounded-2xl border border-line bg-white ui-focus">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Tipe Pilihan</label>
                        <select name="type" class="w-full tap-44 px-4 py-3 rounded-2xl border border-line bg-white ui-focus">
                            <option value="SINGLE" {{ old('type', $modGroup->type ?? '') === 'SINGLE' ? 'selected' : '' }}>Pilih Satu (Radio)</option>
                            <option value="MULTIPLE" {{ old('type', $modGroup->type ?? '') === 'MULTIPLE' ? 'selected' : '' }}>Pilih Banyak (Checkbox)</option>
                        </select>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center gap-2 cursor-pointer ui-chip px-3 py-2">
                            <input type="checkbox" name="is_required" value="1" {{ old('is_required', $modGroup->is_required ?? false) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary-600">
                            <span class="text-sm text-gray-800">Wajib dipilih</span>
                        </label>

                        @if($modGroup)
                        <label class="flex items-center gap-2 cursor-pointer ui-chip px-3 py-2">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $modGroup->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary-600">
                            <span class="text-sm text-gray-800">Aktif</span>
                        </label>
                        @endif
                    </div>
                </div>

                <button type="submit" class="mt-6 w-full tap-44 py-4 bg-primary-600 text-white font-semibold ui-btn hover:bg-primary-700 transition-colors shadow-soft">
                    {{ $modGroup ? 'Simpan Perubahan' : 'Buat Modifier Group' }}
                </button>
            </form>
        </div>

        <!-- Options List (Only show when editing) -->
        @if($modGroup)
        <div class="ui-card p-6">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div>
                    <h3 class="font-semibold tracking-tight text-gray-900">Opsi: {{ $modGroup->name }}</h3>
                    <p class="text-xs text-muted">Tambahkan opsi pilihan untuk pelanggan.</p>
                </div>
                <span class="ui-chip px-3 py-1 text-xs text-muted">{{ $modGroup->options->count() }} opsi</span>
            </div>

            <!-- Add Option Form -->
            <form action="{{ route('admin.modifiers.options.store', $modGroup) }}" method="POST" class="mb-6 ui-card-flat p-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-2">
                    <input type="text" name="name" placeholder="Nama opsi (contoh: Small)" required
                           class="tap-44 px-4 py-3 rounded-2xl border border-line bg-white ui-focus">
                    <input type="number" name="price_modifier" placeholder="Selisih harga" value="0" required
                           class="tap-44 px-4 py-3 rounded-2xl border border-line bg-white ui-focus">
                </div>
                <p class="text-xs text-muted mb-3">Selisih harga: positif untuk tambahan, negatif untuk diskon.</p>
                <button type="submit" class="w-full tap-44 py-3 bg-gray-900 text-white font-semibold ui-btn hover:bg-black transition-colors">
                    + Tambah Opsi
                </button>
            </form>

            <!-- Options List -->
            <div class="space-y-2">
                @forelse($modGroup->options as $option)
                <div class="flex items-center justify-between gap-3 p-3 ui-card-flat">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-2 h-2 rounded-full {{ $option->is_active ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800 truncate">{{ $option->name }}</p>
                            @if($option->price_modifier != 0)
                                <p class="text-xs {{ $option->price_modifier > 0 ? 'text-primary-700' : 'text-green-700' }} font-bold">{{ $option->formatted_price_modifier }}</p>
                            @else
                                <p class="text-xs text-muted">No price change</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('admin.modifiers.options.update', $option) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="name" value="{{ $option->name }}">
                            <input type="hidden" name="price_modifier" value="{{ $option->price_modifier }}">
                            <input type="hidden" name="is_active" value="{{ $option->is_active ? '0' : '1' }}">
                            <button type="submit" class="tap-44 text-xs px-3 py-2 rounded-xl border {{ $option->is_active ? 'bg-white border-line text-gray-700' : 'bg-green-50 border-green-200 text-green-700' }} font-bold hover:opacity-90 transition-opacity">
                                {{ $option->is_active ? 'Nonaktif' : 'Aktif' }}
                            </button>
                        </form>

                        <form id="delete-option-{{ $option->id }}" action="{{ route('admin.modifiers.options.delete', $option) }}" method="POST" class="hidden">@csrf</form>
                        <button type="button" onclick="confirmDelete('delete-option-{{ $option->id }}', 'opsi {{ $option->name }}')" class="tap-44 p-2 rounded-xl hover:bg-red-50 text-gray-400 hover:text-red-600 transition-colors" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-muted">
                    <p class="text-sm">Belum ada opsi. Tambahkan di atas.</p>
                </div>
                @endforelse
            </div>
        </div>
        @endif
    </div>
</x-admin-layout>
