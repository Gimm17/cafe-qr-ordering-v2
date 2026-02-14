<x-admin-layout title="Feedback">
    <div class="mb-6">
        <p class="text-sm text-muted">Lihat rating dan komentar pelanggan, lalu tampilkan/sembunyikan jika diperlukan.</p>
    </div>

    <div class="ui-card overflow-hidden">
        <div class="p-5 border-b ui-divider flex items-center justify-between">
            <h2 class="font-semibold tracking-tight">Daftar feedback</h2>
            <span class="ui-chip px-3 py-1 text-xs text-muted">{{ $feedback->total() }} data</span>
        </div>

        <!-- Desktop table -->
        <div class="hidden md:block overflow-auto">
            <table class="w-full text-sm">
                <thead class="text-muted">
                    <tr>
                        <th class="text-left py-3 px-5">Waktu</th>
                        <th class="text-left py-3 px-5">Order</th>
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
                        <td class="py-3 px-5">
                            <span class="ui-chip px-3 py-1 text-xs font-bold {{ ($f->rating ?? 0) >= 4 ? 'text-green-700' : (($f->rating ?? 0) >= 2 ? 'text-amber-700' : 'text-red-700') }}">
                                {{ $f->rating ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 px-5 text-gray-700 whitespace-pre-wrap">{{ $f->comment ?? '-' }}</td>
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
                        <p class="font-semibold text-gray-900">Order: {{ $f->order?->order_code ?? '-' }}</p>
                    </div>
                    <span class="ui-chip px-3 py-1 text-xs font-bold {{ $f->status === 'VISIBLE' ? 'text-green-700' : 'text-gray-700' }}">{{ $f->status }}</span>
                </div>

                <div class="mt-3 flex items-center justify-between">
                    <span class="ui-chip px-3 py-1 text-xs font-bold {{ ($f->rating ?? 0) >= 4 ? 'text-green-700' : (($f->rating ?? 0) >= 2 ? 'text-amber-700' : 'text-red-700') }}">Rating: {{ $f->rating ?? '-' }}</span>
                    <form method="POST" action="{{ route('admin.feedback.toggle', $f) }}" class="inline">
                        @csrf
                        <button class="tap-44 px-4 py-2 rounded-xl border border-line bg-white hover:bg-gray-50 text-sm font-semibold">
                            {{ $f->status === 'VISIBLE' ? 'Hide' : 'Show' }}
                        </button>
                    </form>
                </div>

                <p class="mt-3 text-sm text-gray-700 whitespace-pre-wrap">{{ $f->comment ?? '-' }}</p>
            </div>
            @endforeach
        </div>

        <div class="p-5 border-t ui-divider">
            {{ $feedback->links() }}
        </div>
    </div>
</x-admin-layout>
