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

            <!-- Reviews -->
            @if($order->feedback->isNotEmpty())
            <section class="ui-card overflow-hidden">
                <div class="p-5 border-b ui-divider">
                    <h3 class="font-semibold tracking-tight">⭐ Review pelanggan</h3>
                </div>
                <div class="divide-y ui-divider">
                    @foreach($order->feedback as $review)
                    <div class="p-5">
                        <p class="font-semibold text-gray-900 text-sm">{{ $review->product?->name ?? 'Produk' }}</p>
                        <div class="flex items-center gap-1 mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= ($review->rating ?? 0) ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        @if($review->comment)
                            <p class="mt-1 text-sm text-gray-700 italic">"{{ $review->comment }}"</p>
                        @endif
                    </div>
                    @endforeach
                </div>
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
                        <span class="text-gray-900 font-medium">{{ $order->payment->payment_method_label }}</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="text-muted">Status</span>
                        <span class="font-semibold {{ $order->payment->status === 'PAID' ? 'text-green-700' : 'text-amber-700' }}">{{ $order->payment->status }}</span>
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
            <button onclick="openReceiptModal()" class="w-full tap-44 py-3 bg-primary-600 text-white font-semibold ui-btn hover:bg-primary-700 transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Struk
            </button>
        </aside>
    </div>

    {{-- Receipt Modal --}}
    <div id="receiptModal" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);" onclick="if(event.target===this)closeReceiptModal()">
        <style>
            #receiptModal { background:rgba(0,0,0,0); transition:background .25s; }
            #receiptModal.active { background:rgba(0,0,0,.5); }
            #receiptModal .receipt-popup { transform:translateY(20px) scale(.97); opacity:0; transition:all .25s cubic-bezier(.4,0,.2,1); }
            #receiptModal.active .receipt-popup { transform:translateY(0) scale(1); opacity:1; }
        </style>
        <div class="receipt-popup" style="background:#fff;border-radius:20px;width:95%;max-width:460px;height:85vh;display:flex;flex-direction:column;box-shadow:0 20px 60px rgba(0,0,0,.2);overflow:hidden;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #eee;flex-shrink:0;">
                <h3 style="font-weight:700;font-size:16px;color:#1a1a1a;margin:0;">📄 Struk Pembelian</h3>
                <button onclick="closeReceiptModal()" style="width:36px;height:36px;background:#f3f4f6;border:none;border-radius:10px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:18px;color:#666;">✕</button>
            </div>
            <div style="flex:1;overflow:hidden;position:relative;min-height:400px;">
                <div id="receiptSpinner" style="display:flex;position:absolute;inset:0;align-items:center;justify-content:center;background:#fff;">
                    <div style="width:32px;height:32px;border:3px solid #e5e7eb;border-top-color:#4f46e5;border-radius:50%;animation:rspin .7s linear infinite;"></div>
                </div>
                <style>@keyframes rspin{to{transform:rotate(360deg)}}</style>
                <iframe id="receiptIframe" style="width:100%;height:100%;border:none;opacity:0;transition:opacity .3s;" title="Struk"></iframe>
            </div>
        </div>
    </div>

    <script>
    function openReceiptModal() {
        const modal = document.getElementById('receiptModal');
        const iframe = document.getElementById('receiptIframe');
        const spinner = document.getElementById('receiptSpinner');
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('active'), 10);
        document.body.style.overflow = 'hidden';
        spinner.style.display = 'flex';
        iframe.style.opacity = '0';
        iframe.src = @json(route('admin.orders.receipt', $order->id));
        iframe.onload = function() {
            spinner.style.display = 'none';
            iframe.style.opacity = '1';
        };
    }
    function closeReceiptModal() {
        const modal = document.getElementById('receiptModal');
        modal.classList.remove('active');
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            document.getElementById('receiptIframe').src = '';
        }, 250);
    }
    function printReceipt() {
        document.getElementById('receiptIframe').contentWindow.print();
    }
    </script>
</x-admin-layout>
