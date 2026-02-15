<x-admin-layout title="Settings">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pengaturan</h1>
        <p class="text-gray-600">Sesuaikan preferensi aplikasi</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Cafe Open/Close Toggle --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:col-span-2">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                🏪 Status Cafe
            </h2>

            <div class="flex items-center justify-between gap-4 {{ $cafeIsOpen ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }} border rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">{{ $cafeIsOpen ? '🟢' : '🔴' }}</span>
                    <div>
                        <p class="font-bold text-lg {{ $cafeIsOpen ? 'text-green-800' : 'text-red-800' }}">
                            Cafe {{ $cafeIsOpen ? 'BUKA' : 'TUTUP' }}
                        </p>
                        <p class="text-sm {{ $cafeIsOpen ? 'text-green-600' : 'text-red-600' }}">
                            {{ $cafeIsOpen ? 'Pelanggan bisa memesan saat ini' : 'Semua pesanan diblokir - pelanggan melihat "Cafe Tutup"' }}
                        </p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.settings.toggle-cafe') }}">
                    @csrf
                    <button type="submit"
                        class="tap-44 px-6 py-3 rounded-xl font-bold text-sm transition-colors {{ $cafeIsOpen ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}"
                        onclick="return confirm('{{ $cafeIsOpen ? 'Tutup cafe sekarang? Pelanggan tidak bisa memesan.' : 'Buka cafe sekarang?' }}')">
                        {{ $cafeIsOpen ? '🔒 Tutup Cafe' : '🔓 Buka Cafe' }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Close Order Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                ⏰ Close Order per Kategori
            </h2>

            <p class="text-sm text-gray-600 mb-4">
                Atur jam close order untuk setiap kategori. Produk yang kategorinya sudah melewati jam close order akan tampil abu-abu dan tidak bisa dipesan.
            </p>

            <form action="{{ route('admin.settings.close-order') }}" method="POST" class="space-y-4">
                @csrf

                <div class="space-y-3">
                    @forelse($categories as $category)
                    <div class="flex items-center justify-between gap-3 bg-gray-50 rounded-lg p-3">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 text-sm truncate">{{ $category->name }}</p>
                            @if($category->close_order_time)
                                @if($category->isClosedOrder())
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-red-600 mt-1">
                                    🚫 Sedang Close Order
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-green-600 mt-1">
                                    ✅ Buka (tutup {{ \Carbon\Carbon::parse($category->close_order_time)->format('H:i') }})
                                </span>
                                @endif
                            @else
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-gray-500 mt-1">
                                ♾️ Tanpa batas waktu
                            </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="time" 
                                   name="close_order_time[{{ $category->id }}]" 
                                   value="{{ $category->close_order_time ? \Carbon\Carbon::parse($category->close_order_time)->format('H:i') : '' }}"
                                   class="tap-44 rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-32"
                                   placeholder="HH:MM">
                            @if($category->close_order_time)
                            <button type="button" 
                                    onclick="this.parentElement.querySelector('input[type=time]').value=''"
                                    class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors" 
                                    title="Hapus close order">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6 text-gray-400 text-sm">
                        Belum ada kategori aktif.
                    </div>
                    @endforelse
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                    <p class="text-xs text-amber-700">
                        <strong>💡 Tips:</strong> Kosongkan jam = selalu tersedia selama cafe buka. Jam setelah tengah malam (misal 02:00) berarti close order jam 2 pagi. Close order berlaku otomatis, tidak perlu dimatikan manual.
                    </p>
                </div>

                @if($categories->isNotEmpty())
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition-colors">
                        Simpan Close Order
                    </button>
                </div>
                @endif
            </form>
        </div>

        {{-- Notification Sound Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                🔊 Suara Notifikasi Pesanan
            </h2>
            
            <p class="text-sm text-gray-600 mb-4">
                Upload file audio (MP3/WAV) untuk mengganti suara notifikasi saat ada pesanan baru masuk.
            </p>

            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Preview Saat Ini</label>
                <audio controls class="w-full">
                    <source src="{{ $soundUrl }}?t={{ time() }}" type="audio/mpeg">
                    Browser Anda tidak mendukung elemen audio.
                </audio>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Suara</label>
                    <input type="file" name="notification_sound" accept=".mp3,.wav" 
                        class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2.5 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-700
                        hover:file:bg-indigo-100
                        cursor-pointer border border-gray-300 rounded-lg bg-white focus:outline-none"
                        required>
                    <p class="mt-1 text-xs text-gray-500">Maksimal 2MB. Format: MP3, WAV.</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Receipt Customization Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:col-span-2">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                🧾 Kustomisasi Struk
            </h2>

            <p class="text-sm text-gray-600 mb-5">
                Sesuaikan tampilan struk pembelian — logo, lokasi, pesan, tema, dan elemen yang ditampilkan.
            </p>

            <form action="{{ route('admin.settings.receipt') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Logo Section --}}
                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Logo Cafe</label>

                    @if($receiptLogo)
                    <div class="flex items-center gap-4">
                        <img src="{{ asset($receiptLogo) }}?t={{ time() }}" alt="Logo" class="w-16 h-16 object-contain rounded-lg border bg-white p-1">
                        <div class="flex-1">
                            <p class="text-sm text-gray-700 font-medium">Logo saat ini</p>
                            <label class="inline-flex items-center gap-2 mt-1">
                                <input type="checkbox" name="remove_logo" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <span class="text-xs text-red-600">Hapus logo</span>
                            </label>
                        </div>
                    </div>
                    @endif

                    <div>
                        <input type="file" name="receipt_logo" accept=".png,.jpg,.jpeg,.webp"
                            class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100
                            cursor-pointer border border-gray-300 rounded-lg bg-white">
                        <p class="mt-1 text-xs text-gray-500">Maks 1MB. Format: PNG, JPG, WEBP.</p>
                    </div>

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="receipt_show_logo" value="1" {{ $receiptShowLogo === '1' ? 'checked' : '' }}
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">Tampilkan logo di struk</span>
                    </label>
                </div>

                {{-- Location --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi / Alamat Cafe</label>
                    <textarea name="receipt_cafe_location" rows="2" maxlength="500"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Jl. Contoh No. 123, Kota, Provinsi">{{ $receiptCafeLocation }}</textarea>
                </div>

                {{-- Footer Text --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pesan Footer Struk</label>
                    <input type="text" name="receipt_footer_text" maxlength="200" value="{{ $receiptFooterText }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Terima kasih! 🙏">
                </div>

                {{-- Theme --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tema Struk</label>
                    <div class="flex gap-3">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="receipt_theme" value="normal" {{ $receiptTheme === 'normal' ? 'checked' : '' }} class="peer sr-only">
                            <div class="peer-checked:ring-2 peer-checked:ring-indigo-500 peer-checked:border-indigo-500 border border-gray-200 rounded-xl p-4 text-center transition-all hover:bg-gray-50">
                                <div class="text-2xl mb-1">🎨</div>
                                <div class="text-sm font-semibold text-gray-900">Normal</div>
                                <div class="text-xs text-gray-500">Warna & badge</div>
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="receipt_theme" value="bw" {{ $receiptTheme === 'bw' ? 'checked' : '' }} class="peer sr-only">
                            <div class="peer-checked:ring-2 peer-checked:ring-indigo-500 peer-checked:border-indigo-500 border border-gray-200 rounded-xl p-4 text-center transition-all hover:bg-gray-50">
                                <div class="text-2xl mb-1">🖨️</div>
                                <div class="text-sm font-semibold text-gray-900">Hitam Putih</div>
                                <div class="text-xs text-gray-500">Minimal, hemat tinta</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Section Toggles --}}
                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Elemen yang Ditampilkan</label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="receipt_show_customer_info" value="1" {{ $receiptShowCustomer === '1' ? 'checked' : '' }}
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-sm font-medium text-gray-900">Info Customer</span>
                            <p class="text-xs text-gray-500">Nama, meja, tipe, tanggal</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="receipt_show_payment_method" value="1" {{ $receiptShowPayment === '1' ? 'checked' : '' }}
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-sm font-medium text-gray-900">Metode Pembayaran</span>
                            <p class="text-xs text-gray-500">Tampilkan di bawah rincian pesanan</p>
                        </div>
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition-colors">
                        Simpan Pengaturan Struk
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
