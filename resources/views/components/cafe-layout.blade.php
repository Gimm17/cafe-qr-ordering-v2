<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Cafe Order' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        },
                        cafe: {
                            dark: '#1a1a2e',
                            darker: '#16162a',
                            accent: '#0f3460',
                            gold: '#e94560',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .glass-light {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .cafe-gradient {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%);
        }
        .btn-glow {
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
        }
        .btn-glow:hover {
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.6);
        }
    </style>
    {{ $head ?? '' }}
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Table Badge - Always visible -->
    @if(isset($tableNo) && $tableNo)
    <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50">
        <div class="bg-primary-600 text-white px-4 py-2 rounded-full shadow-lg flex items-center gap-2 text-sm font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            MEJA {{ $tableNo }}
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main class="pb-24 pt-16">
        {{ $slot }}
    </main>

    <!-- Bottom Navigation -->
    @if(isset($tableNo) && $tableNo)
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-2 z-40">
        <div class="max-w-lg mx-auto flex justify-around items-center">
            <a href="{{ route('cafe.menu') }}" class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('cafe.menu') ? 'text-primary-600' : 'text-gray-400' }} transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <span class="text-xs mt-1 font-medium">Menu</span>
            </a>
            <a href="{{ route('cafe.history') }}" class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('cafe.history') ? 'text-primary-600' : 'text-gray-400' }} transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span class="text-xs mt-1 font-medium">Riwayat</span>
            </a>
            <a href="{{ route('cafe.cart') }}" class="flex flex-col items-center py-2 px-4 {{ request()->routeIs('cafe.cart') ? 'text-primary-600' : 'text-gray-400' }} transition-colors relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="text-xs mt-1 font-medium">Keranjang</span>
                @php $cartCount = app(\App\Services\CartService::class)->count(); @endphp
                @if($cartCount > 0)
                <span class="absolute -top-1 right-2 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">{{ $cartCount }}</span>
                @endif
            </a>
        </div>
    </nav>
    @endif

    <script>
        // SweetAlert Toast for flash messages
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Show flash messages as toasts
        @if(session('success'))
        Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
        @endif
        @if(session('error'))
        Toast.fire({ icon: 'error', title: '{{ session('error') }}' });
        @endif

        // Confirmation helper
        function confirmAction(options) {
            return Swal.fire({
                title: options.title || 'Konfirmasi',
                text: options.text || '',
                icon: options.icon || 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#6b7280',
                confirmButtonText: options.confirmText || 'Ya',
                cancelButtonText: options.cancelText || 'Batal',
                reverseButtons: true
            });
        }

        // Clear cart confirmation
        function confirmClearCart(formId) {
            confirmAction({
                title: 'Kosongkan Keranjang?',
                text: 'Semua item di keranjang akan dihapus.',
                icon: 'warning',
                confirmText: 'Ya, kosongkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // Remove item from cart confirmation
        function confirmRemoveItem(formId, itemName) {
            confirmAction({
                title: 'Hapus dari Keranjang?',
                text: itemName + ' akan dihapus dari keranjang.',
                icon: 'warning',
                confirmText: 'Ya, hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // Checkout confirmation
        function confirmCheckout(formId) {
            confirmAction({
                title: 'Konfirmasi Pesanan?',
                text: 'Pesanan Anda akan dikirim ke dapur.',
                icon: 'question',
                confirmText: 'Ya, pesan sekarang!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
    {{ $scripts ?? '' }}
</body>
</html>

