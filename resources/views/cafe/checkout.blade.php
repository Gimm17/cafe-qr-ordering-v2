<x-cafe-layout :tableNo="$tableNo" title="Checkout - Cafe Order">
    <div class="max-w-lg mx-auto px-4">
        <!-- Back Button -->
        <a href="{{ route('cafe.cart') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-4 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>

        <h1 class="text-2xl font-bold text-gray-800 mb-6">Checkout</h1>

        <!-- Flash Messages -->
        @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('cafe.checkout.store') }}" method="POST">
            @csrf

            <!-- Customer Name -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-4">
                <label class="block font-semibold text-gray-800 mb-2">Nama Pemesan</label>
                <input type="text" 
                       name="customer_name" 
                       value="{{ old('customer_name') }}"
                       placeholder="Masukkan nama Anda"
                       required
                       class="w-full px-4 py-3 bg-gray-50 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:bg-white transition-all outline-none text-gray-700">
                <p class="text-xs text-gray-500 mt-2">Nama ini akan dipanggil saat pesanan siap</p>
            </div>

            <!-- Fulfillment Type -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
                <label class="block font-semibold text-gray-800 mb-3">Pengambilan Pesanan</label>
                
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="fulfillment_type" value="DINE_IN" checked class="peer hidden">
                        <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-all text-center">
                            <div class="w-12 h-12 mx-auto mb-2 bg-gray-100 peer-checked:bg-primary-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700 text-sm">Diantar ke Meja</span>
                        </div>
                    </label>
                    
                    <label class="relative cursor-pointer">
                        <input type="radio" name="fulfillment_type" value="PICKUP" class="peer hidden">
                        <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-all text-center">
                            <div class="w-12 h-12 mx-auto mb-2 bg-gray-100 peer-checked:bg-primary-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700 text-sm">Ambil di Kasir</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">Ringkasan Pesanan</h3>
                
                <div class="space-y-3 mb-4">
                    @foreach($items as $item)
                    <div class="flex justify-between items-start text-sm">
                        <div class="flex-1">
                            <span class="text-gray-800">{{ $item['name'] }}</span>
                            <span class="text-gray-500">x{{ $item['qty'] }}</span>
                            @if(!empty($item['modifiers_summary']))
                            <p class="text-xs text-gray-400">{{ $item['modifiers_summary'] }}</p>
                            @endif
                        </div>
                        <span class="text-gray-800 font-medium">{{ $item['formatted_line_total'] }}</span>
                    </div>
                    @endforeach
                </div>
                
                <div class="pt-4 border-t border-gray-200 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
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
                    <div class="flex justify-between pt-2 border-t border-gray-100">
                        <span class="font-semibold text-gray-800">Total</span>
                        <span class="text-xl font-bold text-primary-600">Rp {{ number_format($totals['grand_total'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Lanjut ke Pembayaran
            </button>
        </form>
    </div>
</x-cafe-layout>
