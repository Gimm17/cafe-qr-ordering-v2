<x-admin-layout :title="isset($product) && $product->exists ? 'Edit Produk' : 'Tambah Produk'">
    <!-- Back Button -->
    <a href="{{ route('admin.products') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-6 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form action="{{ isset($product) && $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
                                   class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="category_id" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                            <input type="number" name="base_price" value="{{ old('base_price', $product->base_price ?? '') }}" required min="0"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="3" 
                                  class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none resize-none">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>

                    <!-- Image Section -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Produk</label>
                        
                        <!-- Current Image Preview -->
                        @if(isset($product) && $product->image_url)
                        <div class="mb-3">
                            <p class="text-xs text-gray-500 mb-2">Gambar saat ini:</p>
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-24 w-24 object-cover rounded-lg border border-gray-200">
                        </div>
                        @endif

                        <!-- Image Source Toggle -->
                        <div class="flex gap-4 mb-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="image_source" value="url" id="image_source_url" 
                                       {{ old('image_source', 'url') === 'url' ? 'checked' : '' }}
                                       class="w-4 h-4 text-primary-600 border-gray-300 focus:ring-primary-500"
                                       onchange="toggleImageInput()">
                                <span class="text-sm text-gray-700">URL Gambar</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="image_source" value="upload" id="image_source_upload"
                                       {{ old('image_source') === 'upload' ? 'checked' : '' }}
                                       class="w-4 h-4 text-primary-600 border-gray-300 focus:ring-primary-500"
                                       onchange="toggleImageInput()">
                                <span class="text-sm text-gray-700">Upload File</span>
                            </label>
                        </div>

                        <!-- URL Input -->
                        <div id="image_url_container">
                            <input type="url" name="image_url" id="image_url_input" value="{{ old('image_url', $product->image_url ?? '') }}" placeholder="https://..."
                                   class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none"
                                   oninput="previewUrlImage(this.value)">
                            <p class="text-xs text-gray-500 mt-1">Masukkan URL gambar produk (opsional)</p>
                            
                            <!-- URL Preview -->
                            <div id="url_preview_container" class="mt-3 hidden">
                                <p class="text-xs text-gray-500 mb-2">Preview:</p>
                                <div class="relative inline-block">
                                    <img id="url_preview_image" src="" alt="Preview" 
                                         class="h-32 w-32 object-cover rounded-lg border-2 border-gray-200"
                                         onerror="handleUrlPreviewError()">
                                    <div id="url_preview_loading" class="absolute inset-0 bg-gray-100 rounded-lg flex items-center justify-center hidden">
                                        <svg class="animate-spin h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                    <div id="url_preview_success" class="absolute -top-2 -right-2 bg-green-500 text-white rounded-full p-1 hidden">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                                <p id="url_preview_error" class="text-xs text-red-500 mt-2 hidden">⚠️ Gambar tidak dapat dimuat. Periksa URL-nya.</p>
                            </div>
                        </div>

                        <!-- File Upload -->
                        <div id="image_file_container" class="hidden">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-400 transition-colors">
                                <input type="file" name="image_file" id="image_file_input" accept="image/*" class="hidden"
                                       onchange="previewImage(this)">
                                <label for="image_file_input" class="cursor-pointer">
                                    <div id="upload_placeholder">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">Klik untuk pilih gambar</p>
                                        <p class="text-xs text-gray-500">PNG, JPG, WEBP (maks. 2MB)</p>
                                    </div>
                                    <div id="upload_preview" class="hidden">
                                        <img id="preview_image" src="" alt="Preview" class="mx-auto h-24 w-24 object-cover rounded-lg">
                                        <p class="mt-2 text-sm text-primary-600">Klik untuk ganti gambar</p>
                                    </div>
                                </label>
                            </div>
                            @error('image_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Modifiers -->
                    @if(isset($modGroups) && $modGroups->isNotEmpty())
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Modifier Groups</label>
                        <p class="text-xs text-gray-500 mb-3">Pilih modifier yang berlaku untuk produk ini</p>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($modGroups as $modGroup)
                            <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                <input type="checkbox" name="mod_groups[]" value="{{ $modGroup->id }}"
                                       {{ in_array($modGroup->id, old('mod_groups', $selectedModGroups ?? [])) ? 'checked' : '' }}
                                       class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                <span class="text-sm text-gray-700">{{ $modGroup->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Status -->
                    <div class="flex flex-wrap gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-gray-700">Aktif</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_sold_out" value="1" {{ old('is_sold_out', $product->is_sold_out ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-gray-700">Stok Habis</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller', $product->is_best_seller ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-gray-700">Best Seller</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="mt-8 w-full py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors">
                    {{ isset($product) && $product->exists ? 'Simpan Perubahan' : 'Tambah Produk' }}
                </button>
            </form>
        </div>
    </div>

    <script>
        let urlPreviewTimeout = null;

        function toggleImageInput() {
            const urlRadio = document.getElementById('image_source_url');
            const urlContainer = document.getElementById('image_url_container');
            const fileContainer = document.getElementById('image_file_container');
            
            if (urlRadio.checked) {
                urlContainer.classList.remove('hidden');
                fileContainer.classList.add('hidden');
            } else {
                urlContainer.classList.add('hidden');
                fileContainer.classList.remove('hidden');
            }
        }
        
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview_image').src = e.target.result;
                    document.getElementById('upload_placeholder').classList.add('hidden');
                    document.getElementById('upload_preview').classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewUrlImage(url) {
            // Clear previous timeout
            if (urlPreviewTimeout) {
                clearTimeout(urlPreviewTimeout);
            }

            const container = document.getElementById('url_preview_container');
            const img = document.getElementById('url_preview_image');
            const loading = document.getElementById('url_preview_loading');
            const success = document.getElementById('url_preview_success');
            const error = document.getElementById('url_preview_error');

            // Reset states
            error.classList.add('hidden');
            success.classList.add('hidden');

            // If empty, hide preview
            if (!url || url.trim() === '') {
                container.classList.add('hidden');
                return;
            }

            // Validate URL format
            try {
                new URL(url);
            } catch (e) {
                container.classList.add('hidden');
                return;
            }

            // Debounce - wait 500ms before loading
            urlPreviewTimeout = setTimeout(() => {
                // Show container with loading
                container.classList.remove('hidden');
                loading.classList.remove('hidden');
                img.classList.add('opacity-50');

                // Set image source
                img.src = url;
            }, 500);
        }

        function handleUrlPreviewError() {
            const loading = document.getElementById('url_preview_loading');
            const success = document.getElementById('url_preview_success');
            const error = document.getElementById('url_preview_error');
            const img = document.getElementById('url_preview_image');

            loading.classList.add('hidden');
            success.classList.add('hidden');
            error.classList.remove('hidden');
            img.classList.remove('opacity-50');
            img.src = 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 24 24" fill="none" stroke="%23d1d5db" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>';
        }

        // Handle successful image load
        document.addEventListener('DOMContentLoaded', function() {
            const img = document.getElementById('url_preview_image');
            img.addEventListener('load', function() {
                // Only handle if it's not our placeholder SVG
                if (this.src && !this.src.startsWith('data:image/svg+xml')) {
                    const loading = document.getElementById('url_preview_loading');
                    const success = document.getElementById('url_preview_success');
                    const error = document.getElementById('url_preview_error');

                    loading.classList.add('hidden');
                    error.classList.add('hidden');
                    success.classList.remove('hidden');
                    this.classList.remove('opacity-50');

                    // Hide success checkmark after 2 seconds
                    setTimeout(() => {
                        success.classList.add('hidden');
                    }, 2000);
                }
            });

            // Initialize on page load
            toggleImageInput();

            // If there's already a URL value, show preview
            const urlInput = document.getElementById('image_url_input');
            if (urlInput.value) {
                previewUrlImage(urlInput.value);
            }
        });
    </script>
</x-admin-layout>
