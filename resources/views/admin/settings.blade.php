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
    </div>
</x-admin-layout>
