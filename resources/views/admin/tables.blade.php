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

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach($tables as $t)
      @php $token = $t->activeToken(); @endphp
      <div class="rounded-xl bg-white border p-4">
        <div class="flex items-center justify-between">
          <div>
            <div class="text-xs text-gray-500">Meja</div>
            <div class="text-2xl font-bold">{{ $t->table_no }}</div>
            <div class="text-sm text-gray-600">{{ $t->name }}</div>
          </div>
          @if($token)
            <img class="w-28 h-28 border rounded-lg" src="{{ route('admin.tables.qr', $t) }}" alt="QR">
          @endif
        </div>

        @if($token)
          <div class="mt-3 text-xs text-gray-600 break-all">
            Token: <span class="font-mono">{{ $token->token }}</span>
          </div>
          <div class="mt-2 text-xs text-gray-600 break-all">
            URL: <span class="font-mono">{{ url('/t/'.$token->token) }}</span>
          </div>
        @endif

        <form id="rotate-form-{{ $t->id }}" class="hidden" method="POST" action="{{ route('admin.tables.rotate', $t) }}">@csrf</form>
        <button type="button" onclick="confirmRotateToken('rotate-form-{{ $t->id }}', '{{ $t->table_no }}')" class="mt-3 w-full rounded bg-gray-900 text-white py-2 text-sm font-semibold hover:bg-gray-800 transition-colors">Rotate Token</button>
      </div>
    @endforeach
  </div>
</x-admin-layout>
