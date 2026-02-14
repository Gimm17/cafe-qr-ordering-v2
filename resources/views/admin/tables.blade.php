<x-admin-layout title="Meja & QR">
    <div class="mb-6">
        <p class="text-sm text-muted">Kelola nomor meja dan QR code untuk akses menu per meja.</p>
    </div>

    <!-- Add Table -->
    <div class="ui-card p-5 mb-6">
        <h2 class="font-semibold tracking-tight mb-4">Tambah meja</h2>
        <form method="POST" action="{{ route('admin.tables.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            @csrf
            <input class="tap-44 rounded-2xl border border-line px-4 py-3 bg-white ui-focus" type="number" name="table_no" placeholder="No meja" required>
            <input class="tap-44 rounded-2xl border border-line px-4 py-3 bg-white ui-focus" name="name" placeholder="Nama (opsional)">
            <button class="tap-44 rounded-2xl bg-primary-600 hover:bg-primary-700 text-white px-5 py-3 font-semibold transition-colors">Tambah</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($tables as $t)
            @php $token = $t->activeToken(); @endphp

            <div class="ui-card-flat overflow-hidden">
                <!-- Header -->
                <div class="px-5 py-4 bg-primary-600 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm opacity-90">{{ config('app.name', 'Cafe QR') }}</div>
                            <div class="text-lg font-extrabold tracking-tight">Meja {{ $t->table_no }}</div>
                        </div>
                        <span class="ui-chip px-3 py-1 text-[11px] font-bold text-white/90 bg-white/15 border border-white/20">
                            {{ $token ? 'Aktif' : 'Belum ada token' }}
                        </span>
                    </div>
                    @if($t->name)
                        <p class="text-xs opacity-90 mt-1">{{ $t->name }}</p>
                    @endif
                </div>

                <!-- QR Area -->
                <div class="p-5 flex flex-col items-center" id="qr-card-{{ $t->id }}">
                    @if($token)
                        <img class="w-48 h-48 bg-white border border-line rounded-2xl shadow-soft" src="{{ route('admin.tables.qr', $t) }}" alt="QR Meja {{ $t->table_no }}" id="qr-image-{{ $t->id }}">
                        <p class="mt-3 text-xs text-muted text-center">Scan QR untuk pesan. Bisa juga langsung ke kasir.</p>
                    @else
                        <div class="w-48 h-48 bg-gray-50 border border-line rounded-2xl flex items-center justify-center">
                            <span class="text-muted text-sm">No QR Token</span>
                        </div>
                    @endif
                </div>

                @if($token)
                <!-- Actions -->
                <div class="p-5 border-t ui-divider bg-primary-50/40">
                    <div class="text-xs text-muted mb-3 truncate">
                        <span class="font-semibold">URL:</span>
                        <span class="font-mono">{{ url('/t/'.$token->token) }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <a href="{{ route('admin.tables.qr.download', ['table' => $t, 'format' => 'png']) }}" class="tap-44 py-2.5 px-3 bg-primary-600 text-white rounded-xl text-sm font-semibold hover:bg-primary-700 transition-colors text-center">
                            Download PNG
                        </a>
                        <a href="{{ route('admin.tables.qr.download', ['table' => $t, 'format' => 'pdf']) }}" class="tap-44 py-2.5 px-3 bg-white border border-line text-gray-800 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors text-center">
                            Download PDF
                        </a>
                    </div>

                    <form id="rotate-form-{{ $t->id }}" class="hidden" method="POST" action="{{ route('admin.tables.rotate', $t) }}">@csrf</form>
                    <button type="button" onclick="confirmRotateToken('rotate-form-{{ $t->id }}', '{{ $t->table_no }}')" class="w-full tap-44 py-3 bg-gray-900 text-white rounded-xl text-sm font-semibold hover:bg-black transition-colors">
                        Rotate Token
                    </button>
                </div>
                @endif
            </div>
        @endforeach
    </div>
</x-admin-layout>
