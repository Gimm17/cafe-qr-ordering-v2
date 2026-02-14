<x-admin-layout title="Produk">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <p class="text-sm text-muted">Kelola menu produk cafe (aktif, habis, best seller).</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center gap-2 tap-44 px-5 py-3 bg-primary-600 text-white ui-btn hover:bg-primary-700 transition-colors font-semibold shadow-soft">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Produk
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($products as $product)
        <div class="ui-card-flat overflow-hidden hover:shadow-soft transition-shadow">
            <!-- Image -->
            <div class="relative aspect-square bg-primary-50/40">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif

                <!-- Badges -->
                <div class="absolute top-3 left-3 flex flex-col gap-2">
                    @if($product->is_best_seller)
                        <span class="ui-chip px-3 py-1 text-[11px] font-bold text-amber-700">⭐ Best</span>
                    @endif
                    @if($product->is_sold_out)
                        <span class="ui-chip px-3 py-1 text-[11px] font-bold text-red-700">Habis</span>
                    @endif
                    @if(!$product->is_active)
                        <span class="ui-chip px-3 py-1 text-[11px] font-bold text-gray-700">Nonaktif</span>
                    @endif
                </div>
            </div>

            <!-- Info -->
            <div class="p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <h3 class="font-semibold text-gray-900 truncate">{{ $product->name }}</h3>
                        <p class="text-xs text-muted">{{ $product->category->name ?? 'Tanpa Kategori' }}</p>
                    </div>
                    <span class="text-primary-700 font-extrabold text-sm whitespace-nowrap">{{ $product->price_rupiah }}</span>
                </div>

                <div class="flex gap-2 mt-4">
                    <a href="{{ route('admin.products.edit', $product) }}" class="flex-1 tap-44 py-2.5 text-center bg-white border border-line text-gray-800 ui-btn hover:bg-gray-50 transition-colors text-sm font-semibold">
                        Edit
                    </a>

                    <form id="bestseller-form-{{ $product->id }}" action="{{ route('admin.products.toggle-bestseller', $product) }}" method="POST" class="hidden">@csrf</form>
                    <button type="button" onclick="confirmToggleBestSeller('bestseller-form-{{ $product->id }}', '{{ $product->name }}', {{ $product->is_best_seller ? 'true' : 'false' }})"
                            class="tap-44 px-3 py-2.5 ui-btn {{ $product->is_best_seller ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-white text-gray-400 border border-line' }} hover:opacity-90 transition-opacity" title="Toggle Best Seller">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </button>

                    <form id="delete-product-{{ $product->id }}" action="{{ route('admin.products.delete', $product) }}" method="POST" class="hidden">@csrf</form>
                    <button type="button" onclick="confirmDelete('delete-product-{{ $product->id }}', 'produk {{ $product->name }}')"
                            class="tap-44 px-3 py-2.5 ui-btn bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 transition-colors" title="Hapus">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="ui-card p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-primary-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada produk</h3>
                <p class="text-muted mb-5">Mulai tambahkan menu cafe Anda.</p>
                <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 tap-44 px-5 py-3 bg-primary-600 text-white ui-btn hover:bg-primary-700 transition-colors font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Produk
                </a>
            </div>
        </div>
        @endforelse
    </div>
</x-admin-layout>
