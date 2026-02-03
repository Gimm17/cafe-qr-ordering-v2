<x-admin-layout title="Dashboard">
    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Sales Today -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full font-medium">Hari Ini</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($todaySales ?? 0, 0, ',', '.') }}</h3>
            <p class="text-gray-500 text-sm">Total Penjualan</p>
        </div>

        <!-- Orders Today -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded-full font-medium">Hari Ini</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $todayOrders ?? 0 }}</h3>
            <p class="text-gray-500 text-sm">Total Pesanan</p>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs text-amber-600 bg-amber-50 px-2 py-1 rounded-full font-medium">Aktif</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $pendingOrders ?? 0 }}</h3>
            <p class="text-gray-500 text-sm">Pesanan Diproses</p>
        </div>

        <!-- Total Products -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <span class="text-xs text-purple-600 bg-purple-50 px-2 py-1 rounded-full font-medium">Total</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $totalProducts ?? 0 }}</h3>
            <p class="text-gray-500 text-sm">Menu Aktif</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Pesanan Terbaru</h3>
                <a href="{{ route('admin.orders') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Lihat Semua</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentOrders ?? [] as $order)
                <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div>
                        <p class="font-medium text-gray-800">{{ $order->order_code }}</p>
                        <p class="text-sm text-gray-500">{{ $order->customer_name }} • Meja {{ $order->table->table_no ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                        <span class="inline-block px-2 py-1 text-xs font-medium rounded-full 
                            {{ $order->order_status === 'DITERIMA' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $order->order_status === 'DIPROSES' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $order->order_status === 'READY' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $order->order_status === 'SELESAI' ? 'bg-gray-100 text-gray-700' : '' }}">
                            {{ $order->order_status }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-500">
                    Belum ada pesanan hari ini
                </div>
                @endforelse
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Produk Terlaris</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($topProducts ?? [] as $product)
                <div class="p-4 flex items-center gap-4 hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                        @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-800 truncate">{{ $product->name }}</p>
                        <p class="text-sm text-gray-500">{{ $product->order_count ?? 0 }} terjual</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-primary-600">{{ $product->price_rupiah }}</p>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-500">
                    Belum ada data penjualan
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>
