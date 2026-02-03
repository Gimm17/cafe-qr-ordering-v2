<x-admin-layout title="Orders">
    <!-- Filters -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('admin.orders') }}" method="GET" class="flex flex-wrap gap-4">
            <select name="status" class="px-4 py-2 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                <option value="">Semua Status</option>
                <option value="DITERIMA" {{ request('status') == 'DITERIMA' ? 'selected' : '' }}>Diterima</option>
                <option value="DIPROSES" {{ request('status') == 'DIPROSES' ? 'selected' : '' }}>Diproses</option>
                <option value="READY" {{ request('status') == 'READY' ? 'selected' : '' }}>Ready</option>
                <option value="SELESAI" {{ request('status') == 'SELESAI' ? 'selected' : '' }}>Selesai</option>
            </select>
            <select name="payment" class="px-4 py-2 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                <option value="">Semua Pembayaran</option>
                <option value="PAID" {{ request('payment') == 'PAID' ? 'selected' : '' }}>Sudah Bayar</option>
                <option value="UNPAID" {{ request('payment') == 'UNPAID' ? 'selected' : '' }}>Belum Bayar</option>
            </select>
            <select name="fulfillment" class="px-4 py-2 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                <option value="">Semua Tipe</option>
                <option value="DINE_IN" {{ request('fulfillment') == 'DINE_IN' ? 'selected' : '' }}>Dine In</option>
                <option value="PICKUP" {{ request('fulfillment') == 'PICKUP' ? 'selected' : '' }}>Pickup</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium">
                Filter
            </button>
            @if(request()->hasAny(['status', 'payment', 'fulfillment']))
            <a href="{{ route('admin.orders') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Kanban Board -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $statusLabels = [
                'DITERIMA' => ['label' => 'Diterima', 'color' => 'blue'],
                'DIPROSES' => ['label' => 'Diproses', 'color' => 'amber'],
                'READY' => ['label' => 'Ready', 'color' => 'green'],
                'SELESAI' => ['label' => 'Selesai', 'color' => 'gray'],
            ];
        @endphp

        @foreach($statusLabels as $status => $config)
        <div class="bg-{{ $config['color'] }}-50 rounded-2xl p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-{{ $config['color'] }}-800">{{ $config['label'] }}</h3>
                <span class="bg-{{ $config['color'] }}-200 text-{{ $config['color'] }}-800 text-xs font-bold px-2 py-1 rounded-full">
                    {{ $orders->where('order_status', $status)->count() }}
                </span>
            </div>

            <div class="space-y-3">
                @foreach($orders->where('order_status', $status) as $order)
                <div class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow cursor-pointer" onclick="window.location='{{ route('admin.orders.show', $order) }}'">
                    <div class="flex justify-between items-start mb-2">
                        <span class="font-bold text-gray-800">{{ $order->order_code }}</span>
                        <span class="text-xs px-2 py-1 rounded-full font-medium {{ $order->payment_status === 'PAID' ? 'bg-green-100 text-green-700' : ($order->payment_status === 'EXPIRED' || $order->created_at->diffInMinutes(now()) >= 10 ? 'bg-gray-100 text-gray-500' : 'bg-red-100 text-red-700') }}">
                            @if($order->payment_status === 'PAID')
                                ✓ Paid
                            @elseif($order->payment_status === 'EXPIRED' || $order->created_at->diffInMinutes(now()) >= 10)
                                Expired
                            @else
                                Unpaid
                            @endif
                        </span>
                    </div>
                    
                    <div class="mb-2">
                        <p class="text-sm text-gray-600">{{ $order->customer_name }}</p>
                        <p class="text-xs text-gray-500">
                            @if($order->fulfillment_type === 'DINE_IN')
                                🪑 Meja {{ $order->table->table_no ?? '-' }}
                            @else
                                📍 Pickup
                            @endif
                        </p>
                    </div>

                    <div class="flex justify-between items-center text-xs text-gray-500">
                        <span>{{ $order->created_at->diffForHumans() }}</span>
                        <span class="font-semibold text-gray-700">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>

                    @if($order->payment_status !== 'PAID' && $order->created_at->diffInMinutes(now()) < 10)
                    <div class="mt-2 text-xs text-amber-600 font-bold bg-amber-50 p-1 rounded text-center countdown-timer" data-expires="{{ $order->created_at->addMinutes(10)->toISOString() }}">
                        ⏳ <span class="timer-display">--:--</span>
                    </div>
                    @endif

                    <!-- Quick Status Update -->
                    @if($status !== 'SELESAI')
                    <div class="mt-3 flex gap-2" onclick="event.stopPropagation()">
                        @php
                            // Skip DIPROSES for DITERIMA - akan otomatis saat PAID
                            // DITERIMA + UNPAID -> tidak perlu tombol (tunggu bayar)
                            // DITERIMA + PAID -> sudah otomatis ke DIPROSES via webhook
                            $nextStatus = match($status) {
                                'DITERIMA' => null, // hilangkan, otomatis via webhook
                                'DIPROSES' => 'READY',
                                'READY' => 'SELESAI',
                                default => null
                            };
                        @endphp
                        @if($nextStatus)
                        <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="status" value="{{ $nextStatus }}">
                            <button type="submit" class="w-full py-2 bg-{{ $config['color'] }}-500 text-white rounded-lg text-sm font-medium hover:bg-{{ $config['color'] }}-600 transition-colors">
                                → {{ $statusLabels[$nextStatus]['label'] }}
                            </button>
                        </form>
                        @endif

                        <!-- Delete button for unpaid orders -->
                        @if($order->payment_status !== 'PAID')
                        <form action="{{ route('admin.orders.delete', $order) }}" method="POST" onsubmit="return confirm('Hapus pesanan {{ $order->order_code }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors" title="Hapus Pesanan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach

                @if($orders->where('order_status', $status)->isEmpty())
                <div class="text-center py-8 text-{{ $config['color'] }}-400">
                    <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-sm">Tidak ada</p>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <script>
        function updateTimers() {
            document.querySelectorAll('.countdown-timer').forEach(el => {
                const expires = new Date(el.dataset.expires).getTime();
                const now = new Date().getTime();
                const distance = expires - now;

                if (distance < 0) {
                    el.innerHTML = '⚠️ Expired';
                    el.classList.add('text-gray-500', 'bg-gray-100');
                    el.classList.remove('text-red-600', 'bg-red-50');
                    return;
                }

                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                const display = el.querySelector('.timer-display');
                if (display) {
                    display.innerText = 
                        (minutes < 10 ? '0' : '') + minutes + ':' + 
                        (seconds < 10 ? '0' : '') + seconds;
                }
            });
        }
        setInterval(updateTimers, 1000);
        updateTimers();
    </script>
</x-admin-layout>
