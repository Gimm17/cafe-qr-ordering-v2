<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Nindito Coffee &amp; Friends</title>

    @include('partials.brand-head')

    <style>
        .noise{ background-image: radial-gradient(rgba(4,3,96,.07) 1px, transparent 1px); background-size: 18px 18px; }

        /* ── Animations ── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes shimmer {
            0%   { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%      { transform: translateY(-6px); }
        }

        .anim-fade-up {
            opacity: 0;
            animation: fadeInUp .6s ease-out forwards;
        }
        .anim-fade {
            opacity: 0;
            animation: fadeIn .5s ease-out forwards;
        }

        /* Stagger delays */
        .delay-1 { animation-delay: .1s; }
        .delay-2 { animation-delay: .2s; }
        .delay-3 { animation-delay: .3s; }
        .delay-4 { animation-delay: .4s; }
        .delay-5 { animation-delay: .5s; }
        .delay-6 { animation-delay: .6s; }
        .delay-7 { animation-delay: .7s; }
        .delay-8 { animation-delay: .8s; }

        /* Product card hover */
        .product-card {
            transition: transform .3s cubic-bezier(.22,1,.36,1), box-shadow .3s ease;
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(4,3,96,.14);
        }
        .product-card img {
            transition: transform .5s cubic-bezier(.22,1,.36,1);
        }
        .product-card:hover img {
            transform: scale(1.08);
        }
        .product-card .card-overlay {
            opacity: 0;
            transition: opacity .3s ease;
        }
        .product-card:hover .card-overlay {
            opacity: 1;
        }

        /* Category chip hover */
        .cat-chip {
            transition: all .25s ease;
        }
        .cat-chip:hover {
            background: rgba(35,74,230,.08);
            border-color: rgba(35,74,230,.3);
            transform: translateY(-2px);
        }

        /* Hero shimmer text */
        .hero-shimmer {
            background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,.3) 50%, rgba(255,255,255,0) 100%);
            background-size: 200% 100%;
            -webkit-background-clip: text;
            animation: shimmer 3s ease-in-out infinite;
        }

        /* Step card */
        .step-card {
            transition: all .3s ease;
            position: relative;
            overflow: hidden;
        }
        .step-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #234AE6, #5B84FF);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .4s ease;
        }
        .step-card:hover::before {
            transform: scaleX(1);
        }
        .step-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(4,3,96,.12);
        }

        /* Badge pulse */
        .badge-fire {
            animation: float 3s ease-in-out infinite;
        }

        /* Star rating */
        .star-gold { color: #F59E0B; }
        .star-gray { color: #D1D5DB; }

        /* Scroll reveal */
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity .6s ease, transform .6s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
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
                <a href="{{ route('admin.login') }}" class="ui-btn tap-44 inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-white border border-line text-gray-800 font-semibold hover:bg-gray-50 transition-colors">
                    Admin
                </a>
            </div>
        </div>
    </header>

    <main class="noise">
        <!-- Hero -->
        <section class="max-w-6xl mx-auto px-4 pt-8 pb-6 anim-fade-up">
            <div class="ui-card overflow-hidden">
                <div class="relative">
                    <img src="/assets/brand/hero-interior.webp" alt="Nindito" class="w-full h-64 sm:h-72 object-cover" loading="eager">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

                    <div class="absolute bottom-5 left-5 right-5 text-white">
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Pesan cepat lewat QR</h1>
                        <p class="mt-1 text-sm text-white/85 hero-shimmer">Scan QR di meja, pilih menu, dan kami antar ke meja kamu.</p>
                    </div>
                </div>

                <div class="p-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Steps -->
                        <div class="step-card ui-card-flat p-4 rounded-2xl anim-fade-up delay-1">
                            <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center mb-2">
                                <span class="text-xs font-bold text-primary-700">01</span>
                            </div>
                            <p class="font-semibold">Scan QR</p>
                            <p class="mt-1 text-sm text-muted">Scan QR yang ada di meja.</p>
                        </div>
                        <div class="step-card ui-card-flat p-4 rounded-2xl anim-fade-up delay-2">
                            <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center mb-2">
                                <span class="text-xs font-bold text-primary-700">02</span>
                            </div>
                            <p class="font-semibold">Pilih menu</p>
                            <p class="mt-1 text-sm text-muted">Tambah ke keranjang, atur catatan &amp; modifier.</p>
                        </div>
                        <div class="step-card ui-card-flat p-4 rounded-2xl anim-fade-up delay-3">
                            <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center mb-2">
                                <span class="text-xs font-bold text-primary-700">03</span>
                            </div>
                            <p class="font-semibold">Bayar &amp; pantau</p>
                            <p class="mt-1 text-sm text-muted">Pantau status pesanan langsung dari HP.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories -->
        @if(isset($categories) && $categories->count())
        <section class="max-w-6xl mx-auto px-4 pb-3 anim-fade-up delay-2">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold tracking-tight">Kategori</h2>
                <span class="text-xs text-muted">Preview</span>
            </div>
            <div class="mt-3 flex gap-2 overflow-x-auto pb-2">
                @foreach($categories as $idx => $cat)
                    <span class="cat-chip ui-chip px-4 py-2 text-xs font-semibold text-gray-800 whitespace-nowrap cursor-default">{{ $cat->name }}</span>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Best Sellers -->
        @if(isset($bestSellers) && $bestSellers->count())
        <section class="max-w-6xl mx-auto px-4 py-6 reveal">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">🔥</span>
                    <h2 class="text-lg font-bold tracking-tight">Best Seller</h2>
                </div>
                <span class="text-xs text-muted">Favorit pelanggan</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($bestSellers as $idx => $p)
                    <div class="product-card ui-card-flat overflow-hidden rounded-2xl cursor-pointer" onclick="openModal({{ $p->id }})">
                        <div class="aspect-square bg-primary-50/40 relative overflow-hidden">
                            @if($p->image_url)
                                <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="w-full h-full object-cover" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/></svg>
                                </div>
                            @endif

                            <!-- Hover overlay with quick info -->
                            <div class="card-overlay absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-3">
                                <span class="text-white text-xs font-medium">Lihat detail →</span>
                            </div>

                            <!-- Order count badge -->
                            @if(($p->total_ordered ?? 0) > 0)
                            <div class="absolute top-2 right-2">
                                <span class="badge-fire inline-flex items-center gap-1 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-lg text-[11px] font-bold text-amber-700 shadow-sm">
                                    🔥 {{ $p->total_ordered }}x
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-semibold line-clamp-2">{{ $p->name }}</p>
                            <!-- Rating -->
                            @if(($p->reviews_count ?? 0) > 0)
                            <div class="flex items-center gap-1 mt-1">
                                <svg class="w-3.5 h-3.5 star-gold" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-xs font-semibold text-gray-700">{{ number_format($p->reviews_avg_rating ?? 0, 1) }}</span>
                                <span class="text-[10px] text-muted">({{ $p->reviews_count }})</span>
                            </div>
                            @endif
                            <p class="mt-1 text-sm font-extrabold text-primary-700">{{ $p->price_rupiah }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="mt-4 text-xs text-muted text-center">* Untuk memesan, silakan scan QR di meja.</p>
        </section>
        @endif

        <!-- Menu Preview -->
        @if(isset($products) && $products->count())
        <section class="max-w-6xl mx-auto px-4 pb-10 reveal">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">☕</span>
                    <h2 class="text-lg font-bold tracking-tight">Menu</h2>
                </div>
                <span class="text-xs text-muted">Semua menu</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($products as $idx => $p)
                    <div class="product-card ui-card-flat overflow-hidden rounded-2xl cursor-pointer" onclick="openModal({{ $p->id }})">
                        <div class="aspect-square bg-primary-50/40 relative overflow-hidden">
                            @if($p->image_url)
                                <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="w-full h-full object-cover" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/></svg>
                                </div>
                            @endif

                            <!-- Hover overlay -->
                            <div class="card-overlay absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-3">
                                <span class="text-white text-xs font-medium">Lihat detail →</span>
                            </div>

                            <!-- Order count & category badges -->
                            <div class="absolute top-2 left-2 right-2 flex justify-between items-start">
                                @if($p->category)
                                    <span class="bg-white/90 backdrop-blur-sm px-2 py-0.5 rounded-lg text-[10px] font-bold text-gray-600">{{ $p->category->name }}</span>
                                @else
                                    <span></span>
                                @endif
                                @if(($p->total_ordered ?? 0) > 0)
                                    <span class="bg-white/90 backdrop-blur-sm px-2 py-0.5 rounded-lg text-[10px] font-bold text-amber-700">🔥 {{ $p->total_ordered }}x</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-semibold line-clamp-2">{{ $p->name }}</p>
                            <!-- Rating -->
                            @if(($p->reviews_count ?? 0) > 0)
                            <div class="flex items-center gap-1 mt-1">
                                <svg class="w-3.5 h-3.5 star-gold" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-xs font-semibold text-gray-700">{{ number_format($p->reviews_avg_rating ?? 0, 1) }}</span>
                                <span class="text-[10px] text-muted">({{ $p->reviews_count }})</span>
                            </div>
                            @endif
                            <p class="mt-1 text-sm font-extrabold text-primary-700">{{ $p->price_rupiah }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Footer -->
        <footer class="border-t border-line bg-gradient-to-b from-white/60 to-white/90">
            <div class="max-w-6xl mx-auto px-4 py-10">
                <div class="flex flex-col items-center text-center gap-3">
                    <img src="/assets/brand/logo.webp" alt="Nindito" class="w-12 h-12 rounded-full ring-1 ring-line shadow-soft2">
                    <p class="text-sm font-bold tracking-tight">Nindito Coffee &amp; Friends</p>
                    <p class="text-xs text-muted">Powered by QR Ordering • {{ date('Y') }}</p>
                </div>
            </div>
        </footer>
    </main>

    <!-- Product Detail Modal -->
    <div id="productModal" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center" style="display:none;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="relative bg-white w-full max-w-lg max-h-[85vh] overflow-y-auto rounded-t-3xl sm:rounded-3xl shadow-2xl transform transition-all duration-300" id="modalContent">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white/95 backdrop-blur-sm z-10 p-4 border-b border-line flex items-center justify-between rounded-t-3xl">
                <h3 class="font-bold text-lg tracking-tight" id="modalTitle">Produk</h3>
                <button onclick="closeModal()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div id="modalBody" class="p-5">
                <!-- Filled by JS -->
            </div>
        </div>
    </div>

    @php
        $mapProduct = function($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price_rupiah,
                'image' => $p->image_url,
                'category' => $p->category?->name ?? null,
                'total_ordered' => $p->total_ordered ?? 0,
                'avg_rating' => round($p->reviews_avg_rating ?? 0, 1),
                'reviews_count' => $p->reviews_count ?? 0,
                'description' => $p->description,
                'reviews' => $p->reviews->map(function($r) {
                    return [
                        'rating' => $r->rating,
                        'comment' => $r->comment,
                        'date' => $r->created_at->diffForHumans(),
                    ];
                })->values(),
            ];
        };
        $productsJson = $products->map($mapProduct)->keyBy('id');
        $bestSellersJson = $bestSellers->map($mapProduct)->keyBy('id');
        $allProductsJson = $productsJson->union($bestSellersJson);
    @endphp

    <!-- Product data for modal -->
    <script>
        const allProducts = {!! json_encode($allProductsJson, JSON_HEX_TAG) !!};

        function openModal(productId) {
            const p = allProducts[productId];
            if (!p) return;

            const modal = document.getElementById('productModal');
            const body = document.getElementById('modalBody');
            const title = document.getElementById('modalTitle');

            title.textContent = p.name;

            let html = '';

            // Image
            if (p.image) {
                html += `<div class="rounded-2xl overflow-hidden mb-4 border border-line">
                    <img src="${p.image}" alt="${p.name}" class="w-full aspect-[4/3] object-cover">
                </div>`;
            }

            // Price + Stats
            html += `<div class="flex items-center justify-between mb-3">
                <span class="text-xl font-extrabold text-primary-700">${p.price}</span>
                <div class="flex items-center gap-3">`;

            if (p.total_ordered > 0) {
                html += `<span class="text-xs font-bold text-amber-700 bg-amber-50 px-2 py-1 rounded-lg">🔥 ${p.total_ordered}x dipesan</span>`;
            }
            html += `</div></div>`;

            // Description
            if (p.description) {
                html += `<p class="text-sm text-muted mb-4">${p.description}</p>`;
            }

            // Average Rating
            if (p.reviews_count > 0) {
                html += `<div class="bg-primary-50/60 rounded-2xl p-4 mb-4 border border-primary-100">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl font-extrabold text-gray-900">${p.avg_rating}</span>
                        <div>
                            <div class="flex items-center gap-0.5">`;
                for (let i = 1; i <= 5; i++) {
                    html += `<svg class="w-4 h-4 ${i <= Math.round(p.avg_rating) ? 'text-amber-400' : 'text-gray-300'}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>`;
                }
                html += `</div>
                            <p class="text-xs text-muted mt-0.5">${p.reviews_count} ulasan</p>
                        </div>
                    </div>
                </div>`;

                // Individual reviews
                html += `<div class="space-y-3">
                    <h4 class="font-semibold text-sm tracking-tight">Ulasan Pelanggan</h4>`;

                p.reviews.forEach(r => {
                    html += `<div class="bg-gray-50 rounded-xl p-3 border border-line">
                        <div class="flex items-center gap-1 mb-1">`;
                    for (let i = 1; i <= 5; i++) {
                        html += `<svg class="w-3.5 h-3.5 ${i <= r.rating ? 'text-amber-400' : 'text-gray-300'}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>`;
                    }
                    html += `<span class="text-[10px] text-muted ml-1">${r.date}</span>
                        </div>`;
                    if (r.comment) {
                        html += `<p class="text-sm text-gray-700">"${r.comment}"</p>`;
                    } else {
                        html += `<p class="text-xs text-gray-400 italic">Tanpa komentar</p>`;
                    }
                    html += `</div>`;
                });

                html += `</div>`;
            } else {
                html += `<div class="text-center py-6">
                    <p class="text-muted text-sm">Belum ada ulasan untuk produk ini.</p>
                </div>`;
            }

            // Scan QR note
            html += `<div class="mt-5 p-3 bg-gray-50 rounded-xl border border-line text-center">
                <p class="text-xs text-muted">📱 Scan QR di meja untuk memesan produk ini</p>
            </div>`;

            body.innerHTML = html;

            // Show modal with animation
            modal.style.display = 'flex';
            const content = document.getElementById('modalContent');
            content.style.transform = 'translateY(100%)';
            content.style.opacity = '0';
            requestAnimationFrame(() => {
                content.style.transform = 'translateY(0)';
                content.style.opacity = '1';
            });
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('productModal');
            const content = document.getElementById('modalContent');
            content.style.transform = 'translateY(100%)';
            content.style.opacity = '0';
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }, 300);
        }

        // Close on Escape key
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });

        // Scroll reveal animation
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        // Stagger children
                        const cards = entry.target.querySelectorAll('.product-card');
                        cards.forEach((card, i) => {
                            card.style.opacity = '0';
                            card.style.transform = 'translateY(20px)';
                            card.style.transition = `opacity .5s ease ${i * .08}s, transform .5s ease ${i * .08}s`;
                            setTimeout(() => {
                                card.style.opacity = '1';
                                card.style.transform = 'translateY(0)';
                            }, 50);
                        });
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
        });
    </script>
</body>
</html>

