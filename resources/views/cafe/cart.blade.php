<x-cafe-layout :tableNo="$tableNo" title="Keranjang - Cafe Order">
    <div class="max-w-lg mx-auto px-4">
        <div class="flex items-end justify-between mb-5">
            <div>
                <h1 class="text-xl font-bold tracking-tight">Keranjang</h1>
                <p class="text-sm text-muted">Cek pesananmu sebelum checkout.</p>
            </div>
            <a href="{{ route('cafe.menu') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">Tambah item</a>
        </div>

        @if(empty($items))
            <!-- Empty Cart -->
            <div class="ui-card p-6 text-center">
                <div class="w-20 h-20 mx-auto mb-4 bg-primary-50 rounded-2xl flex items-center justify-center">
                    <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold">Keranjang kosong</h2>
                <p class="text-sm text-muted mt-1">Belum ada item. Yuk pilih menu favoritmu.</p>
                <a href="{{ route('cafe.menu') }}" class="mt-5 inline-flex items-center justify-center px-5 py-3 bg-primary-600 text-white font-semibold ui-btn hover:bg-primary-700 transition-colors tap-44">
                    Lihat Menu
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        @else
            <!-- Cart Items -->
            <div class="space-y-3 mb-5">
                @foreach($items as $item)
                <div class="ui-card-flat p-4">
                    <div class="flex gap-3">
                        <!-- Product Image -->
                        <div class="w-20 h-20 rounded-2xl bg-primary-50 flex-shrink-0 overflow-hidden ring-1 ring-line">
                            @if($item['image_url'] ?? false)
                                <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <h3 class="font-semibold text-gray-900 truncate">{{ $item['name'] }}</h3>
                                    @if(!empty($item['modifiers_summary']))
                                        <p class="text-xs text-muted mt-0.5">{{ $item['modifiers_summary'] }}</p>
                                    @endif
                                    @if(!empty($item['note']))
                                        <p class="text-xs text-amber-700 mt-0.5 italic">📝 {{ $item['note'] }}</p>
                                    @endif
                                </div>

                                <!-- Remove -->
                                <form id="remove-item-{{ $loop->index }}" action="{{ route('cafe.cart.remove') }}" method="POST" class="hidden">
                                    @csrf
                                    <input type="hidden" name="cart_key" value="{{ $item['cart_key'] }}">
                                </form>
                                <button type="button" onclick="confirmRemoveItem('remove-item-{{ $loop->index }}', '{{ $item['name'] }}')" class="p-2 rounded-xl hover:bg-red-50 text-gray-400 hover:text-red-600 transition-colors" aria-label="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <p class="text-sm font-semibold text-primary-800">{{ $item['formatted_unit_price'] }}</p>

                                <!-- Qty Controls -->
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('cafe.cart.update') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="cart_key" value="{{ $item['cart_key'] }}">
                                        <input type="hidden" name="qty" value="{{ $item['qty'] - 1 }}">
                                        <button type="submit" class="w-10 h-10 tap-44 rounded-xl bg-white border border-line hover:bg-gray-50 flex items-center justify-center transition-colors" aria-label="Kurangi">
                                            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                        </button>
                                    </form>
                                    <span class="w-8 text-center font-bold text-gray-900">{{ $item['qty'] }}</span>
                                    <form action="{{ route('cafe.cart.update') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="cart_key" value="{{ $item['cart_key'] }}">
                                        <input type="hidden" name="qty" value="{{ $item['qty'] + 1 }}">
                                        <button type="submit" class="w-10 h-10 tap-44 rounded-xl bg-white border border-line hover:bg-gray-50 flex items-center justify-center transition-colors" aria-label="Tambah">
                                            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t ui-divider flex justify-between items-center">
                        <span class="text-sm text-muted">Subtotal item</span>
                        <span class="font-bold text-gray-900">{{ $item['formatted_line_total'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div class="ui-card p-5 mb-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold tracking-tight">Ringkasan</h3>
                    <span class="ui-chip px-3 py-1 text-xs text-muted">{{ $totals['count'] }} item</span>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-muted">Subtotal</span>
                        <span class="text-gray-900 font-medium">Rp {{ number_format($totals['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    @if($totals['tax'] > 0)
                    <div class="flex justify-between">
                        <span class="text-muted">Pajak</span>
                        <span class="text-gray-900 font-medium">Rp {{ number_format($totals['tax'], 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($totals['service'] > 0)
                    <div class="flex justify-between">
                        <span class="text-muted">Biaya Layanan</span>
                        <span class="text-gray-900 font-medium">Rp {{ number_format($totals['service'], 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-t ui-divider flex justify-between items-center">
                    <span class="font-semibold">Total</span>
                    <span class="text-2xl font-extrabold text-primary-700">Rp {{ number_format($totals['grand_total'], 0, ',', '.') }}</span>
                </div>
            </div>

            <a href="{{ route('cafe.checkout') }}" class="block w-full tap-44 py-4 bg-primary-600 text-white text-center font-semibold ui-btn hover:bg-primary-700 transition-colors shadow-soft">
                Checkout
                <svg class="w-5 h-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        @endif
    </div>
</x-cafe-layout>
