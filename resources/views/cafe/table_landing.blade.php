<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Cafe Order</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="gradient-bg min-h-screen flex flex-col items-center justify-center p-6">
    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-20 h-20 bg-emerald-500/20 rounded-full blur-xl"></div>
        <div class="absolute bottom-40 right-10 w-32 h-32 bg-purple-500/20 rounded-full blur-xl"></div>
        <div class="absolute top-1/2 left-1/4 w-24 h-24 bg-blue-500/20 rounded-full blur-xl"></div>
    </div>

    <div class="relative z-10 text-center max-w-md mx-auto">
        <!-- Logo/Icon -->
        <div class="animate-float mb-8">
            <div class="w-28 h-28 mx-auto bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-3xl flex items-center justify-center shadow-2xl">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- Welcome Text -->
        <h1 class="text-3xl font-bold text-white mb-2">Selamat Datang!</h1>
        <p class="text-gray-400 mb-8">Pesan menu favoritmu dengan mudah</p>

        <!-- Table Badge -->
        <div class="glass rounded-2xl p-8 mb-8">
            <p class="text-gray-400 text-sm uppercase tracking-wider mb-3">Nomor Meja</p>
            <div class="text-7xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400">
                {{ str_pad($tableNo, 2, '0', STR_PAD_LEFT) }}
            </div>
            <p class="text-gray-500 text-sm mt-4">Pastikan nomor meja sudah benar</p>
        </div>

        <!-- CTA Button -->
        <a href="{{ route('cafe.menu') }}" 
           class="btn-glow inline-flex items-center justify-center w-full bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold text-lg py-4 px-8 rounded-2xl transition-all duration-300 hover:from-emerald-600 hover:to-emerald-700">
            <span>Mulai Pesan</span>
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </a>

        <!-- Footer -->
        <p class="text-gray-600 text-xs mt-8">
            Powered by Cafe QR Ordering
        </p>
    </div>
</body>
</html>
