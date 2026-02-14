<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nindito - Meja {{ str_pad($tableNo, 2, '0', STR_PAD_LEFT) }}</title>

    @include('partials.brand-head')

    <style>
        /* subtle background grain */
        .noise{ background-image: radial-gradient(rgba(4,3,96,.08) 1px, transparent 1px); background-size: 18px 18px; }
    </style>
</head>
<body class="bg-bone text-ink min-h-screen">

    <div class="min-h-screen flex items-center justify-center p-6 noise">
        <div class="w-full max-w-md">
            <div class="ui-card overflow-hidden">
                <!-- Hero image -->
                <div class="relative h-44">
                    <img src="/assets/brand/hero-front.webp" alt="Nindito" class="absolute inset-0 w-full h-full object-cover" loading="eager">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/30 to-transparent"></div>

                    <div class="absolute top-4 left-4 flex items-center gap-3">
                        <img src="/assets/brand/logo.webp" alt="Nindito" class="w-12 h-12 rounded-full ring-2 ring-white/50 shadow-soft2" loading="eager">
                        <div class="text-white leading-tight">
                            <p class="text-sm font-semibold tracking-tight">Nindito</p>
                            <p class="text-xs text-white/80">Coffee &amp; Friends</p>
                        </div>
                    </div>

                    <div class="absolute bottom-4 left-4">
                        <span class="inline-flex items-center gap-2 bg-white/15 border border-white/25 text-white px-3 py-1.5 rounded-full text-xs font-semibold backdrop-blur">
                            <span class="w-2 h-2 rounded-full bg-primary-300"></span>
                            MEJA {{ str_pad($tableNo, 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <h1 class="text-xl font-semibold tracking-tight">Selamat datang 👋</h1>
                    <p class="mt-1 text-sm text-muted">Pilih menu, kirim pesanan, dan nikmati — semuanya dari HP kamu.</p>

                    <div class="mt-5 grid grid-cols-3 gap-3">
                        <div class="ui-card-flat p-3 text-center">
                            <div class="w-10 h-10 mx-auto rounded-2xl bg-primary-50 border border-line flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            </div>
                            <p class="mt-2 text-[11px] font-semibold">Pilih</p>
                        </div>
                        <div class="ui-card-flat p-3 text-center">
                            <div class="w-10 h-10 mx-auto rounded-2xl bg-primary-50 border border-line flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5"/></svg>
                            </div>
                            <p class="mt-2 text-[11px] font-semibold">Keranjang</p>
                        </div>
                        <div class="ui-card-flat p-3 text-center">
                            <div class="w-10 h-10 mx-auto rounded-2xl bg-primary-50 border border-line flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1"/></svg>
                            </div>
                            <p class="mt-2 text-[11px] font-semibold">Bayar</p>
                        </div>
                    </div>

                    <a href="{{ route('cafe.menu') }}" class="mt-6 ui-btn tap-44 inline-flex w-full items-center justify-center gap-2 bg-primary-600 text-white font-semibold rounded-2xl py-3 shadow-soft hover:bg-primary-700 transition">
                        Mulai Pesan
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>

                    <p class="mt-4 text-xs text-muted">Jika nomor meja tidak sesuai, mohon hubungi kasir.</p>
                </div>
            </div>

            <p class="mt-6 text-center text-[11px] text-muted">Powered by QR Ordering</p>
        </div>
    </div>

</body>
</html>
