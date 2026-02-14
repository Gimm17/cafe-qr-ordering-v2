<x-admin-layout title="Orders">
    <!-- Filters -->
    <div class="ui-card p-4 mb-6">
        <form action="{{ route('admin.orders') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <select name="status" class="tap-44 px-4 py-3 rounded-2xl border border-line bg-white ui-focus">
                <option value="">Semua Status</option>
                <option value="DITERIMA" {{ request('status') == 'DITERIMA' ? 'selected' : '' }}>Diterima</option>
                <option value="DIPROSES" {{ request('status') == 'DIPROSES' ? 'selected' : '' }}>Diproses</option>
                <option value="READY" {{ request('status') == 'READY' ? 'selected' : '' }}>Ready</option>
                <option value="SELESAI" {{ request('status') == 'SELESAI' ? 'selected' : '' }}>Selesai</option>
            </select>

            <select name="payment" class="tap-44 px-4 py-3 rounded-2xl border border-line bg-white ui-focus">
                <option value="">Semua Pembayaran</option>
                <option value="PAID" {{ request('payment') == 'PAID' ? 'selected' : '' }}>Sudah Bayar</option>
                <option value="UNPAID" {{ request('payment') == 'UNPAID' ? 'selected' : '' }}>Belum Bayar</option>
            </select>

            <select name="fulfillment" class="tap-44 px-4 py-3 rounded-2xl border border-line bg-white ui-focus">
                <option value="">Semua Tipe</option>
                <option value="DINE_IN" {{ request('fulfillment') == 'DINE_IN' ? 'selected' : '' }}>Dine In</option>
                <option value="PICKUP" {{ request('fulfillment') == 'PICKUP' ? 'selected' : '' }}>Pickup</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 tap-44 px-5 py-3 bg-primary-600 text-white font-semibold ui-btn hover:bg-primary-700 transition-colors">Filter</button>
                @if(request()->hasAny(['status', 'payment', 'fulfillment']))
                    <a href="{{ route('admin.orders') }}" class="flex-1 tap-44 px-5 py-3 bg-white border border-line text-gray-800 font-semibold ui-btn hover:bg-gray-50 transition-colors text-center">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Kanban Board -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        @php
            $statusLabels = [
                'DITERIMA' => ['label' => 'Diterima', 'tint' => 'primary', 'pill' => 'text-primary-800'],
                'DIPROSES' => ['label' => 'Diproses', 'tint' => 'amber', 'pill' => 'text-amber-800'],
                'READY' => ['label' => 'Ready', 'tint' => 'green', 'pill' => 'text-green-800'],
                'SELESAI' => ['label' => 'Selesai', 'tint' => 'gray', 'pill' => 'text-gray-700'],
            ];
        @endphp

        @foreach($statusLabels as $status => $config)
        <section class="ui-card overflow-hidden">
            <div class="p-4 border-b ui-divider bg-{{ $config['tint'] }}-50/60">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold tracking-tight {{ $config['pill'] }}">{{ $config['label'] }}</h3>
                    <span class="ui-chip px-3 py-1 text-xs font-bold {{ $config['pill'] }}">
                        {{ $orders->where('order_status', $status)->count() }}
                    </span>
                </div>
            </div>

            <div class="p-3 space-y-3">
                @foreach($orders->where('order_status', $status) as $order)
                <div class="ui-card-flat p-4 hover:shadow-soft transition-shadow cursor-pointer" onclick="window.location='{{ route('admin.orders.show', $order) }}'">
                    <div class="flex justify-between items-start mb-2">
                        <span class="font-bold text-gray-900">{{ $order->order_code }}</span>
                        <span class="text-[11px] px-3 py-1 rounded-full font-bold ui-chip
                            {{ $order->payment_status === 'PAID' ? 'text-green-700' : ($order->payment_status === 'EXPIRED' || $order->created_at->diffInMinutes(now()) >= 10 ? 'text-gray-600' : 'text-red-700') }}">
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
                        <p class="text-sm text-gray-900 font-medium">{{ $order->customer_name }}</p>
                        <p class="text-xs text-muted">
                            @if($order->fulfillment_type === 'DINE_IN')
                                🪑 Meja {{ $order->table->table_no ?? '-' }}
                            @else
                                📍 Pickup
                            @endif
                        </p>
                    </div>

                    <div class="flex justify-between items-center text-xs text-muted">
                        <span>{{ $order->created_at->diffForHumans() }}</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>

                    @if($order->payment_status !== 'PAID' && $order->created_at->diffInMinutes(now()) < 10)
                    <div class="mt-2 text-[11px] text-amber-700 font-bold bg-amber-50 border border-amber-200 p-2 rounded-xl text-center countdown-timer" data-expires="{{ $order->created_at->addMinutes(10)->toISOString() }}">
                        ⏳ <span class="timer-display">--:--</span> sebelum expired
                    </div>
                    @endif

                    <!-- Quick Status Update -->
                    @if($status !== 'SELESAI')
                    <div class="mt-3 flex gap-2" onclick="event.stopPropagation()">
                        @php
                            $nextStatus = match($status) {
                                'DITERIMA' => null, // otomatis via webhook ketika PAID
                                'DIPROSES' => 'READY',
                                'READY' => 'SELESAI',
                                default => null
                            };
                        @endphp

                        @if($nextStatus)
                        <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="status" value="{{ $nextStatus }}">
                            <button type="submit" class="w-full tap-44 py-2.5 bg-primary-600 text-white rounded-xl text-sm font-semibold hover:bg-primary-700 transition-colors">
                                → {{ $statusLabels[$nextStatus]['label'] }}
                            </button>
                        </form>
                        @endif

                        @if($order->payment_status !== 'PAID')
                            <form id="delete-order-{{ $order->id }}" action="{{ route('admin.orders.delete', $order) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button" onclick="confirmDelete('delete-order-{{ $order->id }}', 'pesanan {{ $order->order_code }}')" class="tap-44 px-3 py-2.5 bg-white border border-line text-red-600 rounded-xl text-sm font-bold hover:bg-red-50 transition-colors" title="Hapus Pesanan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach

                @if($orders->where('order_status', $status)->isEmpty())
                <div class="text-center py-8 text-muted">
                    <svg class="w-8 h-8 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-sm">Tidak ada</p>
                </div>
                @endif
            </div>
        </section>
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
                    el.classList.add('text-gray-600', 'bg-gray-50');
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

        // ── Auto-refresh: poll every 15 seconds ──
        let lastOrderCount = {{ $orders->count() }};
        const REFRESH_INTERVAL = 15000;

        async function autoRefreshOrders() {
            if (document.hidden) return; // skip when tab not visible

            try {
                const res = await fetch(window.location.href, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) return;

                const html = await res.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Replace kanban board content
                const newBoard = doc.querySelector('.grid.grid-cols-1.md\\:grid-cols-2');
                const oldBoard = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2');
                if (newBoard && oldBoard) {
                    oldBoard.innerHTML = newBoard.innerHTML;
                    // Re-attach timer updates
                    updateTimers();
                }

                // Check for new orders and notify
                const newCountEls = doc.querySelectorAll('[data-step]');
                // Simple: count all order cards in the new HTML
                const newCards = doc.querySelectorAll('.ui-card-flat[onclick]');
                if (newCards.length > lastOrderCount) {
                    const diff = newCards.length - lastOrderCount;
                    // Show browser notification
                    if (Notification.permission === 'granted') {
                        new Notification('🔔 Pesanan Baru!', {
                            body: `${diff} pesanan baru masuk`,
                            icon: '/assets/brand/logo.png'
                        });
                    }
                    // Play notification sound
                    try {
                        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbsGckGFqIw+DfuHEoJUyAvN3nxII5MWCE0O/dq1s3OHaV3/DexnlHOm+L0/Hhz5NbR2yFyerhz5leUHGLz/Dlz5tmWXiOz/Dlz5tm');
                        audio.volume = 0.3;
                        audio.play().catch(() => {});
                    } catch(e) {}
                }
                lastOrderCount = newCards.length;

            } catch (e) {
                console.warn('Auto-refresh failed:', e);
            }
        }

        setInterval(autoRefreshOrders, REFRESH_INTERVAL);

        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    </script>
</x-admin-layout>

