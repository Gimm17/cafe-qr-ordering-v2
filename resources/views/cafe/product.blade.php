<x-cafe-layout :tableNo="$tableNo" title="{{ $product->name }} - Cafe Order">
    <x-slot:head>
    <style>
        .modifier-option input:checked + label {
            border-color: rgba(94, 75, 58, .45);
            background-color: rgba(94, 75, 58, .06);
        }
        .modifier-option input:checked + label .check-icon {
            display: flex;
        }
    </style>
    </x-slot:head>

    <div class="max-w-lg mx-auto px-4">
        <a href="{{ route('cafe.menu') }}" class="inline-flex items-center text-sm text-muted hover:text-ink mb-4 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>

        <!-- Image -->
        <div class="relative rounded-2xl overflow-hidden mb-6 bg-primary-50/40 aspect-square border border-line {{ $isCloseOrder ? 'grayscale' : '' }}">
            @if($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-24 h-24 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            @endif

            <div class="absolute top-3 left-3 flex flex-col gap-2">
                @if($isCloseOrder)
                <span class="bg-red-600 text-white px-3 py-1 rounded-lg text-[11px] font-bold shadow-sm">🚫 Close Order</span>
                @endif
                @if($product->is_best_seller && !$isCloseOrder)
                <span class="ui-chip px-3 py-1 text-[11px] font-bold text-amber-700">⭐ Best</span>
                @endif
                @if($product->is_sold_out && !$isCloseOrder)
                <span class="ui-chip px-3 py-1 text-[11px] font-bold text-red-700">Habis</span>
                @endif
            </div>
        </div>

        @if($isCloseOrder)
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-sm flex items-center gap-2">
            @if(!$cafeIsOpen)
                🔴 <strong>Cafe sedang tutup.</strong> Tidak bisa memesan saat ini.
            @else
                🚫 <strong>Menu ini sudah Close Order.</strong> Tidak bisa ditambahkan ke keranjang.
            @endif
        </div>
        @endif

        <!-- Info -->
        <div class="mb-6">
            <div class="flex items-start justify-between gap-3 mb-2">
                <h1 class="text-xl font-extrabold tracking-tight text-gray-900">{{ $product->name }}</h1>
                <span class="text-primary-700 font-extrabold">{{ $product->price_rupiah }}</span>
            </div>
            @if($product->category)
            <span class="inline-flex ui-chip px-3 py-1 text-xs font-semibold text-gray-700 mb-3">
                {{ $product->category->name }}
            </span>
            @endif
            <p class="text-sm text-gray-700 leading-relaxed">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>
        </div>

        <!-- Add to Cart -->
        <form action="{{ route('cafe.cart.add') }}" method="POST" id="addToCartForm">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            @if($product->modGroups->isNotEmpty())
            <div class="space-y-4 mb-6">
                @foreach($product->modGroups as $modGroup)
                <div class="ui-card-flat p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-900">{{ $modGroup->name }}</h3>
                        <span class="ui-chip px-2 py-1 text-[11px] font-bold {{ $modGroup->is_required ? 'text-red-700' : 'text-gray-600' }}">
                            {{ $modGroup->is_required ? 'Wajib' : 'Opsional' }}
                        </span>
                    </div>

                    <div class="space-y-2">
                        @foreach($modGroup->activeOptions as $option)
                        <div class="modifier-option">
                            <input type="{{ $modGroup->isSingleSelect() ? 'radio' : 'checkbox' }}"
                                   name="modifiers{{ $modGroup->isSingleSelect() ? '['.$modGroup->id.']' : '[]' }}"
                                   value="{{ $option->id }}"
                                   id="mod_{{ $option->id }}"
                                   class="hidden"
                                   data-price="{{ $option->price_modifier }}"
                                   {{ $modGroup->is_required && $loop->first && $modGroup->isSingleSelect() ? 'checked' : '' }}>

                            <label for="mod_{{ $option->id }}"
                                   class="flex items-center justify-between p-3 rounded-2xl border border-line cursor-pointer transition-colors hover:bg-white">
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-full border border-line flex items-center justify-center check-icon hidden">
                                        <svg class="w-3.5 h-3.5 text-primary-700" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $option->name }}</span>
                                </div>
                                @if($option->price_modifier != 0)
                                <span class="text-sm font-bold {{ $option->price_modifier > 0 ? 'text-primary-700' : 'text-green-700' }}">
                                    {{ $option->formatted_price_modifier }}
                                </span>
                                @endif
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <div class="mb-6">
                <label class="block font-semibold text-gray-900 mb-2">Catatan</label>
                <textarea name="note" rows="2" placeholder="Contoh: tidak pakai es, extra gula..."
                          class="w-full px-4 py-3 bg-white rounded-2xl border border-line ui-focus transition-all outline-none text-gray-800 resize-none"></textarea>
            </div>

            <div class="mb-6">
                <label class="block font-semibold text-gray-900 mb-2">Jumlah</label>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="updateQty(-1)" class="tap-44 w-12 h-12 rounded-2xl bg-white border border-line hover:bg-gray-50 flex items-center justify-center transition-colors">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    </button>
                    <input type="number" name="qty" value="1" min="1" max="20" id="qtyInput"
                           class="tap-44 w-20 text-center text-lg font-extrabold bg-white border border-line rounded-2xl py-2 ui-focus">
                    <button type="button" onclick="updateQty(1)" class="tap-44 w-12 h-12 rounded-2xl bg-white border border-line hover:bg-gray-50 flex items-center justify-center transition-colors">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </div>
            </div>

            <div class="ui-card p-4 sticky bottom-20">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-muted">Total Harga</span>
                    <span class="text-xl font-extrabold text-primary-700" id="totalPrice">{{ $product->price_rupiah }}</span>
                </div>

                @if($isCloseOrder)
                <button type="button" disabled class="w-full tap-44 py-3 bg-gray-200 text-gray-500 font-semibold ui-btn cursor-not-allowed">
                    🚫 Close Order
                </button>
                @elseif($product->is_sold_out)
                <button type="button" disabled class="w-full tap-44 py-3 bg-gray-200 text-gray-500 font-semibold ui-btn cursor-not-allowed">
                    Stok Habis
                </button>
                @else
                <button type="submit" class="w-full tap-44 py-3 bg-primary-600 text-white font-semibold ui-btn hover:bg-primary-700 transition-colors shadow-soft">
                    Tambah ke Keranjang
                </button>
                @endif
            </div>
        </form>
    </div>

    <x-slot:scripts>
    <script>
        const basePrice = {{ $product->base_price }};

        function updateQty(delta) {
            const input = document.getElementById('qtyInput');
            let val = parseInt(input.value) + delta;
            if (val < 1) val = 1;
            if (val > 20) val = 20;
            input.value = val;
            calculateTotal();
        }

        function calculateTotal() {
            const qty = parseInt(document.getElementById('qtyInput').value) || 1;
            let modTotal = 0;

            document.querySelectorAll('.modifier-option input:checked').forEach(input => {
                modTotal += parseInt(input.dataset.price) || 0;
            });

            const total = (basePrice + modTotal) * qty;
            document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        document.querySelectorAll('.modifier-option input').forEach(input => {
            input.addEventListener('change', calculateTotal);
        });

        document.getElementById('qtyInput').addEventListener('input', calculateTotal);
        calculateTotal();
    </script>
    </x-slot:scripts>
</x-cafe-layout>
