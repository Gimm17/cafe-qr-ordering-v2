<x-admin-layout title="Modifiers">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <p class="text-sm text-muted">Kelola modifier (Size, Sugar, Ice, Add-ons) untuk produk.</p>
        <a href="{{ route('admin.modifiers.create') }}" class="inline-flex items-center gap-2 tap-44 px-5 py-3 bg-primary-600 text-white ui-btn hover:bg-primary-700 transition-colors font-semibold shadow-soft">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Modifier Group
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($modGroups as $modGroup)
        <div class="ui-card-flat overflow-hidden">
            <div class="p-4 border-b ui-divider flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <h3 class="font-semibold text-gray-900 truncate">{{ $modGroup->name }}</h3>
                    <p class="text-xs text-muted mt-0.5">
                        {{ $modGroup->type === 'SINGLE' ? 'Pilih Satu' : 'Pilih Banyak' }}
                        @if($modGroup->is_required)
                            • <span class="text-red-700 font-semibold">Wajib</span>
                        @endif
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="ui-chip px-3 py-1 text-[11px] font-bold {{ $modGroup->is_active ? 'text-green-700' : 'text-gray-600' }}">
                        {{ $modGroup->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <a href="{{ route('admin.modifiers.edit', $modGroup) }}" class="p-2 rounded-xl hover:bg-gray-50 text-gray-400 hover:text-primary-700 transition-colors" title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                </div>
            </div>

            <div class="divide-y ui-divider">
                @forelse($modGroup->options as $option)
                <div class="px-4 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-2 h-2 rounded-full {{ $option->is_active ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                        <span class="text-gray-800 truncate">{{ $option->name }}</span>
                    </div>
                    @if($option->price_modifier != 0)
                    <span class="text-sm font-bold {{ $option->price_modifier > 0 ? 'text-primary-700' : 'text-green-700' }} whitespace-nowrap">
                        {{ $option->formatted_price_modifier }}
                    </span>
                    @endif
                </div>
                @empty
                <div class="px-4 py-6 text-center text-muted text-sm">Belum ada opsi</div>
                @endforelse
            </div>

            <div class="p-4 bg-primary-50/50 border-t ui-divider flex items-center justify-between">
                <span class="text-xs text-muted">{{ $modGroup->options->count() }} opsi</span>
                <form id="delete-modifier-{{ $modGroup->id }}" action="{{ route('admin.modifiers.delete', $modGroup) }}" method="POST" class="hidden">@csrf</form>
                <button type="button" onclick="confirmDelete('delete-modifier-{{ $modGroup->id }}', 'modifier {{ $modGroup->name }}')" class="text-xs font-bold text-red-700 hover:text-red-900">Hapus</button>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="ui-card p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-primary-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada modifier</h3>
                <p class="text-muted mb-5">Buat modifier group untuk menambahkan opsi pada produk.</p>
                <a href="{{ route('admin.modifiers.create') }}" class="inline-flex items-center gap-2 tap-44 px-5 py-3 bg-primary-600 text-white ui-btn hover:bg-primary-700 transition-colors font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Modifier Group
                </a>
            </div>
        </div>
        @endforelse
    </div>
</x-admin-layout>
