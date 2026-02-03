<x-admin-layout>
  <h1 class="text-xl font-bold mb-4">Tables & QR</h1>

  <form class="rounded-xl bg-white border p-4 mb-4" method="POST" action="{{ route('admin.tables.store') }}">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <input class="rounded border px-3 py-2" type="number" name="table_no" placeholder="No meja" required>
      <input class="rounded border px-3 py-2" name="name" placeholder="Nama (opsional)">
      <button class="rounded bg-gray-900 text-white px-4 py-2 font-semibold">Tambah Meja</button>
    </div>
  </form>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($tables as $t)
      @php $token = $t->activeToken(); @endphp
      <div class="rounded-xl bg-white border shadow-sm overflow-hidden">
        <!-- QR Card Header -->
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 text-white px-4 py-3 text-center">
          <div class="text-lg font-bold">{{ config('app.name', 'Cafe QR') }}</div>
        </div>
        
        <!-- QR Code Area -->
        <div class="p-6 flex flex-col items-center" id="qr-card-{{ $t->id }}">
          @if($token)
            <img class="w-48 h-48 border-4 border-gray-100 rounded-lg shadow-sm" 
                 src="{{ route('admin.tables.qr', $t) }}" 
                 alt="QR Meja {{ $t->table_no }}"
                 id="qr-image-{{ $t->id }}">
          @else
            <div class="w-48 h-48 bg-gray-100 rounded-lg flex items-center justify-center">
              <span class="text-gray-400 text-sm">No QR Token</span>
            </div>
          @endif
          
          <!-- Table Number Badge -->
          <div class="mt-4 bg-gray-900 text-white px-6 py-2 rounded-full">
            <span class="text-sm font-medium">MEJA</span>
            <span class="text-2xl font-bold ml-1">{{ $t->table_no }}</span>
          </div>
          
          @if($t->name)
            <div class="mt-2 text-sm text-gray-600">{{ $t->name }}</div>
          @endif
          
          <!-- Instruction Message -->
          <div class="mt-4 text-center text-xs text-gray-500 px-4 leading-relaxed">
            Silahkan scan QR untuk pesan<br>atau langsung ke kasir
          </div>
        </div>

        <!-- Actions -->
        @if($token)
        <div class="border-t px-4 py-3 bg-gray-50">
          <div class="text-xs text-gray-500 mb-2 truncate">
            <span class="font-medium">URL:</span> 
            <span class="font-mono">{{ url('/t/'.$token->token) }}</span>
          </div>
          
          <!-- Download Buttons -->
          <div class="grid grid-cols-2 gap-2 mb-2">
            <a href="{{ route('admin.tables.qr.download', ['table' => $t, 'format' => 'png']) }}" 
               class="flex items-center justify-center gap-1 py-2 px-3 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
              </svg>
              PNG
            </a>
            <a href="{{ route('admin.tables.qr.download', ['table' => $t, 'format' => 'pdf']) }}" 
               class="flex items-center justify-center gap-1 py-2 px-3 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
              </svg>
              PDF
            </a>
          </div>
          
          <!-- Rotate Token -->
          <form id="rotate-form-{{ $t->id }}" class="hidden" method="POST" action="{{ route('admin.tables.rotate', $t) }}">@csrf</form>
          <button type="button" onclick="confirmRotateToken('rotate-form-{{ $t->id }}', '{{ $t->table_no }}')" 
                  class="w-full py-2 bg-gray-900 text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition-colors">
            Rotate Token
          </button>
        </div>
        @endif
      </div>
    @endforeach
  </div>
</x-admin-layout>
