<x-admin-layout title="Detail Pesanan {{ $order->order_code }}">
    <a href="{{ route('admin.orders') }}" class="inline-flex items-center text-sm text-muted hover:text-ink mb-6 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Orders
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Info -->
            <section class="ui-card p-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">{{ $order->order_code }}</h2>
                        <p class="text-sm text-muted">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="ui-chip px-3 py-1 text-sm font-semibold
                            {{ $order->order_status === 'DITERIMA' ? 'text-primary-800' : '' }}
                            {{ $order->order_status === 'DIPROSES' ? 'text-amber-700' : '' }}
                            {{ $order->order_status === 'READY' ? 'text-green-700' : '' }}
                            {{ $order->order_status === 'SELESAI' ? 'text-gray-700' : '' }}
                            {{ $order->order_status === 'DIBATALKAN' ? 'text-red-700' : '' }}">
                            {{ $order->order_status }}
                        </span>
                        <span class="ui-chip px-3 py-1 text-sm font-semibold {{ $order->payment_status === 'PAID' ? 'text-green-700' : 'text-red-700' }}">
                            {{ $order->payment_status === 'PAID' ? '✓ Paid' : 'Unpaid' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-muted">Customer</p>
                        <p class="font-semibold text-gray-900">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-muted">Meja</p>
                        <p class="font-semibold text-gray-900">{{ $order->table->table_no ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-muted">Tipe</p>
                        <p class="font-semibold text-gray-900">{{ $order->fulfillment_type === 'DINE_IN' ? 'Dine In' : 'Pickup' }}</p>
                    </div>
                    <div>
                        <p class="text-muted">Total</p>
                        <p class="font-extrabold text-primary-700">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                    </div>
                </div>
            </section>

            <!-- Items -->
            <section class="ui-card overflow-hidden">
                <div class="p-5 border-b ui-divider">
                    <h3 class="font-semibold tracking-tight">Item pesanan</h3>
                </div>

                <div class="divide-y ui-divider">
                    @foreach($order->items as $item)
                    <div class="p-5">
                        <div class="flex justify-between items-start gap-4">
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900">{{ $item->product_name }}</p>
                                @if($item->mods->isNotEmpty())
                                    <p class="text-xs text-muted mt-1">{{ $item->mods_summary }}</p>
                                @endif
                                @if($item->note)
                                    <p class="text-xs text-amber-700 mt-1">📝 {{ $item->note }}</p>
                                @endif
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-xs text-muted">{{ $item->qty }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                                <p class="font-bold text-gray-900">Rp {{ number_format($item->line_total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="p-5 bg-primary-50/50 border-t ui-divider">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-900">Grand Total</span>
                        <span class="text-xl font-extrabold text-primary-700">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </section>

            <!-- Feedback -->
            @if($order->feedback)
            <section class="ui-card p-6">
                <h3 class="font-semibold tracking-tight mb-4">Feedback pelanggan</h3>
                <div class="flex items-center gap-1 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= $order->feedback->rating ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                @if($order->feedback->comment)
                    <p class="text-sm text-gray-700 italic">“{{ $order->feedback->comment }}”</p>
                @else
                    <p class="text-sm text-muted">Tidak ada komentar.</p>
                @endif
            </section>
            @endif
        </div>

        <!-- Sidebar -->
        <aside class="space-y-6">
            <!-- Update Status -->
            <section class="ui-card p-6">
                <h3 class="font-semibold tracking-tight mb-4">Ubah status</h3>
                <form id="status-form" action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-3">
                    @csrf
                    <select name="status" id="status-select" class="w-full tap-44 px-4 py-3 rounded-2xl border border-line bg-white ui-focus">
                        <option value="DITERIMA" {{ $order->order_status === 'DITERIMA' ? 'selected' : '' }}>Diterima</option>
                        <option value="DIPROSES" {{ $order->order_status === 'DIPROSES' ? 'selected' : '' }}>Diproses</option>
                        <option value="READY" {{ $order->order_status === 'READY' ? 'selected' : '' }}>Ready</option>
                        <option value="SELESAI" {{ $order->order_status === 'SELESAI' ? 'selected' : '' }}>Selesai</option>
                        <option value="DIBATALKAN" {{ $order->order_status === 'DIBATALKAN' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <button type="button" onclick="confirmStatusChange('status-form', document.getElementById('status-select').value)" class="w-full tap-44 py-3 bg-primary-600 text-white font-semibold ui-btn hover:bg-primary-700 transition-colors">
                        Update Status
                    </button>
                </form>
            </section>

            <!-- Payment Info -->
            @if($order->payment)
            <section class="ui-card p-6">
                <h3 class="font-semibold tracking-tight mb-4">Pembayaran</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between gap-3">
                        <span class="text-muted">Metode</span>
                        <span class="text-gray-900 font-medium">{{ $order->payment->payment_method }}</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="text-muted">Status</span>
                        <span class="font-semibold {{ $order->payment->status === 'SUCCESS' ? 'text-green-700' : 'text-amber-700' }}">{{ $order->payment->status }}</span>
                    </div>
                    @if($order->payment->paid_at)
                    <div class="flex justify-between gap-3">
                        <span class="text-muted">Waktu bayar</span>
                        <span class="text-gray-900 font-medium">{{ $order->payment->paid_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </section>
            @endif

            <!-- Print -->
            <button onclick="window.print()" class="w-full tap-44 py-3 bg-white border border-line text-gray-800 font-semibold ui-btn hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Struk
            </button>
        </aside>
    </div>
</x-admin-layout>
