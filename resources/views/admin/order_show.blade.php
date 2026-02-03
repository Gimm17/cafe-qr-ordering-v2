<x-admin-layout title="Detail Pesanan {{ $order->order_code }}">
    <!-- Back Button -->
    <a href="{{ route('admin.orders') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-6 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Daftar Order
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $order->order_code }}</h2>
                        <p class="text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                            {{ $order->order_status === 'DITERIMA' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $order->order_status === 'DIPROSES' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $order->order_status === 'READY' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $order->order_status === 'SELESAI' ? 'bg-gray-100 text-gray-700' : '' }}
                            {{ $order->order_status === 'DIBATALKAN' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ $order->order_status }}
                        </span>
                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $order->payment_status === 'PAID' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $order->payment_status === 'PAID' ? '✓ Paid' : 'Unpaid' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Customer</p>
                        <p class="font-semibold text-gray-800">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Meja</p>
                        <p class="font-semibold text-gray-800">{{ $order->table->table_no ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Fulfillment</p>
                        <p class="font-semibold text-gray-800">{{ $order->fulfillment_type === 'DINE_IN' ? 'Dine In' : 'Pickup' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Total</p>
                        <p class="font-semibold text-primary-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Item Pesanan</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-800">{{ $item->product_name }}</p>
                                @if($item->mods->isNotEmpty())
                                <p class="text-sm text-gray-500">{{ $item->mods_summary }}</p>
                                @endif
                                @if($item->note)
                                <p class="text-sm text-amber-600">📝 {{ $item->note }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-gray-500 text-sm">{{ $item->qty }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                                <p class="font-semibold text-gray-800">Rp {{ number_format($item->line_total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="p-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-800">Grand Total</span>
                        <span class="text-xl font-bold text-primary-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Feedback -->
            @if($order->feedback)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Feedback Pelanggan</h3>
                <div class="flex items-center gap-2 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 {{ $i <= $order->feedback->rating ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                @if($order->feedback->comment)
                <p class="text-gray-600 italic">"{{ $order->feedback->comment }}"</p>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Update Status -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Ubah Status</h3>
                <form id="status-form" action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-3">
                    @csrf
                    <select name="status" id="status-select" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none">
                        <option value="DITERIMA" {{ $order->order_status === 'DITERIMA' ? 'selected' : '' }}>Diterima</option>
                        <option value="DIPROSES" {{ $order->order_status === 'DIPROSES' ? 'selected' : '' }}>Diproses</option>
                        <option value="READY" {{ $order->order_status === 'READY' ? 'selected' : '' }}>Ready</option>
                        <option value="SELESAI" {{ $order->order_status === 'SELESAI' ? 'selected' : '' }}>Selesai</option>
                        <option value="DIBATALKAN" {{ $order->order_status === 'DIBATALKAN' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <button type="button" onclick="confirmStatusChange('status-form', document.getElementById('status-select').value)" class="w-full py-2 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Payment Info -->
            @if($order->payment)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Pembayaran</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Metode</span>
                        <span class="text-gray-800">{{ $order->payment->payment_method }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="font-medium {{ $order->payment->status === 'SUCCESS' ? 'text-green-600' : 'text-amber-600' }}">{{ $order->payment->status }}</span>
                    </div>
                    @if($order->payment->paid_at)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Waktu Bayar</span>
                        <span class="text-gray-800">{{ $order->payment->paid_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Print Receipt -->
            <button onclick="window.print()" class="w-full py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Struk
            </button>
        </div>
    </div>
</x-admin-layout>
