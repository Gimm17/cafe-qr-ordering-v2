<x-cafe-layout :tableNo="$tableNo" title="Menu - Cafe Order">
    <div class="max-w-lg mx-auto px-4">
        <!-- Search Bar -->
        <div class="mb-6">
            <form action="{{ route('cafe.menu') }}" method="GET" class="relative">
                <input type="text" 
                       name="search" 
                       value="{{ $search ?? '' }}"
                       placeholder="Cari menu..." 
                       class="w-full pl-12 pr-10 py-3 bg-white rounded-2xl border border-line ui-focus transition-all outline-none text-gray-700 tap-44">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                @if($search)
                <a href="{{ route('cafe.menu') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
                @endif
            </form>
        </div>

        <!-- Category Filter Pills -->
        <div class="mb-6 overflow-x-auto pb-2 -mx-4 px-4">
            <div class="flex gap-2 min-w-max">
                <a href="{{ route('cafe.menu') }}" 
                   class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ !$selectedCategory ? 'bg-primary-600 text-white shadow-soft' : 'ui-chip text-gray-800 hover:bg-white' }}">
                    Semua
                </a>
                @foreach($categories as $category)
                <a href="{{ route('cafe.menu', ['category' => $category->id]) }}" 
                   class="px-4 py-2 rounded-full text-sm font-semibold transition-all whitespace-nowrap {{ $selectedCategory == $category->id ? 'bg-primary-600 text-white shadow-soft' : 'ui-chip text-gray-800 hover:bg-white' }}">
                    {{ $category->name }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-2xl text-green-700 text-sm">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-sm">
            {{ session('error') }}
        </div>
        @endif

        <!-- Product Grid -->
        @if($products->isEmpty())
        <div class="ui-card p-10 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-muted">Tidak ada menu ditemukan</p>
        </div>
        @else
        <div class="grid grid-cols-2 gap-4">
            @foreach($products as $product)
            @php $isClosed = in_array($product->category_id, $closedCategoryIds); @endphp
            <a href="{{ route('cafe.product.show', $product->slug) }}" 
               class="ui-card-flat overflow-hidden hover:shadow-soft transition-shadow {{ $product->is_sold_out ? 'opacity-60' : '' }} {{ $isClosed ? 'grayscale opacity-50 pointer-events-none' : '' }}">
                <!-- Product Image -->
                <div class="relative aspect-square bg-primary-50/40">
                    @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    @endif
                    
                    <!-- Badges -->
                    <div class="absolute top-2 left-2 flex flex-col gap-1">
                        @if($isClosed)
                        <span class="bg-red-600 text-white px-2 py-1 rounded-lg text-[11px] font-bold shadow-sm">🚫 Close Order</span>
                        @endif
                        @if($product->is_best_seller && !$isClosed)
                        <span class="ui-chip px-2 py-1 text-[11px] font-bold text-amber-700">⭐ Best</span>
                        @endif
                        @if($product->is_sold_out && !$isClosed)
                        <span class="ui-chip px-2 py-1 text-[11px] font-bold text-red-700">Habis</span>
                        @endif
                    </div>
                </div>
                
                <!-- Product Info -->
                <div class="p-3">
                    <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 mb-1">{{ $product->name }}</h3>
                    <p class="text-primary-700 font-extrabold text-sm">{{ $product->price_rupiah }}</p>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</x-cafe-layout>
