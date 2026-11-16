<template>
  <div>
    <!-- ── STATUS SUMMARY ──────────────────────────────────────── -->
    <div class="ui-card p-5 mb-4">
      <div class="flex items-center justify-between gap-3">
        <div>
          <p class="text-xs text-muted">Status Pesanan</p>
          <p class="text-lg font-extrabold text-gray-900">{{ store.orderStatus }}</p>
        </div>
        <div class="text-right">
          <p class="text-xs text-muted">Pembayaran</p>
          <p class="font-bold" :style="store.isPaid ? 'color:#16a34a' : 'color:#dc2626'">
            {{ store.isPaid ? '✓ Paid' : 'Unpaid' }}
          </p>
        </div>
      </div>

      <!-- Unpaid Warning -->
      <div v-if="!store.isPaid" class="mt-4 ui-card-flat p-4" style="border:1px solid #fcd34d; background:#fffbeb;">
        <div class="flex items-start justify-between gap-3">
          <div>
            <p class="font-semibold" style="color:#92400e;">Menunggu pembayaran</p>
            <p class="text-xs" style="color:#b45309;">Pesanan akan expired jika belum dibayar.</p>
          </div>
          <div class="text-right">
            <p class="text-xs" style="color:#b45309;">Sisa waktu</p>
            <p class="text-lg font-extrabold" style="color:#78350f;">{{ countdown }}</p>
          </div>
        </div>
        <a v-if="payNowVisible && payUrl"
           :href="payUrl"
           class="mt-3 w-full tap-44 px-5 py-3 font-semibold ui-btn transition-colors"
           style="display:flex;align-items:center;justify-content:center;background:#234AE6;color:#fff;">
          💳 Bayar Sekarang
        </a>
      </div>
    </div>

    <!-- ── PROGRESS ─────────────────────────────────────────────── -->
    <div class="ui-card p-5 mb-4">
      <h2 class="font-semibold tracking-tight mb-4">Progress</h2>
      <div class="space-y-3">
        <div v-for="(step, idx) in stepList" :key="step.key" class="flex items-start gap-3">
          <!-- Dot -->
          <div class="mt-0.5 w-7 h-7 rounded-full flex items-center justify-center border"
               :style="stepDone(idx)
                 ? 'background:#234AE6;border-color:#234AE6;color:#fff'
                 : 'background:#fff;border-color:rgba(4,3,96,.10);color:#9ca3af'">
            <!-- checkmark -->
            <svg v-if="stepDone(idx)" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span v-else class="text-xs font-bold">{{ idx + 1 }}</span>
          </div>
          <!-- Label -->
          <div class="flex-1">
            <p class="font-semibold"
               :style="stepActive(idx) ? 'color:#0B1020' : (stepDone(idx) ? 'color:#1f2937' : 'color:#6b7280')">
              {{ step.label }}
            </p>
            <p class="text-xs text-muted">{{ step.desc }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useOrderStore } from '../stores/useOrderStore';

// ── Props (dari data-* attributes di Blade) ───────────────────────────────
const props = defineProps({
    orderCode:          { type: String, required: true },
    initialOrderStatus: { type: String, default: 'DITERIMA' },
    initialPayStatus:   { type: String, default: 'UNPAID' },
    expiresTimestamp:   { type: String, default: '' },
    payUrl:             { type: String, default: '' },
});

// ── Pinia Store ────────────────────────────────────────────────────────────
const store = useOrderStore();
// Inisialisasi dari nilai server (Blade) supaya tidak blank saat pertama load
store.orderStatus   = props.initialOrderStatus;
store.paymentStatus = props.initialPayStatus;

// ── Step definitions (array, lebih reliable daripada object iteration) ────
const stepList = [
    { key: 'DITERIMA', label: 'Diterima', desc: 'Pesanan masuk' },
    { key: 'DIPROSES', label: 'Diproses', desc: 'Sedang dibuat' },
    { key: 'READY',    label: 'Ready',    desc: 'Siap diambil/diantar' },
    { key: 'SELESAI',  label: 'Selesai',  desc: 'Pesanan selesai' },
];
const statusKeys = stepList.map(s => s.key);

// computed: index step saat ini (0-3 atau -1 kalau tidak dikenal)
const currentIdx = computed(() => statusKeys.indexOf(store.orderStatus));

const stepDone   = (idx) => currentIdx.value !== -1 && idx <= currentIdx.value;
const stepActive = (idx) => currentIdx.value !== -1 && idx === currentIdx.value;

// ── Countdown Timer ────────────────────────────────────────────────────────
const countdown    = ref('--:--');
const payNowVisible = ref(true);
let countdownTimer  = null;

function tickCountdown() {
    const ts = parseInt(props.expiresTimestamp);
    if (!ts) return;
    const diff = ts - Date.now();
    if (diff <= 0) {
        countdown.value = '00:00';
        payNowVisible.value = false;
        return;
    }
    const m = Math.floor(diff / 60000);
    const s = Math.floor((diff % 60000) / 1000);
    countdown.value = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
}

// ── Notification Handlers ──────────────────────────────────────────────────
function onOrderReady() {
    if (navigator.vibrate) navigator.vibrate([200, 100, 200]);
    try { new Audio('/custom_notification.mp3').play().catch(() => {}); } catch {}
    if (window.Swal) {
        window.Swal.fire({
            icon: 'success',
            title: 'Pesanan Ready! 🎉',
            text: 'Pesananmu sudah siap! Silakan ambil di kasir.',
            confirmButtonColor: '#234AE6',
        });
    }
}

function onOrderSelesai() {
    // Tampilkan review section yang masih di Blade
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
        setTimeout(() => reviewEl.scrollIntoView({ behavior: 'smooth', block: 'center' }), 600);
    }
}

// ── Lifecycle ──────────────────────────────────────────────────────────────
onMounted(() => {
    store.startPolling(props.orderCode);
    tickCountdown();
    if (props.expiresTimestamp) countdownTimer = setInterval(tickCountdown, 1000);
    window.addEventListener('order:ready',   onOrderReady);
    window.addEventListener('order:selesai', onOrderSelesai);
});

onUnmounted(() => {
    clearInterval(countdownTimer);
    window.removeEventListener('order:ready',   onOrderReady);
    window.removeEventListener('order:selesai', onOrderSelesai);
});
</script>
