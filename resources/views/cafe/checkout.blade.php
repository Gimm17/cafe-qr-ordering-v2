<x-cafe-layout :tableNo="$tableNo" title="Checkout - Cafe Order">
    <div class="max-w-lg mx-auto px-4">
        <a href="{{ route('cafe.cart') }}" class="inline-flex items-center text-sm text-muted hover:text-ink mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke keranjang
        </a>

        <div class="mb-5">
            <h1 class="text-xl font-bold tracking-tight">Checkout</h1>
            <p class="text-sm text-muted">Isi detail singkat, lalu kirim pesanan.</p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('cafe.checkout.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Customer Name -->
            <div class="ui-card p-5">
                <label class="block font-semibold mb-2">Nama pemesan</label>
                <input type="text"
                       name="customer_name"
                       value="{{ old('customer_name') }}"
                       placeholder="Contoh: Agim"
                       required
                       class="w-full px-4 py-3 bg-white border border-line rounded-2xl ui-focus transition-all text-gray-900">
                <p class="text-xs text-muted mt-2">Nama ini akan dipanggil saat pesanan siap.</p>
            </div>

            <!-- Fulfillment Type -->
            <div class="ui-card p-5">
                <label class="block font-semibold mb-3">Pengambilan pesanan</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="fulfillment_type" value="DINE_IN" checked class="peer hidden">
                        <div class="p-4 rounded-2xl border border-line peer-checked:border-primary-600 peer-checked:bg-primary-50 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl bg-white border border-line flex items-center justify-center">
                                    <svg class="w-6 h-6 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Diantar ke meja</p>
                                    <p class="text-xs text-muted">Untuk dine-in</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input type="radio" name="fulfillment_type" value="PICKUP" class="peer hidden">
                        <div class="p-4 rounded-2xl border border-line peer-checked:border-primary-600 peer-checked:bg-primary-50 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl bg-white border border-line flex items-center justify-center">
                                    <svg class="w-6 h-6 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Ambil di kasir</p>
                                    <p class="text-xs text-muted">Take away</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="ui-card p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold tracking-tight">Ringkasan pesanan</h3>
                    <span class="ui-chip px-3 py-1 text-xs text-muted">{{ $totals['count'] }} item</span>
                </div>

                <div class="space-y-3 mb-4">
                    @foreach($items as $item)
                        <div class="flex justify-between items-start gap-3 text-sm">
                            <div class="flex-1 min-w-0">
                                <p class="text-gray-900 font-medium truncate">{{ $item['name'] }} <span class="text-muted font-normal">x{{ $item['qty'] }}</span></p>
                                @if(!empty($item['modifiers_summary']))
                                    <p class="text-xs text-muted mt-0.5">{{ $item['modifiers_summary'] }}</p>
                                @endif
                            </div>
                            <span class="text-gray-900 font-semibold whitespace-nowrap">{{ $item['formatted_line_total'] }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t ui-divider space-y-2 text-sm">
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
                        <span class="text-muted">Biaya layanan</span>
                        <span class="text-gray-900 font-medium">Rp {{ number_format($totals['service'], 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between pt-3 border-t ui-divider">
                        <span class="font-semibold">Total</span>
                        <span class="text-xl font-extrabold text-primary-700">Rp {{ number_format($totals['grand_total'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full tap-44 py-4 bg-primary-600 text-white font-semibold ui-btn hover:bg-primary-700 transition-colors shadow-soft">
                Kirim Pesanan
                <svg class="w-5 h-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </button>
        </form>
    </div>
</x-cafe-layout>
