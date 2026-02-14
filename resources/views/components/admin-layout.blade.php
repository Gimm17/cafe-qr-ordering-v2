<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} - Cafe Admin</title>

    @include('partials.brand-head')

    <style>
        body{ background: var(--bone); }
        .sidebar-link{ border-left: 3px solid transparent; }
        .sidebar-link.active{ background: rgba(240,245,255,.75); color: var(--primary-900); border-left-color: var(--primary-600); }
    </style>

    {{ $head ?? '' }}
</head>
<body class="text-ink min-h-screen">
    <div class="min-h-screen">

        <!-- Sidebar (desktop) -->
        <aside class="hidden lg:flex fixed top-3 bottom-3 left-3 w-72 bg-white border border-line rounded-2xl shadow-soft2 overflow-hidden z-40 flex-col">
            <div class="p-5 border-b border-line">
                <div class="flex items-center gap-3">
                    <img src="/assets/brand/logo.webp" alt="Logo" class="w-10 h-10 rounded-full ring-1 ring-line" loading="lazy">
                    <div class="leading-tight">
                        <p class="text-sm font-semibold tracking-tight">Nindito Admin</p>
                        <p class="text-xs text-muted">Coffee &amp; Friends</p>
                    </div>
                </div>
            </div>

            <nav class="p-3 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.orders') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Orders
                </a>
                <a href="{{ route('admin.products') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Produk
                </a>
                <a href="{{ route('admin.categories') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                    Kategori
                </a>
                <a href="{{ route('admin.modifiers') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('admin.modifiers*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    Modifiers
                </a>
                <a href="{{ route('admin.tables') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('admin.tables*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    Meja & QR
                </a>
                <a href="{{ route('admin.feedback') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('admin.feedback*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    Feedback
                </a>
                <a href="{{ route('admin.reports') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Laporan
                </a>
            </nav>

            <div class="p-3 border-t border-line">
                <button type="button" onclick="confirmLogout()" class="w-full tap-44 flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="lg:ml-80">
            <!-- Top Header -->
            <header class="sticky top-0 z-30 border-b border-line bg-bone/80 backdrop-blur">
                <div class="px-4 sm:px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button onclick="toggleMobileMenu()" class="lg:hidden p-2 rounded-xl hover:bg-white/60 border border-line">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <div>
                            <h2 class="text-lg sm:text-xl font-semibold tracking-tight">{{ $title ?? 'Dashboard' }}</h2>
                            <p class="text-xs text-muted">Kelola order, menu, dan laporan</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-2 ui-chip px-3 py-1.5 text-xs text-muted">
                            <span class="inline-flex w-2 h-2 rounded-full bg-primary-600"></span>
                            {{ auth()->user()->name ?? 'Admin' }}
                        </div>
                    </div>
                </div>
            </header>

            <main class="px-4 sm:px-6 py-6">
                {{ $slot }}
            </main>
        </div>

        <!-- Mobile Menu Overlay -->
        <div id="mobileMenuOverlay" class="fixed inset-0 bg-black/40 z-50 hidden lg:hidden" onclick="toggleMobileMenu()">
            <aside class="w-72 bg-white h-full shadow-soft border-r border-line" onclick="event.stopPropagation()">
                <div class="p-5 border-b border-line">
                    <div class="flex items-center gap-3">
                        <img src="/assets/brand/logo.webp" alt="Logo" class="w-10 h-10 rounded-full ring-1 ring-line" loading="lazy">
                        <div class="leading-tight">
                            <p class="text-sm font-semibold tracking-tight">Nindito Admin</p>
                            <p class="text-xs text-muted">Coffee &amp; Friends</p>
                        </div>
                    </div>
                </div>
                <nav class="p-3 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link block px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('admin.orders') }}" class="sidebar-link block px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">Orders</a>
                    <a href="{{ route('admin.products') }}" class="sidebar-link block px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.products*') ? 'active' : '' }}">Produk</a>
                    <a href="{{ route('admin.categories') }}" class="sidebar-link block px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">Kategori</a>
                    <a href="{{ route('admin.modifiers') }}" class="sidebar-link block px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.modifiers*') ? 'active' : '' }}">Modifiers</a>
                    <a href="{{ route('admin.tables') }}" class="sidebar-link block px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.tables*') ? 'active' : '' }}">Meja & QR</a>
                    <a href="{{ route('admin.feedback') }}" class="sidebar-link block px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.feedback*') ? 'active' : '' }}">Feedback</a>
                    <a href="{{ route('admin.reports') }}" class="sidebar-link block px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">Laporan</a>
                </nav>
                <div class="p-3 border-t border-line">
                    <button type="button" onclick="confirmLogout()" class="w-full tap-44 flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </div>
            </aside>
        </div>

    </div>

    <script>
        function toggleMobileMenu() {
            document.getElementById('mobileMenuOverlay').classList.toggle('hidden');
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
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
                title: options.title || 'Apakah Anda yakin?',
                text: options.text || '',
                icon: options.icon || 'warning',
                showCancelButton: true,
                confirmButtonColor: '#234AE6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: options.confirmText || 'Ya, lanjutkan',
                cancelButtonText: options.cancelText || 'Batal',
                reverseButtons: true
            });
        }

        function confirmDelete(formId, itemName = 'item ini') {
            confirmAction({
                title: 'Hapus ' + itemName + '?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                confirmText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById(formId).submit();
            });
        }

        function confirmSubmit(formId, options = {}) {
            confirmAction({
                title: options.title || 'Konfirmasi',
                text: options.text || 'Apakah Anda yakin ingin melanjutkan?',
                icon: options.icon || 'question',
                confirmText: options.confirmText || 'Ya, lanjutkan'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById(formId).submit();
            });
        }

        function confirmStatusChange(formId, status) {
            const statusLabels = {
                'PREPARING': 'Proses Pesanan',
                'READY': 'Siap Diambil',
                'COMPLETED': 'Selesai',
                'CANCELLED': 'Batalkan'
            };
            confirmAction({
                title: 'Ubah Status Pesanan?',
                text: 'Status akan diubah menjadi: ' + (statusLabels[status] || status),
                icon: status === 'CANCELLED' ? 'warning' : 'question',
                confirmText: 'Ya, ubah status'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById(formId).submit();
            });
        }

        function confirmLogout() {
            confirmAction({
                title: 'Logout?',
                text: 'Anda akan keluar dari sistem admin.',
                icon: 'question',
                confirmText: 'Ya, logout'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('logout-form').submit();
            });
        }

        function confirmRotateToken(formId, tableNo) {
            confirmAction({
                title: 'Ganti Token QR Meja ' + tableNo + '?',
                text: 'QR code lama tidak akan berfungsi lagi.',
                icon: 'warning',
                confirmText: 'Ya, ganti token'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById(formId).submit();
            });
        }

        function confirmToggleBestSeller(formId, productName, currentStatus) {
            const action = currentStatus ? 'menghapus dari' : 'menandai sebagai';
            confirmAction({
                title: 'Ubah Status Best Seller?',
                text: 'Anda akan ' + action + ' Best Seller: ' + productName,
                icon: 'question',
                confirmText: 'Ya, ubah'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById(formId).submit();
            });
        }
    </script>

    {{ $scripts ?? '' }}
</body>
</html>
