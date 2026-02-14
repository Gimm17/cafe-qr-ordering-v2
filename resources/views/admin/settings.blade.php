<x-admin-layout title="Settings">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pengaturan</h1>
        <p class="text-gray-600">Sesuaikan preferensi aplikasi</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                            @if($category->isClosedOrder())
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-red-600 mt-1">
                                🚫 Sedang Close Order
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-green-600 mt-1">
                                ✅ Buka
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
                        <strong>💡 Tips:</strong> Kosongkan jam untuk membuat kategori selalu tersedia. Jam setelah tengah malam (misal 02:00) berarti cafe beroperasi sampai dini hari.
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
    </div>
</x-admin-layout>
