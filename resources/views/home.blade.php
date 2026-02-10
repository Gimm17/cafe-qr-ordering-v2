<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Kafe') }} - Selamat Datang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="font-sans bg-gray-50 text-gray-800">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center">
                        <span class="text-white text-xl">☕</span>
                    </div>
                    <span class="font-bold text-xl text-gray-900">{{ config('app.name', 'Kafe Kita') }}</span>
                </div>
                <div class="hidden md:flex items-center gap-8">
                    <a href="#about" class="text-gray-600 hover:text-primary-600 transition-colors">Tentang</a>
                    <a href="#menu" class="text-gray-600 hover:text-primary-600 transition-colors">Menu</a>
                    <a href="#location" class="text-gray-600 hover:text-primary-600 transition-colors">Lokasi</a>
                    <a href="{{ route('admin.login') }}" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors text-sm font-medium">Admin</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient min-h-screen flex items-center pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <span class="inline-block px-4 py-1 bg-white/20 rounded-full text-sm font-medium mb-6">🌟 Welcome to Our Cafe</span>
                    <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                        Nikmati Kopi<br>
                        <span class="text-primary-300">Terbaik</span> Kami
                    </h1>
                    <p class="text-lg text-gray-200 mb-8 max-w-md">
                        Pesan langsung dari meja Anda dengan scan QR code. Tanpa antri, tanpa ribet!
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#menu" class="px-8 py-4 bg-white text-primary-700 font-semibold rounded-xl hover:bg-gray-100 transition-colors">
                            Lihat Menu
                        </a>
                        <a href="#how-to-order" class="px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition-colors">
                            Cara Pesan
                        </a>
                    </div>
                </div>
                <div class="hidden md:flex justify-center">
                    <div class="relative">
                        <div class="w-80 h-80 bg-primary-400/20 rounded-full absolute -top-10 -right-10 animate-float"></div>
                        <div class="w-64 h-64 bg-white/10 rounded-3xl backdrop-blur-sm p-8 relative z-10">
                            <div class="text-center text-white">
                                <div class="text-6xl mb-4">☕</div>
                                <p class="text-lg font-medium">Scan QR di Meja</p>
                                <p class="text-sm text-gray-300 mt-2">untuk mulai pesan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- QR Order Notice -->
    <section id="how-to-order" class="py-16 bg-gradient-to-r from-amber-50 to-orange-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-lg p-8 md:p-12 border border-amber-200">
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="w-32 h-32 bg-amber-100 rounded-2xl flex items-center justify-center qr-pulse">
                        <svg class="w-16 h-16 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                            📌 Cara Memesan
                        </h2>
                        <p class="text-gray-600 text-lg mb-4">
                            Untuk memesan, silakan <strong class="text-amber-600">scan QR code yang ada di meja Anda</strong>. 
                            Sistem kami menggunakan QR ordering untuk memastikan pesanan Anda langsung terhubung dengan meja yang tepat.
                        </p>
                        <div class="flex flex-wrap gap-4 justify-center md:justify-start mt-6">
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center font-bold">1</span>
                                Duduk di meja
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center font-bold">2</span>
                                Scan QR code
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center font-bold">3</span>
                                Pilih menu & pesan
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center font-bold">4</span>
                                Tunggu pesanan datang
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-primary-600 font-medium">Tentang Kami</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2">Kafe dengan Pengalaman Modern</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-8 rounded-2xl bg-gray-50 card-hover">
                    <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl">☕</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Kopi Premium</h3>
                    <p class="text-gray-600">Biji kopi pilihan dari petani lokal Indonesia, dipanggang dengan sempurna.</p>
                </div>
                <div class="text-center p-8 rounded-2xl bg-gray-50 card-hover">
                    <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl">📱</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Ordering Digital</h3>
                    <p class="text-gray-600">Pesan dari smartphone Anda dengan scan QR. Cepat, mudah, tanpa antri.</p>
                </div>
                <div class="text-center p-8 rounded-2xl bg-gray-50 card-hover">
                    <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl">🍃</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Suasana Nyaman</h3>
                    <p class="text-gray-600">Interior cozy dengan WiFi cepat, perfect untuk bekerja atau bersantai.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Preview Section -->
    <section id="menu" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-primary-600 font-medium">Menu Kami</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2">Pilihan Menu Favorit</h2>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Nikmati berbagai pilihan kopi, minuman, dan makanan ringan kami. Scan QR di meja untuk memesan!</p>
            </div>

            <!-- Categories -->
            @if($categories->isNotEmpty())
            <div class="flex flex-wrap justify-center gap-3 mb-12">
                @foreach($categories as $category)
                <span class="px-4 py-2 bg-white rounded-full text-sm font-medium text-gray-700 shadow-sm">{{ $category->name }}</span>
                @endforeach
            </div>
            @endif

            <!-- Best Sellers -->
            @if($bestSellers->isNotEmpty())
            <div class="mb-12">
                <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                    <span class="text-2xl">⭐</span> Best Sellers
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($bestSellers as $product)
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden card-hover">
                        <div class="aspect-square bg-gray-100 relative">
                            @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-100 to-primary-50">
                                <span class="text-5xl opacity-50">☕</span>
                            </div>
                            @endif
                            <span class="absolute top-3 left-3 px-2 py-1 bg-amber-400 text-amber-900 text-xs font-bold rounded-lg">⭐ Best</span>
                        </div>
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-900 truncate">{{ $product->name }}</h4>
                            <p class="text-primary-600 font-bold mt-1">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- All Products -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($products->whereNotIn('id', $bestSellers->pluck('id')) as $product)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden card-hover">
                    <div class="aspect-square bg-gray-100 relative">
                        @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-50">
                            <span class="text-5xl opacity-30">☕</span>
                        </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h4 class="font-semibold text-gray-900 truncate">{{ $product->name }}</h4>
                        <p class="text-primary-600 font-bold mt-1">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- CTA -->
            <div class="text-center mt-12">
                <div class="inline-block p-6 bg-white rounded-2xl shadow-lg">
                    <p class="text-gray-600 mb-4">Mau pesan? Scan QR di meja kamu!</p>
                    <div class="flex items-center justify-center gap-2 text-primary-600 font-semibold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        Scan QR → Pilih Menu → Pesan
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Location Section -->
    <section id="location" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-primary-600 font-medium">Lokasi & Kontak</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2 mb-6">Kunjungi Kami</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-xl">📍</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Alamat</h4>
                                <p class="text-gray-600">Jl. Kopi Nikmat No. 123<br>Jakarta Selatan, 12345</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-xl">🕐</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Jam Operasional</h4>
                                <p class="text-gray-600">Senin - Jumat: 08:00 - 22:00<br>Sabtu - Minggu: 09:00 - 23:00</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-xl">📞</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Kontak</h4>
                                <p class="text-gray-600">WhatsApp: +62 812-3456-7890<br>Email: hello@kafekita.id</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-200 rounded-2xl h-80 flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <span class="text-4xl">🗺️</span>
                        <p class="mt-2">Google Maps</p>
                        <p class="text-sm">(Embed peta di sini)</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center">
                        <span class="text-white text-xl">☕</span>
                    </div>
                    <span class="font-bold text-xl">{{ config('app.name', 'Kafe Kita') }}</span>
                </div>
                <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} {{ config('app.name', 'Kafe Kita') }}. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors">📷</a>
                    <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors">📘</a>
                    <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors">🐦</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
