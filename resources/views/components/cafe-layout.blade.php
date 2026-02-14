<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Cafe Order' }}</title>

    @include('partials.brand-head')

    {{ $head ?? '' }}
</head>
<body class="bg-bone text-ink min-h-screen">

    <!-- Premium Top Bar -->
    <header class="fixed top-0 left-0 right-0 z-50 border-b border-line bg-bone/80 backdrop-blur">
        <div class="max-w-lg mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="/assets/brand/logo.webp" alt="Nindito" class="w-10 h-10 rounded-full ring-1 ring-line shadow-soft2" loading="lazy">
                <div class="leading-tight">
                    <p class="text-sm font-semibold tracking-tight">Nindito</p>
                    <p class="text-xs text-muted">Coffee &amp; Friends</p>
                </div>
            </div>

            @if(isset($tableNo) && $tableNo)
            <div class="ui-chip px-3 py-1.5 text-xs font-semibold flex items-center gap-2 text-primary-900">
                <span class="inline-flex w-2 h-2 rounded-full bg-primary-600"></span>
                MEJA {{ $tableNo }}
            </div>
            @endif
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-20 pb-28">
        {{ $slot }}
    </main>

    <!-- Bottom Navigation -->
    @if(isset($tableNo) && $tableNo)
    <nav class="fixed bottom-0 left-0 right-0 z-50 border-t border-line bg-white/85 backdrop-blur">
        <div class="max-w-lg mx-auto px-4 py-2">
            <div class="flex justify-around items-center">
                <a href="{{ route('cafe.menu') }}" class="flex flex-col items-center px-4 py-2 tap-44 {{ request()->routeIs('cafe.menu') ? 'text-primary-700' : 'text-gray-500' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <span class="text-[11px] mt-1 font-medium">Menu</span>
                </a>

                <a href="{{ route('cafe.history') }}" class="flex flex-col items-center px-4 py-2 tap-44 {{ request()->routeIs('cafe.history') ? 'text-primary-700' : 'text-gray-500' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="text-[11px] mt-1 font-medium">Riwayat</span>
                </a>

                <a href="{{ route('cafe.cart') }}" class="flex flex-col items-center px-4 py-2 tap-44 {{ request()->routeIs('cafe.cart') ? 'text-primary-700' : 'text-gray-500' }} relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="text-[11px] mt-1 font-medium">Keranjang</span>
                    @php $cartCount = app(\App\Services\CartService::class)->count(); @endphp
                    @if($cartCount > 0)
                    <span class="absolute -top-1 right-3 bg-primary-600 text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold ring-2 ring-white">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>
    </nav>
    @endif

    <script>
        // SweetAlert Toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 2800,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
        Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
        @endif
        @if(session('error'))
        Toast.fire({ icon: 'error', title: '{{ session('error') }}' });
        @endif

        function confirmAction(options) {
            return Swal.fire({
                title: options.title || 'Konfirmasi',
                text: options.text || '',
                icon: options.icon || 'question',
                showCancelButton: true,
                confirmButtonColor: '#234AE6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: options.confirmText || 'Ya',
                cancelButtonText: options.cancelText || 'Batal',
                reverseButtons: true
            });
        }

        function confirmClearCart(formId) {
            confirmAction({
                title: 'Kosongkan Keranjang?',
                text: 'Semua item di keranjang akan dihapus.',
                icon: 'warning',
                confirmText: 'Ya, kosongkan'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById(formId).submit();
            });
        }

        function confirmRemoveItem(formId, itemName) {
            confirmAction({
                title: 'Hapus dari Keranjang?',
                text: itemName + ' akan dihapus dari keranjang.',
                icon: 'warning',
                confirmText: 'Ya, hapus'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById(formId).submit();
            });
        }

        function confirmCheckout(formId) {
            confirmAction({
                title: 'Konfirmasi Pesanan?',
                text: 'Pesanan Anda akan dikirim ke dapur.',
                icon: 'question',
                confirmText: 'Ya, pesan sekarang!'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById(formId).submit();
            });
        }
    </script>

    {{ $scripts ?? '' }}
</body>
</html>
