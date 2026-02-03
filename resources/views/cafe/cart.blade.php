<x-cafe-layout :tableNo="$tableNo" title="Keranjang - Cafe Order">
    <div class="max-w-lg mx-auto px-4">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Keranjang</h1>

        @if(empty($items))
        <!-- Empty Cart -->
        <div class="text-center py-16">
            <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Keranjang Kosong</h2>
            <p class="text-gray-500 mb-6">Belum ada item di keranjang</p>
            <a href="{{ route('cafe.menu') }}" class="inline-flex items-center justify-center px-6 py-3 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                Lihat Menu
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
        @else
        <!-- Cart Items -->
        <div class="space-y-4 mb-6">
            @foreach($items as $item)
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <div class="flex gap-4">
                    <!-- Product Image -->
                    <div class="w-20 h-20 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden">
                        @if($item['image_url'] ?? false)
                        <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Product Info -->
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-800 mb-1">{{ $item['name'] }}</h3>
                        
                        <!-- Modifiers Summary -->
                        @if(!empty($item['modifiers_summary']))
                        <p class="text-xs text-gray-500 mb-1">{{ $item['modifiers_summary'] }}</p>
                        @endif
                        
                        <!-- Note -->
                        @if(!empty($item['note']))
                        <p class="text-xs text-amber-600 italic mb-1">📝 {{ $item['note'] }}</p>
                        @endif
                        
                        <p class="text-primary-600 font-semibold text-sm">{{ $item['formatted_unit_price'] }}</p>
                    </div>
                    
                    <!-- Quantity & Remove -->
                    <div class="flex flex-col items-end justify-between">
                        <form id="remove-item-{{ $loop->index }}" action="{{ route('cafe.cart.remove') }}" method="POST" class="hidden">
                            @csrf
                            <input type="hidden" name="cart_key" value="{{ $item['cart_key'] }}">
                        </form>
                        <button type="button" onclick="confirmRemoveItem('remove-item-{{ $loop->index }}', '{{ $item['name'] }}')" class="text-gray-400 hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                        
                        <div class="flex items-center gap-2">
                            <form action="{{ route('cafe.cart.update') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="cart_key" value="{{ $item['cart_key'] }}">
                                <input type="hidden" name="qty" value="{{ $item['qty'] - 1 }}">
                                <button type="submit" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                </button>
                            </form>
                            <span class="w-8 text-center font-bold text-gray-800">{{ $item['qty'] }}</span>
                            <form action="{{ route('cafe.cart.update') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="cart_key" value="{{ $item['cart_key'] }}">
                                <input type="hidden" name="qty" value="{{ $item['qty'] + 1 }}">
                                <button type="submit" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Line Total -->
                <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-sm text-gray-500">Subtotal</span>
                    <span class="font-bold text-gray-800">{{ $item['formatted_line_total'] }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-2xl p-4 shadow-lg border border-gray-100 mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">Ringkasan Pesanan</h3>
            
            <div class="space-y-2 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Subtotal ({{ $totals['count'] }} item)</span>
                    <span class="text-gray-800">Rp {{ number_format($totals['subtotal'], 0, ',', '.') }}</span>
                </div>
                @if($totals['tax'] > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Pajak</span>
                    <span class="text-gray-800">Rp {{ number_format($totals['tax'], 0, ',', '.') }}</span>
                </div>
                @endif
                @if($totals['service'] > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Biaya Layanan</span>
                    <span class="text-gray-800">Rp {{ number_format($totals['service'], 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
            
            <div class="pt-4 border-t border-gray-200 flex justify-between items-center">
                <span class="font-semibold text-gray-800">Total</span>
                <span class="text-2xl font-bold text-primary-600">Rp {{ number_format($totals['grand_total'], 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Checkout Button -->
        <a href="{{ route('cafe.checkout') }}" 
           class="block w-full py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-center font-semibold rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            Lanjut ke Pembayaran
            <svg class="w-5 h-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </a>
        @endif
    </div>
</x-cafe-layout>
