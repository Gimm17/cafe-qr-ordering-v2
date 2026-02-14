<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Nindito Coffee &amp; Friends</title>

    @include('partials.brand-head')

    <style>
        .noise{ background-image: radial-gradient(rgba(4,3,96,.07) 1px, transparent 1px); background-size: 18px 18px; }
    </style>
</head>
<body class="bg-bone text-ink min-h-screen">

    <!-- Topbar -->
    <header class="sticky top-0 z-40 border-b border-line bg-bone/80 backdrop-blur">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="/assets/brand/logo.webp" alt="Nindito" class="w-10 h-10 rounded-full ring-1 ring-line shadow-soft2" loading="eager">
                <div class="leading-tight">
                    <p class="text-sm font-semibold tracking-tight">Nindito</p>
                    <p class="text-xs text-muted">Coffee &amp; Friends</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.login') }}" class="ui-btn tap-44 inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-white border border-line text-gray-800 font-semibold">
                    Admin
                </a>
            </div>
        </div>
    </header>

    <main class="noise">
        <!-- Hero -->
        <section class="max-w-6xl mx-auto px-4 pt-8 pb-6">
            <div class="ui-card overflow-hidden">
                <div class="relative">
                    <img src="/assets/brand/hero-interior.webp" alt="Nindito" class="w-full h-64 sm:h-72 object-cover" loading="eager">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-transparent"></div>

                    <div class="absolute bottom-5 left-5 right-5 text-white">
                        <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">Pesan cepat lewat QR</h1>
                        <p class="mt-1 text-sm text-white/85">Scan QR di meja, pilih menu, dan kami antar ke meja kamu.</p>
                    </div>
                </div>

                <div class="p-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Steps -->
                        <div class="ui-card-flat p-4">
                            <p class="text-xs font-semibold text-primary-800">01</p>
                            <p class="mt-1 font-semibold">Scan QR</p>
                            <p class="mt-1 text-sm text-muted">Scan QR yang ada di meja.</p>
                        </div>
                        <div class="ui-card-flat p-4">
                            <p class="text-xs font-semibold text-primary-800">02</p>
                            <p class="mt-1 font-semibold">Pilih menu</p>
                            <p class="mt-1 text-sm text-muted">Tambah ke keranjang, atur catatan &amp; modifier.</p>
                        </div>
                        <div class="ui-card-flat p-4">
                            <p class="text-xs font-semibold text-primary-800">03</p>
                            <p class="mt-1 font-semibold">Bayar &amp; pantau</p>
                            <p class="mt-1 text-sm text-muted">Pantau status pesanan langsung dari HP.</p>
                        </div>
                    </div>

                    <!-- Quick enter by table number (useful for testing) -->
                    <div class="mt-5 ui-card-flat p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="font-semibold">Masuk via nomor meja</p>
                                <p class="text-sm text-muted">Untuk testing (tanpa scan), masukkan nomor meja.</p>
                            </div>
                            <form onsubmit="return goTable(event)" class="flex gap-2">
                                <input id="tableNo" type="number" inputmode="numeric" min="1" placeholder="No meja" class="ui-focus tap-44 w-28 px-3 rounded-xl border border-line bg-white" />
                                <button type="submit" class="ui-btn tap-44 px-4 rounded-xl bg-primary-600 text-white font-semibold shadow-soft2 hover:bg-primary-700 transition">Mulai</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories -->
        @if(isset($categories) && $categories->count())
        <section class="max-w-6xl mx-auto px-4 pb-3">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold tracking-tight">Kategori</h2>
                <span class="text-xs text-muted">Preview</span>
            </div>
            <div class="mt-3 flex gap-2 overflow-x-auto pb-2">
                @foreach($categories as $cat)
                    <span class="ui-chip px-3 py-1.5 text-xs font-semibold text-gray-800 whitespace-nowrap">{{ $cat->name }}</span>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Best Sellers -->
        @if(isset($bestSellers) && $bestSellers->count())
        <section class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold tracking-tight">Best Seller</h2>
                <span class="text-xs text-muted">Favorit pelanggan</span>
            </div>
            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($bestSellers as $p)
                    <div class="ui-card-flat overflow-hidden">
                        <div class="aspect-square bg-primary-50/40">
                            @if($p->image_url)
                                <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="w-full h-full object-cover" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-semibold line-clamp-2">{{ $p->name }}</p>
                            <p class="mt-1 text-sm font-extrabold text-primary-700">{{ $p->price_rupiah }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="mt-3 text-xs text-muted">* Untuk memesan, silakan scan QR di meja.</p>
        </section>
        @endif

        <!-- Menu Preview -->
        @if(isset($products) && $products->count())
        <section class="max-w-6xl mx-auto px-4 pb-10">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold tracking-tight">Menu Preview</h2>
                <span class="text-xs text-muted">Top picks</span>
            </div>
            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($products as $p)
                    <div class="ui-card-flat overflow-hidden">
                        <div class="aspect-square bg-primary-50/40">
                            @if($p->image_url)
                                <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="w-full h-full object-cover" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-semibold line-clamp-2">{{ $p->name }}</p>
                            <div class="mt-1 flex items-center justify-between">
                                <p class="text-sm font-extrabold text-primary-700">{{ $p->price_rupiah }}</p>
                                @if($p->category)
                                    <span class="text-[11px] text-muted">{{ $p->category->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Footer -->
        <footer class="border-t border-line bg-white/60">
            <div class="max-w-6xl mx-auto px-4 py-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <p class="text-sm font-semibold">Nindito Coffee &amp; Friends</p>
                    <p class="text-xs text-muted">Powered by QR Ordering • {{ date('Y') }}</p>
                </div>
            </div>
        </footer>
    </main>

    <script>
        function goTable(e){
            e.preventDefault();
            const el = document.getElementById('tableNo');
            const n = (el?.value || '').toString().trim();
            if(!n) return false;
            window.location.href = `/table/${encodeURIComponent(n)}`;
            return false;
        }
    </script>
</body>
</html>
