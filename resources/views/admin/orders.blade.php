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
                        <span class="text-xs px-2 py-1 rounded-full font-medium {{ $order->payment_status === 'PAID' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $order->payment_status === 'PAID' ? '✓ Paid' : 'Unpaid' }}
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

                    <!-- Quick Status Update -->
                    @if($status !== 'SELESAI')
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="mt-3" onclick="event.stopPropagation()">
                        @csrf
                        @php
                            $nextStatus = match($status) {
                                'DITERIMA' => 'DIPROSES',
                                'DIPROSES' => 'READY',
                                'READY' => 'SELESAI',
                                default => null
                            };
                        @endphp
                        @if($nextStatus)
                        <input type="hidden" name="status" value="{{ $nextStatus }}">
                        <button type="submit" class="w-full py-2 bg-{{ $config['color'] }}-500 text-white rounded-lg text-sm font-medium hover:bg-{{ $config['color'] }}-600 transition-colors">
                            → {{ $statusLabels[$nextStatus]['label'] }}
                        </button>
                        @endif
                    </form>
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
</x-admin-layout>
