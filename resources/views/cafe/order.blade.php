<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan {{ $order->order_code }} - Cafe Order</title>
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
        .status-step.active .status-dot { background-color: #10b981; }
        .status-step.active .status-line { background-color: #10b981; }
        .status-step.completed .status-dot { background-color: #10b981; }
        .status-step.completed .status-line { background-color: #10b981; }
        .pulse-dot { animation: pulse 2s infinite; }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Table Badge -->
    @if($order->table)
    <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50">
        <div class="bg-primary-600 text-white px-4 py-2 rounded-full shadow-lg flex items-center gap-2 text-sm font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            MEJA {{ $order->table->table_no }}
        </div>
    </div>
    @endif

    <main class="max-w-lg mx-auto px-4 py-20">
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
        <!-- Order Code Header -->
        <div class="text-center mb-8">
            <p class="text-gray-500 text-sm mb-1">Nomor Pesanan</p>
            <h1 class="text-2xl font-bold text-gray-800">{{ $order->order_code }}</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $order->customer_name }}</p>
        </div>

        <!-- Payment Status -->
        @if($order->payment_status !== 'PAID')
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-amber-800">Menunggu Pembayaran</p>
                    <p class="text-sm text-amber-600">Silakan selesaikan pembayaran</p>
                </div>
            </div>
            <a href="{{ route('cafe.pay', $order->order_code) }}" 
               class="block w-full mt-4 py-3 bg-amber-500 text-white text-center font-semibold rounded-xl hover:bg-amber-600 transition-colors">
                Bayar Sekarang
            </a>
        </div>
        @else
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-green-800">Pembayaran Berhasil</p>
                    <p class="text-sm text-green-600">Pesanan sedang diproses</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Order Status Timeline -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
            <h2 class="font-semibold text-gray-800 mb-6">Status Pesanan</h2>
            
            @php
                $statuses = ['DITERIMA', 'DIPROSES', 'READY', 'SELESAI'];
                $currentIndex = array_search($order->order_status, $statuses);
                if ($currentIndex === false) $currentIndex = -1;
            @endphp
            
            <div class="relative">
                @foreach($statuses as $index => $status)
                @php
                    $isCompleted = $index < $currentIndex;
                    $isActive = $index == $currentIndex;
                    $isPending = $index > $currentIndex;
                @endphp
                <div class="status-step flex items-start gap-4 {{ $isCompleted ? 'completed' : '' }} {{ $isActive ? 'active' : '' }}">
                    <!-- Dot and Line -->
                    <div class="flex flex-col items-center">
                        <div class="status-dot w-4 h-4 rounded-full {{ $isCompleted || $isActive ? 'bg-primary-500' : 'bg-gray-300' }} {{ $isActive ? 'pulse-dot ring-4 ring-primary-100' : '' }}"></div>
                        @if(!$loop->last)
                        <div class="status-line w-0.5 h-12 {{ $isCompleted ? 'bg-primary-500' : 'bg-gray-200' }}"></div>
                        @endif
                    </div>
                    
                    <!-- Status Text -->
                    <div class="pb-8">
                        <p class="font-semibold {{ $isActive ? 'text-primary-600' : ($isCompleted ? 'text-gray-800' : 'text-gray-400') }}">
                            @switch($status)
                                @case('DITERIMA') Pesanan Diterima @break
                                @case('DIPROSES') Sedang Diproses @break
                                @case('READY') Siap Diambil/Diantar @break
                                @case('SELESAI') Selesai @break
                            @endswitch
                        </p>
                        <p class="text-sm {{ $isActive ? 'text-primary-500' : ($isCompleted ? 'text-gray-500' : 'text-gray-300') }}">
                            @switch($status)
                                @case('DITERIMA') Pesanan masuk ke sistem @break
                                @case('DIPROSES') Barista sedang menyiapkan @break
                                @case('READY') {{ $order->fulfillment_type == 'PICKUP' ? 'Silakan ambil di kasir' : 'Pesanan akan diantar ke meja' }} @break
                                @case('SELESAI') Terima kasih! @break
                            @endswitch
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
            <h2 class="font-semibold text-gray-800 mb-4">Detail Pesanan</h2>
            
            <div class="space-y-3">
                @foreach($order->items as $item)
                <div class="flex justify-between items-start text-sm">
                    <div class="flex-1">
                        <span class="text-gray-800">{{ $item->product_name }}</span>
                        <span class="text-gray-500">x{{ $item->qty }}</span>
                        @if($item->mods->isNotEmpty())
                        <p class="text-xs text-gray-400">{{ $item->mods_summary }}</p>
                        @endif
                        @if($item->note)
                        <p class="text-xs text-amber-600">📝 {{ $item->note }}</p>
                        @endif
                    </div>
                    <span class="text-gray-800 font-medium">Rp {{ number_format($item->line_total, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
            
            <div class="pt-4 mt-4 border-t border-gray-200 flex justify-between items-center">
                <span class="font-semibold text-gray-800">Total</span>
                <span class="text-xl font-bold text-primary-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Feedback Form (Only show after SELESAI) -->
        @if($order->order_status === 'SELESAI' && !$order->feedback)
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
            <h2 class="font-semibold text-gray-800 mb-4">Bagaimana pengalaman Anda?</h2>
            
            <form action="{{ route('cafe.order.feedback', $order->order_code) }}" method="POST">
                @csrf
                
                <!-- Star Rating -->
                <div class="flex justify-center gap-2 mb-4">
                    @for($i = 1; $i <= 5; $i++)
                    <label class="cursor-pointer">
                        <input type="radio" name="rating" value="{{ $i }}" class="hidden peer">
                        <svg class="w-10 h-10 text-gray-300 peer-checked:text-amber-400 hover:text-amber-300 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </label>
                    @endfor
                </div>
                
                <!-- Comment -->
                <textarea name="comment" 
                          rows="3" 
                          placeholder="Tuliskan komentar Anda (opsional)"
                          class="w-full px-4 py-3 bg-gray-50 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:bg-white transition-all outline-none text-gray-700 resize-none mb-4"></textarea>
                
                <button type="submit" class="w-full py-3 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                    Kirim Feedback
                </button>
            </form>
        </div>
        @elseif($order->feedback)
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6 text-center">
            <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-green-800 font-semibold">Terima kasih atas feedback Anda!</p>
        </div>
        @endif

        <!-- Back to Menu -->
        <a href="{{ route('cafe.menu') }}" class="block w-full py-3 bg-gray-100 text-gray-700 text-center font-semibold rounded-xl hover:bg-gray-200 transition-colors">
            Pesan Lagi
        </a>
    </main>

    <!-- Auto-refresh for status updates -->
    @endif

    <script>
        function updateTimers() {
            document.querySelectorAll('.countdown-timer').forEach(el => {
                const expires = new Date(el.dataset.expires).getTime();
                const now = new Date().getTime();
                const distance = expires - now;

                if (distance < 0) {
                    el.innerHTML = '<span class="text-red-500 font-bold block text-center mb-2">Expired</span>';
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

        @if($order->order_status !== 'SELESAI')
        // Poll for status updates every 10 seconds
        setInterval(async () => {
            try {
                const response = await fetch('{{ route("cafe.order.status", $order->order_code) }}');
                const data = await response.json();
                
                if (data.order_status !== '{{ $order->order_status }}' || data.payment_status !== '{{ $order->payment_status }}') {
                    window.location.reload();
                }
            } catch (e) {
                console.log('Status check failed');
            }
        }, 10000);
        @endif
    </script>
</body>
</html>
