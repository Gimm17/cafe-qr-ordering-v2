<x-cafe-layout :tableNo="$tableNo" title="{{ $product->name }} - Cafe Order">
    <x-slot:head>
    <style>
        .modifier-option input:checked + label {
            border-color: #10b981;
            background-color: #ecfdf5;
        }
        .modifier-option input:checked + label .check-icon {
            display: flex;
        }
    </style>
    </x-slot:head>

    <div class="max-w-lg mx-auto px-4">
        <!-- Back Button -->
        <a href="{{ route('cafe.menu') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-4 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>

        <!-- Product Image -->
        <div class="relative rounded-2xl overflow-hidden mb-6 bg-gray-100 aspect-square">
            @if($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            @endif
            
            <!-- Badges -->
            <div class="absolute top-4 left-4 flex gap-2">
                @if($product->is_best_seller)
                <span class="bg-amber-500 text-white text-sm font-bold px-3 py-1 rounded-full shadow-lg">
                    ⭐ Best Seller
                </span>
                @endif
                @if($product->is_sold_out)
                <span class="bg-red-500 text-white text-sm font-bold px-3 py-1 rounded-full shadow-lg">
                    Habis
                </span>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="mb-6">
            <div class="flex items-start justify-between mb-2">
                <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
            </div>
            @if($product->category)
            <span class="inline-block bg-gray-100 text-gray-600 text-xs font-medium px-3 py-1 rounded-full mb-3">
                {{ $product->category->name }}
            </span>
            @endif
            <p class="text-gray-600 text-sm leading-relaxed">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>
        </div>

        <!-- Add to Cart Form -->
        <form action="{{ route('cafe.cart.add') }}" method="POST" id="addToCartForm">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <!-- Modifiers -->
            @if($product->modGroups->isNotEmpty())
            <div class="space-y-6 mb-6">
                @foreach($product->modGroups as $modGroup)
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-800">{{ $modGroup->name }}</h3>
                        @if($modGroup->is_required)
                        <span class="text-xs bg-red-100 text-red-600 font-medium px-2 py-1 rounded-full">Wajib</span>
                        @else
                        <span class="text-xs bg-gray-100 text-gray-500 font-medium px-2 py-1 rounded-full">Opsional</span>
                        @endif
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
                                   class="flex items-center justify-between p-3 rounded-xl border-2 border-gray-200 cursor-pointer transition-all hover:border-primary-300">
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center check-icon hidden">
                                        <svg class="w-3 h-3 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-700">{{ $option->name }}</span>
                                </div>
                                @if($option->price_modifier != 0)
                                <span class="text-sm font-semibold {{ $option->price_modifier > 0 ? 'text-primary-600' : 'text-green-600' }}">
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

            <!-- Notes -->
            <div class="mb-6">
                <label class="block font-semibold text-gray-800 mb-2">Catatan</label>
                <textarea name="note" 
                          rows="2" 
                          placeholder="Contoh: tidak pakai es, extra gula..."
                          class="w-full px-4 py-3 bg-white rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none text-gray-700 resize-none"></textarea>
            </div>

            <!-- Quantity -->
            <div class="mb-6">
                <label class="block font-semibold text-gray-800 mb-2">Jumlah</label>
                <div class="flex items-center gap-4">
                    <button type="button" onclick="updateQty(-1)" class="w-12 h-12 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </button>
                    <input type="number" name="qty" value="1" min="1" max="20" id="qtyInput" 
                           class="w-20 text-center text-xl font-bold bg-white border border-gray-200 rounded-xl py-2 focus:outline-none focus:border-primary-500">
                    <button type="button" onclick="updateQty(1)" class="w-12 h-12 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Price Summary & Add Button -->
            <div class="bg-white rounded-2xl p-4 shadow-lg border border-gray-100 sticky bottom-20">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-gray-600">Total Harga</span>
                    <span class="text-2xl font-bold text-primary-600" id="totalPrice">{{ $product->price_rupiah }}</span>
                </div>
                
                @if($product->is_sold_out)
                <button type="button" disabled class="w-full py-4 bg-gray-300 text-gray-500 font-semibold rounded-xl cursor-not-allowed">
                    Stok Habis
                </button>
                @else
                <button type="submit" class="w-full py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
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
        
        // Listen to modifier changes
        document.querySelectorAll('.modifier-option input').forEach(input => {
            input.addEventListener('change', calculateTotal);
        });
        
        document.getElementById('qtyInput').addEventListener('input', calculateTotal);
        
        // Initial calculation
        calculateTotal();
    </script>
    </x-slot:scripts>
</x-cafe-layout>
