import { defineStore } from 'pinia';

export const useOrderStore = defineStore('order', {
    // ── STATE ─────────────────────────────────────────────────────────────────
    // Brankas data pusat: ini yang dibagi ke semua komponen tanpa prop-drilling
    state: () => ({
        orderStatus:   null,   // DITERIMA | DIPROSES | READY | SELESAI
        paymentStatus: null,   // UNPAID | PAID | FAILED | EXPIRED
        updatedAt:     null,
        lastETag:      null,   // Cache header untuk ETag validation (hemat request)
        isPolling:     false,
        backoff:       1000,   // ms awal jeda polling
    }),

    // ── GETTERS ───────────────────────────────────────────────────────────────
    // Computed property: otomatis dihitung ulang setiap kali state berubah
    getters: {
        statusIndex: (state) => {
            const steps = ['DITERIMA', 'DIPROSES', 'READY', 'SELESAI'];
            return steps.indexOf(state.orderStatus);
        },
        isPaid: (state) => state.paymentStatus === 'PAID',
        isDone: (state) => state.orderStatus === 'SELESAI',
        isReady: (state) => state.orderStatus === 'READY',
    },

    // ── ACTIONS ───────────────────────────────────────────────────────────────
    // Fungsi yang bisa memanipulasi state dan berisi async logic
    actions: {
        /**
         * Mulai polling status order dengan Exponential Backoff + ETag validation.
         * Merupakan inti dari "Intelligent Polling" yang dioptimasi (bukan polling buta tiap 10 detik).
         */
        async startPolling(orderCode) {
            if (this.isPolling) return; // Cegah jalankan polling dobel
            this.isPolling = true;
            await this._poll(orderCode);
        },

        async _poll(orderCode) {
            // Pause polling saat tab tidak aktif (hemat baterai & request)
            if (document.hidden) {
                setTimeout(() => this._poll(orderCode), this.backoff);
                return;
            }

            const prevStatus = this.orderStatus;

            try {
                const headers = {};
                if (this.lastETag) headers['If-None-Match'] = this.lastETag;

                const res = await fetch(`/cafe/order/${orderCode}/status`, { headers });

                if (res.status === 304) {
                    // Data tidak berubah → tambah backoff, hemat parsing & re-render
                    this.backoff = Math.min(8000, this.backoff + 500);

                } else if (res.ok) {
                    const data = await res.json();
                    // ✅ Update State — semua komponen yang bergantung akan reactive otomatis
                    this.orderStatus   = data.order_status;
                    this.paymentStatus = data.payment_status;
                    this.updatedAt     = data.updated_at;
                    this.lastETag      = res.headers.get('ETag');
                    this.backoff       = 1000; // Reset backoff karena ada update

                    // Emit event agar komponen Vue bisa trigger notifikasi, sound, dll.
                    if (data.order_status === 'READY' && prevStatus !== 'READY' && prevStatus !== null) {
                        window.dispatchEvent(new CustomEvent('order:ready'));
                    }
                    if (data.order_status === 'SELESAI' && prevStatus !== 'SELESAI' && prevStatus !== null) {
                        window.dispatchEvent(new CustomEvent('order:selesai'));
                    }

                    // Hentikan polling jika sudah SELESAI (tidak perlu lagi)
                    if (data.order_status === 'SELESAI') {
                        this.isPolling = false;
                        return;
                    }
                } else {
                    this.backoff = Math.min(8000, this.backoff + 500);
                }
            } catch (e) {
                // Error jaringan → naikkan backoff, jangan sampai crash
                this.backoff = Math.min(8000, this.backoff + 500);
            }

            setTimeout(() => this._poll(orderCode), this.backoff);
        },
    },
});
