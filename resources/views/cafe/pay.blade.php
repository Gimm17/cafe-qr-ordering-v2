<x-cafe-layout title="Pembayaran">
    <div class="min-h-screen bg-bone pt-4 pb-28 px-4">
        {{-- Header --}}
        <div class="max-w-md mx-auto">
            <a href="{{ route('cafe.order.show', ['order' => $order->order_code]) }}" class="inline-flex items-center text-muted text-sm mb-4 hover:text-ink transition-colors">
                ← Kembali ke pesanan
            </a>

            {{-- Order Summary Card --}}
            <div class="ui-card p-5 mb-5">
                <div class="flex items-center justify-between mb-3">
                    <h1 class="text-lg font-bold text-ink">Pembayaran</h1>
                    <span class="ui-chip px-3 py-1 text-xs font-semibold text-primary-700">
                        {{ $order->order_code }}
                    </span>
                </div>

                <div class="space-y-2">
                    @foreach($items as $item)
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-muted">{{ $item->qty }}x</span>
                            <span class="text-ink font-medium">{{ $item->product_name }}</span>
                        </div>
                        <span class="text-ink font-semibold">Rp {{ number_format($item->unit_price * $item->qty, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-line mt-3 pt-3 flex justify-between items-center">
                    <span class="text-sm font-semibold text-ink">Total</span>
                    <span class="text-xl font-extrabold text-primary-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Payment Action --}}
            <div class="ui-card-flat p-5 mb-5">
                <div class="text-center">
                    <div id="paymentLoading" class="py-6">
                        <div class="inline-flex items-center gap-3">
                            <svg class="animate-spin h-5 w-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span class="text-muted text-sm">Memuat halaman pembayaran...</span>
                        </div>
                    </div>

                    <button id="payButton" onclick="openSnapPayment()" class="hidden w-full tap-44 px-5 py-4 bg-primary-600 text-white font-bold text-base ui-btn hover:bg-primary-700 active:scale-[0.98] transition-all duration-150 shadow-soft">
                        💳 Bayar Rp {{ number_format($order->total, 0, ',', '.') }}
                    </button>

                    <p id="payHint" class="hidden text-xs text-muted mt-3">
                        Klik tombol di atas untuk membuka popup pembayaran
                    </p>
                </div>
            </div>

            {{-- Security Notice --}}
            <div class="text-center text-xs text-muted space-y-1">
                <p>🔒 Pembayaran diproses aman oleh Midtrans</p>
                <p>Mendukung QRIS, GoPay, ShopeePay, DANA, VA Bank, dll.</p>
            </div>
        </div>
    </div>

    {{-- Midtrans Snap.js --}}
    <script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>

    <script>
        const SNAP_TOKEN  = @json($snapToken);
        const ORDER_URL   = @json(route('cafe.order.show', ['order' => $order->order_code]));
        const VERIFY_URL  = @json(route('cafe.pay.verify', ['order' => $order->order_code]));
        const CSRF_TOKEN  = @json(csrf_token());

        // After Snap popup closes (success or pending), trigger server-side verify
        // Server calls Midtrans API and updates DB. Browser only triggers.
        function verifyAndRedirect(delay = 500) {
            fetch(VERIFY_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
            })
            .then(res => res.json())
            .then(() => setTimeout(() => window.location.href = ORDER_URL, delay))
            .catch(() => setTimeout(() => window.location.href = ORDER_URL, delay));
        }

        // Auto-trigger Snap payment popup on load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(openSnapPayment, 500);
        });

        function openSnapPayment() {
            document.getElementById('paymentLoading').classList.add('hidden');
            document.getElementById('payButton').classList.remove('hidden');
            document.getElementById('payHint').classList.remove('hidden');

            window.snap.pay(SNAP_TOKEN, {
                onSuccess: function(result) {
                    showStatus('success', 'Pembayaran Berhasil! ✅ Mengecek status...');
                    verifyAndRedirect(500);
                },
                onPending: function(result) {
                    showStatus('pending', 'Menunggu pembayaran...');
                    verifyAndRedirect(2000);
                },
                onError: function(result) {
                    showStatus('error', 'Pembayaran gagal. Silakan coba lagi.');
                },
                onClose: function() {
                    // User closed the popup without finishing
                    document.getElementById('payButton').classList.remove('hidden');
                    document.getElementById('payHint').classList.remove('hidden');
                }
            });
        }


        function showStatus(type, message) {
            const colors = {
                success: 'bg-green-50 text-green-800 border-green-200',
                pending: 'bg-amber-50 text-amber-800 border-amber-200',
                error:   'bg-red-50 text-red-800 border-red-200',
            };

            const btn = document.getElementById('payButton');
            const hint = document.getElementById('payHint');
            btn.classList.add('hidden');
            hint.classList.add('hidden');

            const statusEl = document.createElement('div');
            statusEl.className = `p-4 rounded-xl border ${colors[type]} text-center font-semibold`;
            statusEl.textContent = message;
            btn.parentNode.appendChild(statusEl);
        }
    </script>
</x-cafe-layout>
