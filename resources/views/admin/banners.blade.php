<x-admin-layout>
    <x-slot name="title">Banner</x-slot>

    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">🖼️ Banner Homepage</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola gambar banner yang ditampilkan di halaman depan. Upload banyak sekaligus!</p>
            </div>
        </div>

        {{-- Upload Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                📤 Upload Banner Baru
            </h2>

            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm" class="space-y-4">
                @csrf

                <div id="dropZone"
                    class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-indigo-400 hover:bg-indigo-50/30 transition-all cursor-pointer">
                    <input type="file" name="images[]" multiple accept=".png,.jpg,.jpeg,.webp" id="fileInput"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="text-4xl mb-2">📸</div>
                    <p class="text-sm font-medium text-gray-700">Klik atau drag & drop gambar di sini</p>
                    <p class="text-xs text-gray-500 mt-1">Maks 2MB per file. Format: PNG, JPG, WEBP. Bisa pilih banyak file.</p>
                </div>

                {{-- Preview --}}
                <div id="previewContainer" class="hidden grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3"></div>

                @error('images')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                <button type="submit" id="uploadBtn" class="hidden px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition-colors">
                    Upload Banner
                </button>
            </form>
        </div>

        {{-- Current Banners --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                📋 Banner Saat Ini
                <span class="text-sm font-normal text-gray-500">({{ $banners->count() }} gambar)</span>
            </h2>

            @if($banners->isEmpty())
                <div class="text-center py-12 text-gray-400">
                    <div class="text-5xl mb-3">🖼️</div>
                    <p class="text-sm">Belum ada banner. Upload gambar pertama!</p>
                    <p class="text-xs text-gray-400 mt-1">Banner default akan ditampilkan di homepage.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="bannerGrid">
                    @foreach($banners as $banner)
                    <div class="group relative rounded-xl overflow-hidden border border-gray-200 {{ !$banner->is_active ? 'opacity-50' : '' }}" data-id="{{ $banner->id }}">
                        <img src="{{ asset($banner->image_path) }}?t={{ $banner->updated_at->timestamp }}"
                            alt="Banner {{ $loop->iteration }}"
                            class="w-full h-40 object-cover">

                        {{-- Overlay Actions --}}
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                            <form action="{{ route('admin.banners.toggle', $banner) }}" method="POST">
                                @csrf
                                <button type="submit" title="{{ $banner->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                    class="w-10 h-10 rounded-full bg-white/90 hover:bg-white flex items-center justify-center text-lg shadow transition-all">
                                    {{ $banner->is_active ? '👁️' : '🚫' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.banners.delete', $banner) }}" method="POST" id="delete-banner-{{ $banner->id }}">
                                @csrf
                                <button type="button" onclick="confirmDelete('delete-banner-{{ $banner->id }}', 'banner ini')"
                                    title="Hapus"
                                    class="w-10 h-10 rounded-full bg-white/90 hover:bg-red-100 flex items-center justify-center text-lg shadow transition-all">
                                    🗑️
                                </button>
                            </form>
                        </div>

                        {{-- Info Bar --}}
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent px-3 py-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-white/80 font-medium">#{{ $loop->iteration }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $banner->is_active ? 'bg-green-500/80 text-white' : 'bg-gray-500/80 text-white' }}">
                                    {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <x-slot name="scripts">
    <script>
        // File preview
        const fileInput = document.getElementById('fileInput');
        const previewContainer = document.getElementById('previewContainer');
        const uploadBtn = document.getElementById('uploadBtn');
        const dropZone = document.getElementById('dropZone');

        fileInput.addEventListener('change', function() {
            previewContainer.innerHTML = '';
            if (this.files.length > 0) {
                previewContainer.classList.remove('hidden');
                uploadBtn.classList.remove('hidden');

                Array.from(this.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const div = document.createElement('div');
                        div.className = 'relative rounded-lg overflow-hidden border border-gray-200';
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-24 object-cover">
                            <div class="absolute bottom-0 left-0 right-0 bg-black/50 px-2 py-1">
                                <span class="text-xs text-white truncate block">${file.name}</span>
                            </div>`;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                previewContainer.classList.add('hidden');
                uploadBtn.classList.add('hidden');
            }
        });

        // Drag & drop visual feedback
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-indigo-500', 'bg-indigo-50/50');
        });
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50/50');
        });
        dropZone.addEventListener('drop', () => {
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50/50');
        });
    </script>
    </x-slot>
</x-admin-layout>
