<x-cafe-layout :tableNo="$tableNo" title="Riwayat Pesanan - Cafe Order">
  <div class="max-w-lg mx-auto px-4">
    <div class="flex items-end justify-between mb-5">
      <div>
        <h1 class="text-xl font-bold tracking-tight">Riwayat Pesanan</h1>
        <p class="text-sm text-muted">Pesanan yang pernah kamu buat di meja ini.</p>
      </div>
      <a href="{{ route('cafe.menu') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">Ke menu</a>
    </div>

    @if($orders->isEmpty())
      <div class="ui-card p-8 text-center">
        <div class="w-20 h-20 mx-auto mb-4 bg-primary-50 rounded-2xl flex items-center justify-center">
          <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
        </div>
        <h2 class="text-lg font-semibold">Belum ada riwayat</h2>
        <p class="text-sm text-muted mt-1">Mulai pesan dulu, nanti muncul di sini.</p>
        <a href="{{ route('cafe.menu') }}" class="mt-5 inline-flex items-center justify-center px-5 py-3 bg-primary-600 text-white font-semibold ui-btn hover:bg-primary-700 transition-colors tap-44">
          Lihat Menu
          <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
      </div>
    @else
      <div class="space-y-3">
        @foreach($orders as $order)
          <a href="{{ route('cafe.order.show', $order) }}" class="block ui-card-flat p-4 hover:shadow-soft transition-shadow">
            <div class="flex items-start justify-between gap-3">
              <div>
                <p class="font-semibold text-gray-900">{{ $order->order_code }}</p>
                <p class="text-xs text-muted mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
              </div>
              <span class="ui-chip px-3 py-1 text-[11px] font-bold
                {{ $order->order_status === 'DITERIMA' ? 'text-primary-800' : '' }}
                {{ $order->order_status === 'DIPROSES' ? 'text-amber-700' : '' }}
                {{ $order->order_status === 'READY' ? 'text-green-700' : '' }}
                {{ $order->order_status === 'SELESAI' ? 'text-gray-700' : '' }}">
                {{ $order->order_status }}
              </span>
            </div>

            <div class="mt-3 flex items-center justify-between">
              <span class="text-sm text-muted">{{ $order->payment_status === 'PAID' ? '✓ Paid' : 'Unpaid' }}</span>
              <span class="font-extrabold text-primary-700">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
            </div>
          </a>
        @endforeach
      </div>
    @endif
  </div>
</x-cafe-layout>
