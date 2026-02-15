<x-cafe-layout :tableNo="$tableNo" title="Pesanan {{ $order->order_code }} - Cafe Order">
    <div class="max-w-lg mx-auto px-4">
        <div class="flex items-start justify-between gap-3 mb-5">
            <div>
                <h1 class="text-xl font-extrabold tracking-tight text-gray-900">{{ $order->order_code }}</h1>
                <p class="text-sm text-muted">{{ $order->created_at->format('d M Y, H:i') }} • {{ $order->customer_name }}</p>
            </div>
            <a href="{{ route('cafe.history') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">Riwayat</a>
        </div>

        <!-- Payment / Status Summary -->
        <div class="ui-card p-5 mb-4" id="statusSummary">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs text-muted">Status Pesanan</p>
                    <p class="text-lg font-extrabold text-gray-900" id="orderStatusText">{{ $order->order_status }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-muted">Pembayaran</p>
                    <p class="font-bold {{ $order->payment_status === 'PAID' ? 'text-green-700' : 'text-red-700' }}" id="paymentStatusText">
                        {{ $order->payment_status === 'PAID' ? '✓ Paid' : 'Unpaid' }}
                    </p>
                </div>
            </div>

            @if($order->payment_status !== 'PAID')
                @php $expiresAt = $order->created_at->addMinutes(10); @endphp
                <div class="mt-4 ui-card-flat p-4 border border-amber-200 bg-amber-50" id="unpaidWarning">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="font-semibold text-amber-800">Menunggu pembayaran</p>
                            <p class="text-xs text-amber-700">Pesanan akan expired jika belum dibayar.</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-amber-700">Sisa waktu</p>
                            <p class="text-lg font-extrabold text-amber-900" id="countdown">--:--</p>
                        </div>
                    </div>

                    @if($order->created_at->addMinutes(10)->isFuture())
                    <a href="{{ route('cafe.pay', $order->order_code) }}" class="mt-3 inline-flex items-center justify-center w-full tap-44 px-5 py-3 bg-primary-600 text-white font-semibold ui-btn hover:bg-primary-700 transition-colors">
                        💳 Bayar Sekarang
                    </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- Progress -->
        <div class="ui-card p-5 mb-4">
            <h2 class="font-semibold tracking-tight mb-4">Progress</h2>
            @php
                $steps = [
                    'DITERIMA' => ['label' => 'Diterima', 'desc' => 'Pesanan masuk'],
                    'DIPROSES' => ['label' => 'Diproses', 'desc' => 'Sedang dibuat'],
                    'READY' => ['label' => 'Ready', 'desc' => 'Siap diambil/diantar'],
                    'SELESAI' => ['label' => 'Selesai', 'desc' => 'Pesanan selesai'],
                ];
                $orderKeys = array_keys($steps);
                $currentIndex = array_search($order->order_status, $orderKeys);
            @endphp

            <div class="space-y-3" id="progressList">
                @foreach($steps as $key => $meta)
                    @php
                        $idx = array_search($key, $orderKeys);
                        $done = $currentIndex !== false && $idx <= $currentIndex;
                        $active = $currentIndex !== false && $idx === $currentIndex;
                    @endphp
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 w-7 h-7 rounded-full flex items-center justify-center border {{ $done ? 'bg-primary-600 border-primary-600 text-white' : 'bg-white border-line text-gray-400' }}" data-step="{{ $key }}">
                            @if($done)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <span class="text-xs font-bold">{{ $idx+1 }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold {{ $active ? 'text-gray-900' : ($done ? 'text-gray-800' : 'text-gray-600') }}" data-step-label="{{ $key }}">{{ $meta['label'] }}</p>
                            <p class="text-xs text-muted">{{ $meta['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Items -->
        <div class="ui-card overflow-hidden mb-4">
            <div class="p-5 border-b ui-divider">
                <h2 class="font-semibold tracking-tight">Rincian</h2>
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
            <div class="p-5 bg-primary-50/50 border-t ui-divider flex items-center justify-between">
                <span class="font-semibold text-gray-900">Total</span>
                <span class="text-xl font-extrabold text-primary-700">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Download Struk Button -->
        @if($order->order_status === 'SELESAI' && $order->payment_status === 'PAID')
        <div class="mb-4" id="downloadReceiptSection">
            <a href="{{ route('cafe.order.receipt', ['order' => $order->order_code, 't' => \App\Http\Controllers\Cafe\OrderController::receiptToken($order->order_code)]) }}"
               target="_blank"
               class="w-full flex items-center justify-center gap-2 tap-44 px-5 py-3 bg-primary-600 text-white font-semibold ui-btn hover:bg-primary-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                📥 Download Struk
            </a>
        </div>
        @endif

        <!-- Product Reviews -->
        <div class="ui-card overflow-hidden mb-6" id="reviewSection" style="{{ $order->order_status !== 'SELESAI' ? 'display:none;' : '' }}">
            <div class="p-5 border-b ui-divider">
                <h2 class="font-semibold tracking-tight">⭐ Beri Review</h2>
                <p class="text-sm text-muted">Opsional — beri rating untuk produk yang kamu pesan.</p>
            </div>

            @if($order->feedback->isNotEmpty())
                {{-- Already reviewed --}}
                <div class="divide-y ui-divider">
                    @foreach($order->items as $item)
                        @php $review = $order->feedback->firstWhere('order_item_id', $item->id); @endphp
                        <div class="p-5">
                            <p class="font-semibold text-gray-900 text-sm">{{ $item->product_name }}</p>
                            @if($review)
                                <div class="flex items-center gap-1 mt-1">
                                    @for($i=1;$i<=5;$i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                @if($review->comment)
                                    <p class="mt-1 text-sm text-gray-600 italic">"{{ $review->comment }}"</p>
                                @endif
                            @else
                                <p class="text-xs text-gray-400 mt-1">Tidak diberi review</p>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="p-4 bg-green-50 border-t border-green-100 text-center">
                    <p class="text-sm text-green-700 font-medium">✅ Terima kasih sudah memberi review!</p>
                </div>
            @else
                {{-- Review form --}}
                <form action="{{ route('cafe.order.feedback', $order) }}" method="POST">
                    @csrf
                    <div class="divide-y ui-divider">
                        @foreach($order->items as $item)
                        <div class="p-5">
                            <p class="font-semibold text-gray-900 text-sm mb-2">{{ $item->product_name }}</p>
                            <div class="flex items-center gap-1 mb-2" id="stars-{{ $item->id }}">
                                @for($i=1;$i<=5;$i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="items[{{ $item->id }}][rating]" value="{{ $i }}" class="hidden star-input" data-group="{{ $item->id }}">
                                        <svg class="w-7 h-7 text-gray-300 hover:text-amber-400 transition-colors star-svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </label>
                                @endfor
                            </div>
                            <textarea name="items[{{ $item->id }}][comment]" rows="1" class="w-full px-3 py-2 bg-white rounded-xl border border-line ui-focus resize-none text-sm" placeholder="Komentar (opsional)"></textarea>
                        </div>
                        @endforeach
                    </div>
                    <div class="p-5 border-t ui-divider">
                        <p class="text-xs text-muted mb-3">💡 Tidak wajib — kosongkan saja jika tidak ingin memberi review.</p>
                        <button type="submit" class="w-full tap-44 py-3 bg-primary-600 text-white font-semibold ui-btn hover:bg-primary-700 transition-colors">
                            Kirim Review
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <div class="mb-10">
            <a href="{{ route('cafe.menu') }}" class="w-full inline-flex items-center justify-center tap-44 px-5 py-3 bg-gray-900 text-white font-semibold ui-btn hover:bg-black transition-colors">
                Pesan Lagi
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>

    <x-slot:scripts>
    <script>
        // Notification sound
        const notificationSound = new Audio("{{ file_exists(public_path('custom_notification.mp3')) ? asset('custom_notification.mp3') : 'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbsGckGFqIw+DfuHEoJUyAvN3nxII5MWCE0O/dq1s3OHaV3/DexnlHOm+L0/Hhz5NbR2yFyerhz5leUHGLz/Dlz5tmWXiOz/Dlz5tm' }}");

        // Countdown for unpaid order (10 minutes)
        const expiresAt = {{ $order->payment_status !== 'PAID' ? $order->created_at->addMinutes(10)->timestamp * 1000 : 'null' }};
        const countdownEl = document.getElementById('countdown');

        function tickCountdown() {
            if (!expiresAt || !countdownEl) return;
            const now = Date.now();
            const diff = expiresAt - now;
            if (diff <= 0) {
                countdownEl.textContent = '00:00';
                return;
            }
            const m = Math.floor(diff / 60000);
            const s = Math.floor((diff % 60000) / 1000);
            countdownEl.textContent = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
        }
        setInterval(tickCountdown, 1000);
        tickCountdown();

        // Poll status (ETag + backoff)
        const statusUrl = @json(route('cafe.order.status', $order->order_code));
        let etag = null;
        let backoff = 1000;
        const maxBackoff = 8000;
        let lastStatus = '{{ $order->status }}';

        function setText(id, text) {
            const el = document.getElementById(id);
            if (el) el.textContent = text;
        }

        function refreshProgress(orderStatus) {
            // Update headline
            setText('orderStatusText', orderStatus);

            // Steps order
            const keys = ['DITERIMA','DIPROSES','READY','SELESAI'];
            const idx = keys.indexOf(orderStatus);
            keys.forEach((k, i) => {
                const dot = document.querySelector(`[data-step="${k}"]`);
                const label = document.querySelector(`[data-step-label="${k}"]`);
                if (!dot || !label) return;

                const done = idx !== -1 && i <= idx;
                const active = idx !== -1 && i === idx;

                dot.className = `mt-0.5 w-7 h-7 rounded-full flex items-center justify-center border ${done ? 'bg-primary-600 border-primary-600 text-white' : 'bg-white border-line text-gray-400'}`;
                label.className = `font-semibold ${active ? 'text-gray-900' : (done ? 'text-gray-800' : 'text-gray-600')}`;

                dot.innerHTML = done
                    ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                    : `<span class="text-xs font-bold">${i+1}</span>`;
            });
        }

        function refreshPayment(paymentStatus) {
            const el = document.getElementById('paymentStatusText');
            if (!el) return;
            if (paymentStatus === 'PAID') {
                el.textContent = '✓ Paid';
                el.className = 'font-bold text-green-700';
                // Hide the unpaid warning box
                const unpaidBox = document.getElementById('unpaidWarning');
                if (unpaidBox) unpaidBox.style.display = 'none';
            } else {
                el.textContent = 'Unpaid';
                el.className = 'font-bold text-red-700';
            }
        }

        async function poll() {
            if (document.hidden) {
                setTimeout(poll, backoff);
                return;
            }

            try {
                const res = await fetch(statusUrl, {
                    headers: etag ? { 'If-None-Match': etag } : {}
                });

                if (res.status === 304) {
                    backoff = Math.min(maxBackoff, backoff + 500);
                } else if (res.ok) {
                    etag = res.headers.get('ETag');
                    const data = await res.json();
                    refreshProgress(data.order_status);
                    refreshPayment(data.payment_status);
                    backoff = 1000;

                    // Check for status change to READY
                    if (data.order_status === 'READY' && lastStatus !== 'READY' && lastStatus !== null) {
                        // Play sound
                        notificationSound.play().catch(() => {});
                        // Vibrate
                        if (navigator.vibrate) navigator.vibrate([200, 100, 200]);
                        // Show alert
                        Swal.fire({
                            icon: 'success',
                            title: 'Pesanan Ready!',
                            text: 'Pesanan Anda sudah siap! Silakan ambil di kasir.',
                            confirmButtonColor: '#4F46E5'
                        });
                    }

                    // Check for status change to SELESAI — reveal review form
                    if (data.order_status === 'SELESAI' && lastStatus !== 'SELESAI') {
                        const reviewEl = document.getElementById('reviewSection');
                        if (reviewEl) {
                            reviewEl.style.display = '';
                            reviewEl.style.opacity = '0';
                            reviewEl.style.transform = 'translateY(16px)';
                            reviewEl.style.transition = 'opacity .5s ease, transform .5s ease';
                            requestAnimationFrame(() => {
                                reviewEl.style.opacity = '1';
                                reviewEl.style.transform = 'translateY(0)';
                            });
                            // Re-bind star rating handlers
                            bindStarRatings();
                            // Scroll to review section
                            setTimeout(() => reviewEl.scrollIntoView({ behavior: 'smooth', block: 'center' }), 600);
                        }
                    }

                    lastStatus = data.order_status;
                } else {
                    backoff = Math.min(maxBackoff, backoff + 500);
                }
            } catch (e) {
                backoff = Math.min(maxBackoff, backoff + 500);
            }

            setTimeout(poll, backoff);
        }

        // Start polling
        poll();

        // Star rating UX — extracted to function so it can be re-bound
        function bindStarRatings() {
            document.querySelectorAll('.star-input').forEach(input => {
                input.addEventListener('change', () => {
                    const group = input.dataset.group;
                    const val = parseInt(input.value);
                    document.querySelectorAll(`.star-input[data-group="${group}"]`).forEach((r, idx) => {
                        const svg = r.parentElement.querySelector('.star-svg');
                        if (!svg) return;
                        svg.classList.toggle('text-amber-400', idx < val);
                        svg.classList.toggle('text-gray-300', idx >= val);
                    });
                });
            });
        }
        bindStarRatings();
    </script>
    </x-slot:scripts>
</x-cafe-layout>
