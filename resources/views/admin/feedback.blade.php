<x-admin-layout title="Feedback">
    <div class="mb-6">
        <p class="text-sm text-muted">Lihat rating dan komentar pelanggan per produk, lalu tampilkan/sembunyikan jika diperlukan.</p>
    </div>

    <div class="ui-card overflow-hidden">
        <div class="p-5 border-b ui-divider flex items-center justify-between">
            <h2 class="font-semibold tracking-tight">Daftar Review</h2>
            <span class="ui-chip px-3 py-1 text-xs text-muted">{{ $feedback->total() }} data</span>
        </div>

        <!-- Desktop table -->
        <div class="hidden md:block overflow-auto">
            <table class="w-full text-sm">
                <thead class="text-muted">
                    <tr>
                        <th class="text-left py-3 px-5">Waktu</th>
                        <th class="text-left py-3 px-5">Order</th>
                        <th class="text-left py-3 px-5">Produk</th>
                        <th class="text-left py-3 px-5">Rating</th>
                        <th class="text-left py-3 px-5">Komentar</th>
                        <th class="text-left py-3 px-5">Status</th>
                        <th class="text-right py-3 px-5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y ui-divider">
                    @foreach($feedback as $f)
                    <tr class="align-top hover:bg-gray-50">
                        <td class="py-3 px-5 whitespace-nowrap text-gray-700">{{ $f->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-3 px-5 font-semibold text-gray-900">{{ $f->order?->order_code ?? '-' }}</td>
                        <td class="py-3 px-5 text-gray-700">{{ $f->product?->name ?? '-' }}</td>
                        <td class="py-3 px-5">
                            <div class="flex items-center gap-0.5">
                                @for($i=1;$i<=5;$i++)
                                    <svg class="w-3.5 h-3.5 {{ $i <= ($f->rating ?? 0) ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </td>
                        <td class="py-3 px-5 text-gray-700 whitespace-pre-wrap max-w-xs">{{ $f->comment ?? '-' }}</td>
                        <td class="py-3 px-5">
                            <span class="ui-chip px-3 py-1 text-xs font-bold {{ $f->status === 'VISIBLE' ? 'text-green-700' : 'text-gray-700' }}">
                                {{ $f->status }}
                            </span>
                        </td>
                        <td class="py-3 px-5 text-right">
                            <form method="POST" action="{{ route('admin.feedback.toggle', $f) }}" class="inline">
                                @csrf
                                <button class="px-4 py-2 rounded-xl border border-line bg-white hover:bg-gray-50 text-sm font-semibold">
                                    {{ $f->status === 'VISIBLE' ? 'Hide' : 'Show' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile cards -->
        <div class="md:hidden divide-y ui-divider">
            @foreach($feedback as $f)
            <div class="p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs text-muted">{{ $f->created_at->format('d/m/Y H:i') }}</p>
                        <p class="font-semibold text-gray-900">{{ $f->product?->name ?? 'Produk' }}</p>
                        <p class="text-xs text-muted">Order: {{ $f->order?->order_code ?? '-' }}</p>
                    </div>
                    <span class="ui-chip px-3 py-1 text-xs font-bold {{ $f->status === 'VISIBLE' ? 'text-green-700' : 'text-gray-700' }}">{{ $f->status }}</span>
                </div>

                <div class="mt-2 flex items-center justify-between">
                    <div class="flex items-center gap-0.5">
                        @for($i=1;$i<=5;$i++)
                            <svg class="w-3.5 h-3.5 {{ $i <= ($f->rating ?? 0) ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <form method="POST" action="{{ route('admin.feedback.toggle', $f) }}" class="inline">
                        @csrf
                        <button class="tap-44 px-4 py-2 rounded-xl border border-line bg-white hover:bg-gray-50 text-sm font-semibold">
                            {{ $f->status === 'VISIBLE' ? 'Hide' : 'Show' }}
                        </button>
                    </form>
                </div>

                @if($f->comment)
                <p class="mt-2 text-sm text-gray-700 whitespace-pre-wrap">{{ $f->comment }}</p>
                @endif
            </div>
            @endforeach
        </div>

        <div class="p-5 border-t ui-divider">
            {{ $feedback->links() }}
        </div>
    </div>
</x-admin-layout>
