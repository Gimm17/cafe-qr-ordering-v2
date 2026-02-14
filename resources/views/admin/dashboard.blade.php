<x-admin-layout title="Dashboard">
    {{-- Cafe Open/Close Toggle --}}
    <div class="mb-6">
        <div class="ui-card-flat p-4 flex items-center justify-between gap-4 {{ $cafeIsOpen ? 'border-l-4 border-green-500' : 'border-l-4 border-red-500' }}">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl {{ $cafeIsOpen ? 'bg-green-50' : 'bg-red-50' }} flex items-center justify-center">
                    <span class="text-2xl">{{ $cafeIsOpen ? '🟢' : '🔴' }}</span>
                </div>
                <div>
                    <p class="font-bold text-gray-900 text-lg">Cafe {{ $cafeIsOpen ? 'BUKA' : 'TUTUP' }}</p>
                    <p class="text-xs text-muted">{{ $cafeIsOpen ? 'Pelanggan bisa memesan saat ini' : 'Semua pesanan diblokir' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.settings.toggle-cafe') }}">
                @csrf
                <button type="submit"
                    class="tap-44 px-6 py-3 rounded-2xl font-bold text-sm transition-colors {{ $cafeIsOpen ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}"
                    onclick="return confirm('{{ $cafeIsOpen ? 'Tutup cafe sekarang?' : 'Buka cafe sekarang?' }}')">
                    {{ $cafeIsOpen ? '🔒 Tutup Cafe' : '🔓 Buka Cafe' }}
                </button>
            </form>
        </div>
    </div>

    <!-- KPI Cards (compact & mobile-first) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5 mb-6">
        <div class="ui-card-flat p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-2xl bg-primary-50 border border-line flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="ui-chip px-3 py-1 text-[11px] text-muted">Hari ini</span>
            </div>
            <p class="text-xs text-muted">Total Penjualan</p>
            <p class="text-lg sm:text-2xl font-extrabold tracking-tight text-gray-900">Rp {{ number_format($todaySales ?? 0, 0, ',', '.') }}</p>
        </div>

        <div class="ui-card-flat p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-2xl bg-primary-50 border border-line flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="ui-chip px-3 py-1 text-[11px] text-muted">Hari ini</span>
            </div>
            <p class="text-xs text-muted">Total Pesanan</p>
            <p class="text-lg sm:text-2xl font-extrabold tracking-tight text-gray-900">{{ $todayOrders ?? 0 }}</p>
        </div>

        <div class="ui-card-flat p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-2xl bg-primary-50 border border-line flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="ui-chip px-3 py-1 text-[11px] text-muted">Aktif</span>
            </div>
            <p class="text-xs text-muted">Diproses</p>
            <p class="text-lg sm:text-2xl font-extrabold tracking-tight text-gray-900">{{ $pendingOrders ?? 0 }}</p>
        </div>

        <div class="ui-card-flat p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-2xl bg-primary-50 border border-line flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <span class="ui-chip px-3 py-1 text-[11px] text-muted">Total</span>
            </div>
            <p class="text-xs text-muted">Menu Aktif</p>
            <p class="text-lg sm:text-2xl font-extrabold tracking-tight text-gray-900">{{ $totalProducts ?? 0 }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Recent Orders -->
        <section class="ui-card">
            <div class="p-5 border-b ui-divider flex items-center justify-between">
                <div>
                    <h3 class="font-semibold tracking-tight">Pesanan terbaru</h3>
                    <p class="text-xs text-muted">Pantau order yang masuk.</p>
                </div>
                <a href="{{ route('admin.orders') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">Lihat semua</a>
            </div>

            <div class="divide-y ui-divider">
                @forelse($recentOrders ?? [] as $order)
                    <div class="p-4 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 truncate">{{ $order->order_code }}</p>
                            <p class="text-xs text-muted mt-0.5">{{ $order->customer_name }} • Meja {{ $order->table->table_no ?? '-' }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-bold text-gray-900">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                            <span class="inline-flex ui-chip px-3 py-1 text-[11px] font-semibold mt-1
                                {{ $order->order_status === 'DITERIMA' ? 'text-primary-800' : '' }}
                                {{ $order->order_status === 'DIPROSES' ? 'text-amber-700' : '' }}
                                {{ $order->order_status === 'READY' ? 'text-green-700' : '' }}
                                {{ $order->order_status === 'SELESAI' ? 'text-gray-700' : '' }}">
                                {{ $order->order_status }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-muted">Belum ada pesanan hari ini</div>
                @endforelse
            </div>
        </section>

        <!-- Top Products -->
        <section class="ui-card">
            <div class="p-5 border-b ui-divider">
                <h3 class="font-semibold tracking-tight">Produk terlaris</h3>
                <p class="text-xs text-muted">Berdasarkan jumlah terjual.</p>
            </div>

            <div class="divide-y ui-divider">
                @forelse($topProducts ?? [] as $product)
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50 transition-colors">
                        <div class="w-12 h-12 rounded-2xl bg-primary-50 border border-line flex items-center justify-center flex-shrink-0 overflow-hidden">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 truncate">{{ $product->name }}</p>
                            <p class="text-xs text-muted mt-0.5">{{ $product->order_count ?? 0 }} terjual</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-primary-800">{{ $product->price_rupiah }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-muted">Belum ada data penjualan</div>
                @endforelse
            </div>
        </section>
    </div>
</x-admin-layout>
