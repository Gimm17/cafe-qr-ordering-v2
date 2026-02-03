<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Cafe Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: {
                            50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7',
                            400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Table Badge -->
    @if($tableNo)
    <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50">
        <div class="bg-primary-600 text-white px-4 py-2 rounded-full shadow-lg flex items-center gap-2 text-sm font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            MEJA {{ $tableNo }}
        </div>
    </div>
    @endif

    <main class="max-w-lg mx-auto px-4 py-20">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('cafe.menu') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span>Kembali ke Menu</span>
            </a>
        </div>

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Pesanan</h1>
            <p class="text-gray-500 text-sm mt-1">24 jam terakhir</p>
        </div>

        <!-- Flash Messages -->
        @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
            {{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">
            {{ session('success') }}
        </div>
        @endif

        <!-- Orders List -->
        @if($orders->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-gray-500">Belum ada pesanan</p>
            <a href="{{ route('cafe.menu') }}" class="mt-4 inline-block px-6 py-2 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors">
                Pesan Sekarang
            </a>
        </div>
        @else
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Order Header -->
                <div class="p-4 border-b border-gray-100">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $order->order_code }}</h3>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            @if($order->payment_status !== 'PAID')
                            <div class="text-xs text-amber-600 font-medium mt-1 countdown-timer" data-expires="{{ $order->created_at->addMinutes(10)->toISOString() }}">
                                Sisa waktu: <span class="timer-display font-bold">--:--</span>
                            </div>
                            @endif
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <!-- Payment Status Badge -->
                            @if($order->payment_status === 'PAID')
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">✓ Paid</span>
                            @else
                            <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded-full">Unpaid</span>
                            @endif
                            <!-- Order Status Badge -->
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">{{ $order->order_status }}</span>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="p-4 bg-gray-50">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center text-sm {{ !$loop->last ? 'mb-2' : '' }}">
                        <span class="text-gray-700">{{ $item->product_name }} x{{ $item->qty }}</span>
                        <span class="text-gray-500">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- Order Total & Actions -->
                <div class="p-4 border-t border-gray-100">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-gray-600">Total</span>
                        <span class="text-lg font-bold text-primary-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex gap-2">
                        <!-- View Detail -->
                        <a href="{{ route('cafe.order.show', $order) }}" class="flex-1 py-2 text-center bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                            Lihat Detail
                        </a>

                        @if($order->payment_status !== 'PAID')
                        <!-- Pay Button -->
                        <a href="{{ route('cafe.pay', $order) }}" class="flex-1 py-2 text-center bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 transition-colors">
                            Bayar
                        </a>

                        <!-- Cancel Button -->
                        <form action="{{ route('cafe.order.cancel', $order) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-100 text-red-600 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </main>

    <script>
        function updateTimers() {
            document.querySelectorAll('.countdown-timer').forEach(el => {
                const expires = new Date(el.dataset.expires).getTime();
                const now = new Date().getTime();
                const distance = expires - now;

                if (distance < 0) {
                    el.innerHTML = '<span class="text-red-500 font-bold">Expired</span>';
                    // Optional: reload page specifically if needed, but avoid infinite reload loops
                    return;
                }

                // Kalkulasi waktu
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
        
        // Update setiap detik
        setInterval(updateTimers, 1000);
        // Jalankan sekali saat load
        updateTimers();
    </script>
</body>
</html>
