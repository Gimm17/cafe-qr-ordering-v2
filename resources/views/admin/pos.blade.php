<x-admin-layout title="Kasir / POS">

{{-- ─── Styles ─────────────────────────────────────────── --}}
<style>
.pos-grid { display: grid; grid-template-columns: 1fr 400px; gap: 0; height: calc(100vh - 64px); overflow: hidden; }
@media(max-width:1024px){ .pos-grid { grid-template-columns: 1fr; height:auto; overflow:auto; } }

/* LEFT PANEL */
.pos-left { display:flex; flex-direction:column; overflow:hidden; background:#f9fafb; }
.pos-left-body { flex:1; overflow-y:auto; padding:16px; }

/* Product cards */
.product-card {
    background:#fff; border-radius:12px; overflow:hidden; cursor:pointer;
    border:2px solid transparent; transition:all .15s;
    display:flex; flex-direction:column;
}
.product-card:hover { border-color:#6366f1; box-shadow:0 2px 12px rgba(99,102,241,.15); }
.product-card img { width:100%; aspect-ratio:4/3; object-fit:cover; }
.product-card .pc-body { padding:10px 12px; flex:1; }
.product-card .pc-name { font-weight:600; font-size:13px; color:#1a1a1a; line-height:1.3; }
.product-card .pc-price { font-size:12px; color:#6366f1; font-weight:700; margin-top:4px; }
.product-card .pc-mod { font-size:10px; color:#888; margin-top:2px; }
.product-card .pc-add { margin:0 12px 12px; background:#6366f1; color:#fff; border:none; border-radius:8px; padding:7px; font-size:12px; font-weight:600; cursor:pointer; width:calc(100% - 24px); }
.product-card .pc-add:hover { background:#4f46e5; }

/* RIGHT PANEL */
.pos-right { display:flex; flex-direction:column; background:#fff; border-left:1px solid #e5e7eb; overflow:hidden; }
.pos-right-head { padding:16px; border-bottom:1px solid #f0f0f0; flex-shrink:0; }
.pos-right-body { flex:1; overflow-y:auto; padding:12px 16px; }
.pos-right-foot { padding:12px 16px 16px; border-top:1px solid #f0f0f0; flex-shrink:0; background:#fff; }

/* Cart item */
.cart-item { padding:10px 0; border-bottom:1px solid #f5f5f5; }
.cart-item:last-child { border-bottom:none; }
.cart-item-top { display:flex; justify-content:space-between; align-items:flex-start; gap:8px; }
.cart-name { font-size:13px; font-weight:600; color:#1a1a1a; flex:1; }
.cart-mods { font-size:11px; color:#888; margin-top:2px; }
.cart-controls { display:flex; align-items:center; gap:4px; margin-top:8px; }
.qty-btn { width:28px; height:28px; border:1px solid #e5e7eb; background:#f9fafb; border-radius:6px; font-size:14px; font-weight:700; cursor:pointer; display:flex;align-items:center;justify-content:center; }
.qty-btn:hover { background:#f0f0f0; }
.qty-num { min-width:32px; text-align:center; font-weight:700; font-size:14px; }
.cart-price { font-size:13px; font-weight:700; color:#1a1a1a; text-align:right; white-space:nowrap; }
.cart-del { background:none; border:none; color:#ef4444; cursor:pointer; padding:4px; font-size:14px; }

/* Note input */
.note-input { width:100%; margin-top:6px; font-size:12px; padding:5px 8px; border:1px solid #e5e7eb; border-radius:6px; outline:none; color:#555; }
.note-input:focus { border-color:#6366f1; }

/* Empty cart */
#emptyCart { text-align:center; padding:40px 20px; color:#bbb; }
#emptyCart svg { width:48px; height:48px; margin:0 auto 12px; opacity:.4; }

/* Payment toggle */
.pay-toggle { display:flex; gap:8px; margin-bottom:12px; }
.pay-btn { flex:1; padding:10px; border:2px solid #e5e7eb; border-radius:10px; background:#fff; font-size:13px; font-weight:600; cursor:pointer; color:#555; transition:all .12s; }
.pay-btn.active-cash { border-color:#10b981; background:#ecfdf5; color:#059669; }
.pay-btn.active-qris { border-color:#8b5cf6; background:#f5f3ff; color:#7c3aed; }

/* Kembalian */
.kembalian-ok { color:#10b981; font-weight:700; }
.kembalian-err { color:#ef4444; font-weight:700; }

/* Category tabs */
.cat-tabs { display:flex; gap:6px; padding:12px 16px; overflow-x:auto; border-bottom:1px solid #f0f0f0; flex-shrink:0; }
.cat-tabs::-webkit-scrollbar { height:3px; }
.cat-tab { white-space:nowrap; padding:6px 14px; border-radius:20px; font-size:12px; font-weight:600; cursor:pointer; border:none; background:#f3f4f6; color:#555; transition:all .12s; flex-shrink:0; }
.cat-tab.active { background:#6366f1; color:#fff; }
.cat-tab:hover:not(.active) { background:#e0e7ff; color:#4f46e5; }

/* Search */
.search-bar { position:relative; padding:12px 16px 0; flex-shrink:0; }
.search-bar input { width:100%; padding:9px 12px 9px 36px; border:1px solid #e5e7eb; border-radius:10px; font-size:13px; outline:none; background:#fff; }
.search-bar input:focus { border-color:#6366f1; }
.search-bar svg { position:absolute; left:28px; top:22px; width:16px; height:16px; color:#9ca3af; }

/* Totals row */
.total-row { display:flex; justify-content:space-between; align-items:center; padding:3px 0; }
.total-row.grand { font-weight:800; font-size:18px; color:#1a1a1a; padding-top:8px; border-top:2px dashed #e5e7eb; margin-top:4px; }

/* Modal base */
.pos-modal { display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,.5); backdrop-filter:blur(4px); justify-content:center; align-items:center; padding:16px; }
.pos-modal.open { display:flex; }
.pos-modal-box { background:#fff; border-radius:20px; width:100%; max-width:480px; max-height:85vh; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.2); }
.modal-head { padding:20px 24px 16px; border-bottom:1px solid #f0f0f0; }
.modal-head h3 { font-size:17px; font-weight:700; color:#1a1a1a; }
.modal-head p { font-size:13px; color:#888; margin-top:4px; }
.modal-body { padding:20px 24px; overflow-y:auto; flex:1; }
.modal-foot { padding:16px 24px; border-top:1px solid #f0f0f0; display:flex; gap:10px; }
.modal-foot button { flex:1; padding:12px; border:none; border-radius:12px; font-size:14px; font-weight:700; cursor:pointer; }
.btn-primary-modal { background:#6366f1; color:#fff; }
.btn-primary-modal:hover { background:#4f46e5; }
.btn-success-modal { background:#10b981; color:#fff; }
.btn-success-modal:hover { background:#059669; }
.btn-outline-modal { background:#f5f5f5; color:#555; }
.btn-outline-modal:hover { background:#eee; }

/* QRIS modal */
.qris-img { max-width:260px; margin:0 auto; display:block; border-radius:12px; border:2px solid #e5e7eb; }
.qris-total { text-align:center; font-size:28px; font-weight:900; color:#7c3aed; margin:12px 0; }
.qris-amount-label { text-align:center; font-size:13px; color:#888; }

/* Modifier groups */
.mod-group { margin-bottom:16px; }
.mod-group-name { font-size:12px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:.5px; margin-bottom:8px; }
.mod-options { display:flex; flex-wrap:wrap; gap:6px; }
.mod-option { display:flex; align-items:center; gap:6px; padding:7px 12px; border:1px solid #e5e7eb; border-radius:8px; cursor:pointer; font-size:13px; transition:all .12s; }
.mod-option input { accent-color:#6366f1; }
.mod-option:has(input:checked) { border-color:#6366f1; background:#eef2ff; }

/* Success modal */
.success-icon { width:72px; height:72px; background:#ecfdf5; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; }
.success-icon svg { width:36px; height:36px; color:#10b981; }
.order-code-display { font-size:22px; font-weight:900; letter-spacing:1px; color:#1a1a1a; text-align:center; margin:8px 0; }

/* Proses btn */
.btn-proses { width:100%; padding:14px; background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; border:none; border-radius:14px; font-size:15px; font-weight:700; cursor:pointer; margin-top:12px; display:flex; align-items:center; justify-content:center; gap:8px; transition:all .15s; }
.btn-proses:hover { transform:translateY(-1px); box-shadow:0 4px 20px rgba(99,102,241,.4); }
.btn-proses:disabled { opacity:.6; cursor:not-allowed; transform:none; }

/* Fulfillment pills */
.ful-btn { flex:1; padding:7px; border:1px solid #e5e7eb; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; background:#fff; color:#555; }
.ful-btn.active { border-color:#6366f1; background:#eef2ff; color:#4f46e5; }
</style>

<div class="pos-grid">
    {{-- ═══════════════════════════════════════════════
         PANEL KIRI — Katalog Produk
    ═══════════════════════════════════════════════── --}}
    <div class="pos-left">
        {{-- Search --}}
        <div class="search-bar">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" id="searchInput" placeholder="Cari produk..." oninput="filterProducts()">
        </div>

        {{-- Category Tabs --}}
        <div class="cat-tabs" id="catTabs">
            <button class="cat-tab active" onclick="filterCat(0, this)">Semua</button>
            @foreach($categories as $cat)
            <button class="cat-tab" onclick="filterCat({{ $cat->id }}, this)">{{ $cat->name }}</button>
            @endforeach
        </div>

        {{-- Product Grid --}}
        <div class="pos-left-body">
            <div id="productGrid" class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                @foreach($products as $p)
                <div class="product-card" data-id="{{ $p->id }}" data-cat="{{ $p->category_id }}" data-name="{{ strtolower($p->name) }}" onclick="addToCart({{ $p->id }})">
                    @if($p->image_url)
                    <img src="{{ asset($p->image_url) }}" alt="{{ $p->name }}" loading="lazy">
                    @else
                    <div style="width:100%;aspect-ratio:4/3;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                        <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#d1d5db"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                    <div class="pc-body">
                        <div class="pc-name">{{ $p->name }}</div>
                        <div class="pc-price">Rp {{ number_format($p->base_price, 0, ',', '.') }}</div>
                        @if($p->activeModGroups->isNotEmpty())
                        <div class="pc-mod">⚙️ {{ $p->activeModGroups->count() }} varian</div>
                        @endif
                    </div>
                    <button class="pc-add" onclick="event.stopPropagation(); addToCart({{ $p->id }})">+ Tambah</button>
                </div>
                @endforeach
            </div>
            <div id="noResults" class="hidden text-center py-10 text-gray-400 text-sm">Tidak ada produk ditemukan.</div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         PANEL KANAN — Order Panel
    ═══════════════════════════════════════════════── --}}
    <div class="pos-right">
        {{-- Header --}}
        <div class="pos-right-head">
            <h2 style="font-size:16px;font-weight:800;color:#1a1a1a;margin-bottom:10px;">🧾 Order Kasir</h2>

            {{-- Customer name --}}
            <input type="text" id="customer_name" placeholder="Nama Customer *" required
                   style="width:100%;padding:8px 12px;border:1px solid #e5e7eb;border-radius:9px;font-size:13px;margin-bottom:8px;outline:none;">

            {{-- Table & Fulfillment --}}
            <div style="display:flex;gap:8px;margin-bottom:0;">
                <select id="table_id" style="flex:1;padding:8px 10px;border:1px solid #e5e7eb;border-radius:9px;font-size:13px;outline:none;color:#555;">
                    <option value="">Tanpa Meja</option>
                    @foreach($tables as $t)
                    <option value="{{ $t->id }}">Meja {{ $t->table_no }}</option>
                    @endforeach
                </select>
                <div style="display:flex;gap:4px;flex:1;">
                    <button class="ful-btn active" id="fulDineIn" onclick="setFulfillment('DINE_IN')">🍽 Dine In</button>
                    <button class="ful-btn" id="fulPickup" onclick="setFulfillment('PICKUP')">🥡 Takeaway</button>
                </div>
            </div>
        </div>

        {{-- Cart --}}
        <div class="pos-right-body" id="cartBody">
            <div id="emptyCart">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <p>Keranjang kosong</p>
                <p style="font-size:11px;margin-top:4px;">Klik produk untuk menambahkan</p>
            </div>
            <div id="cartItems"></div>
        </div>

        {{-- Footer: totals + payment --}}
        <div class="pos-right-foot">
            <div id="totalSection" class="hidden">
                <div class="total-row">
                    <span style="font-size:13px;color:#888;">Subtotal</span>
                    <span id="subtotalDisplay" style="font-size:13px;font-weight:600;">Rp 0</span>
                </div>
                <div class="total-row grand">
                    <span>Grand Total</span>
                    <span id="grandTotalDisplay">Rp 0</span>
                </div>
            </div>

            {{-- Payment method --}}
            <div class="pay-toggle mt-3">
                <button class="pay-btn" id="btnCash" onclick="setPayment('CASH')">💵 Tunai</button>
                <button class="pay-btn" id="btnQris" onclick="setPayment('QRIS')">📱 QRIS</button>
            </div>

            {{-- Cash input --}}
            @if($posCashChangeEnabled === '1')
            <div id="cashSection" class="hidden">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                    <label style="font-size:12px;color:#888;white-space:nowrap;">Uang Diterima</label>
                    <input type="number" id="cashInput" placeholder="0" min="0" step="1000"
                           oninput="calcChange()"
                           style="flex:1;padding:7px 10px;border:1px solid #e5e7eb;border-radius:8px;font-size:13px;text-align:right;outline:none;">
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:4px 2px;">
                    <span style="font-size:12px;color:#888;">Kembalian</span>
                    <span id="kembalian" style="font-size:14px;">Rp 0</span>
                </div>
            </div>
            @endif

            <button class="btn-proses" id="btnProses" onclick="openConfirmModal()" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Proses Pesanan
            </button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════
     MODAL: Modifier Picker
═══════════════════════════════════════════════── --}}
<div class="pos-modal" id="modModal">
    <div class="pos-modal-box">
        <div class="modal-head">
            <h3 id="modModalName">Pilih Varian</h3>
            <p id="modModalPrice"></p>
        </div>
        <div class="modal-body" id="modModalBody"></div>
        <div class="modal-foot">
            <button class="btn-outline-modal" onclick="closeModal('modModal')">Batal</button>
            <button class="btn-primary-modal" onclick="addFromModal()">+ Tambahkan ke Keranjang</button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════
     MODAL: Konfirmasi Order
═══════════════════════════════════════════════── --}}
<div class="pos-modal" id="confirmModal">
    <div class="pos-modal-box" style="max-width:400px;">
        <div class="modal-head">
            <h3>⚠️ Konfirmasi Pesanan</h3>
            <p>Cek kembali sebelum diproses</p>
        </div>
        <div class="modal-body" id="confirmModalBody"></div>
        <div class="modal-foot">
            <button class="btn-outline-modal" onclick="closeModal('confirmModal')">Periksa Lagi</button>
            <button class="btn-success-modal" id="btnFinalSubmit" onclick="submitOrder()">✅ Ya, Proses!</button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════
     MODAL: Tunggu Pembayaran QRIS
═══════════════════════════════════════════════── --}}
<div class="pos-modal" id="qrisModal">
    <div class="pos-modal-box" style="max-width:400px;">
        <div class="modal-head" style="text-align:center;">
            <h3>📱 Menunggu Pembayaran QRIS</h3>
        </div>
        <div class="modal-body" style="text-align:center;">
            @if($posQrisImage)
            <img src="{{ asset($posQrisImage) }}" alt="QRIS" class="qris-img" id="qrisImg">
            @else
            <div class="qris-img" style="aspect-ratio:1;display:flex;align-items:center;justify-content:center;background:#f3f4f6;color:#888;font-size:13px;">
                Belum ada gambar QRIS.<br>Upload di Pengaturan.
            </div>
            @endif
            <div class="qris-amount-label">Nominal yang harus dibayar</div>
            <div class="qris-total" id="qrisNominal">Rp 0</div>
            <p style="font-size:12px;color:#aaa;">Polling otomatis tiap 3 detik...</p>
        </div>
        <div class="modal-foot">
            <button class="btn-outline-modal" id="btnCancelQris" onclick="cancelQrisModal()">Batalkan</button>
            <button class="btn-success-modal" id="btnConfirmQris" onclick="confirmQris()">✅ Sudah Dibayar</button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════
     MODAL: Sukses
═══════════════════════════════════════════════── --}}
<div class="pos-modal" id="successModal">
    <div class="pos-modal-box" style="max-width:380px;">
        <div class="modal-body" style="text-align:center;padding:32px 24px;">
            <div class="success-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 style="font-size:20px;font-weight:800;">Pesanan Berhasil! 🎉</h2>
            <div class="order-code-display" id="successOrderCode"></div>
            <p style="font-size:13px;color:#888;margin-top:4px;" id="successTotal"></p>
        </div>
        <div class="modal-foot" style="padding-top:0;">
            <button class="btn-outline-modal" onclick="printReceipt()">🖨️ Cetak Struk</button>
            <button class="btn-success-modal" onclick="resetPos()">➕ Pesanan Baru</button>
        </div>
    </div>
</div>

<x-slot:scripts>
<script>
// ── Constants ────────────────────────────────────────────
const POS_ORDER_URL     = @json(route('admin.pos.order'));
const POS_QRIS_URL      = @json(route('admin.pos.confirm-qris', ['order' => '__ID__']));
const POS_CHECK_URL     = @json(route('admin.pos.check-payment', ['order' => '__ID__']));
const CSRF_TOKEN        = @json(csrf_token());
const AUDIO_ENABLED     = @json($posAudioEnabled === '1');
const CASH_CHANGE_EN    = @json($posCashChangeEnabled === '1');

// Build products array from blade
const ALL_PRODUCTS = @json($productsJson);

// ── State ────────────────────────────────────────────────
let cart = [];
let selectedPayment = 'CASH';
let selectedFulfillment = 'DINE_IN';
let grandTotal = 0;
let activeOrder = null;      // { id, order_code, grand_total, receipt_url }
let qrisPollTimer = null;
let selectedProduct = null;  // product clicked for modifier modal

// ── Init ─────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    setPayment('CASH');
    renderCart();
});

// ── Filtering ────────────────────────────────────────────
let activeCat = 0;

function filterCat(catId, el) {
    activeCat = catId;
    document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    filterProducts();
}

function filterProducts() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.product-card');
    let visible = 0;
    cards.forEach(card => {
        const matchCat  = !activeCat || card.dataset.cat == activeCat;
        const matchName = !q || card.dataset.name.includes(q);
        const show = matchCat && matchName;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById('noResults').classList.toggle('hidden', visible > 0);
}

// ── Fulfillment ───────────────────────────────────────────
function setFulfillment(type) {
    selectedFulfillment = type;
    document.getElementById('fulDineIn').classList.toggle('active', type === 'DINE_IN');
    document.getElementById('fulPickup').classList.toggle('active', type === 'PICKUP');
}

// ── Payment method ────────────────────────────────────────
function setPayment(method) {
    selectedPayment = method;
    document.getElementById('btnCash').className = 'pay-btn' + (method === 'CASH' ? ' active-cash' : '');
    document.getElementById('btnQris').className = 'pay-btn' + (method === 'QRIS' ? ' active-qris' : '');

    if (CASH_CHANGE_EN) {
        document.getElementById('cashSection')?.classList.toggle('hidden', method !== 'CASH');
    }
    calcChange();
}

function calcChange() {
    if (!CASH_CHANGE_EN) return;
    const el = document.getElementById('cashInput');
    const kemEl = document.getElementById('kembalian');
    if (!el || !kemEl) return;
    const received = parseInt(el.value) || 0;
    const change = received - grandTotal;
    kemEl.textContent = 'Rp ' + change.toLocaleString('id-ID');
    kemEl.className = change >= 0 ? 'kembalian-ok' : 'kembalian-err';
}

// ── Add to cart ───────────────────────────────────────────
function addToCart(productId) {
    const p = ALL_PRODUCTS.find(x => x.id === productId);
    if (!p) return;
    if (p.modGroups.length > 0) {
        openModModal(p);
    } else {
        pushToCart(p, [], p.base_price);
    }
}

function pushToCart(product, modifiers, unitPrice) {
    const key = product.id + '_' + modifiers.map(m => m.id).sort().join('_');
    const existing = cart.find(i => i.key === key);
    if (existing) {
        existing.qty++;
    } else {
        cart.push({ key, product_id: product.id, name: product.name, unit_price: unitPrice, qty: 1, note: '', modifiers });
    }
    renderCart();
    closeModal('modModal');
}

// ── Cart render ───────────────────────────────────────────
function renderCart() {
    const empty  = document.getElementById('emptyCart');
    const items  = document.getElementById('cartItems');
    const totSec = document.getElementById('totalSection');
    const btnP   = document.getElementById('btnProses');

    if (cart.length === 0) {
        empty.style.display = '';
        items.innerHTML = '';
        totSec.classList.add('hidden');
        btnP.disabled = true;
        grandTotal = 0;
        document.getElementById('subtotalDisplay').textContent = 'Rp 0';
        document.getElementById('grandTotalDisplay').textContent = 'Rp 0';
        return;
    }

    empty.style.display = 'none';
    totSec.classList.remove('hidden');
    btnP.disabled = false;

    let subtotal = 0;
    items.innerHTML = cart.map((item, idx) => {
        const lineTotal = item.unit_price * item.qty;
        subtotal += lineTotal;
        const modSummary = item.modifiers.map(m => m.option_name).join(', ');
        return `
        <div class="cart-item">
            <div class="cart-item-top">
                <div style="flex:1">
                    <div class="cart-name">${escHtml(item.name)}</div>
                    ${modSummary ? `<div class="cart-mods">${escHtml(modSummary)}</div>` : ''}
                </div>
                <button class="cart-del" onclick="removeCartItem(${idx})">🗑</button>
            </div>
            <div class="cart-controls">
                <button class="qty-btn" onclick="changeQty(${idx}, -1)">−</button>
                <span class="qty-num">${item.qty}</span>
                <button class="qty-btn" onclick="changeQty(${idx}, 1)">+</button>
                <span style="flex:1;"></span>
                <span class="cart-price">Rp ${lineTotal.toLocaleString('id-ID')}</span>
            </div>
            <input class="note-input" type="text" placeholder="Catatan (opsional)" value="${escHtml(item.note)}"
                   oninput="cart[${idx}].note = this.value" maxlength="200">
        </div>`;
    }).join('');

    grandTotal = subtotal;
    document.getElementById('subtotalDisplay').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('grandTotalDisplay').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
    calcChange();
}

function removeCartItem(idx) { cart.splice(idx, 1); renderCart(); }
function changeQty(idx, delta) {
    cart[idx].qty = Math.max(1, cart[idx].qty + delta);
    renderCart();
}

function escHtml(str) {
    return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Modifier Modal ────────────────────────────────────────
function openModModal(product) {
    selectedProduct = product;
    document.getElementById('modModalName').textContent = product.name;
    document.getElementById('modModalPrice').textContent = 'Harga dasar: Rp ' + product.base_price.toLocaleString('id-ID');

    const body = product.modGroups.map(g => `
        <div class="mod-group">
            <div class="mod-group-name">${escHtml(g.name)} ${g.is_required ? '<span style="color:#ef4444">*</span>' : '(opsional)'}</div>
            <div class="mod-options">
                ${g.options.map(o => `
                    <label class="mod-option">
                        <input type="${g.max_select === 1 ? 'radio' : 'checkbox'}"
                               name="mod_${g.id}"
                               value="${o.id}"
                               data-group="${g.id}"
                               data-group-name="${escHtml(g.name)}"
                               data-option-name="${escHtml(o.name)}"
                               data-price="${o.price_modifier}">
                        ${escHtml(o.name)}${o.price_modifier > 0 ? ' <span style="color:#6366f1;font-size:11px;">+Rp ' + o.price_modifier.toLocaleString('id-ID') + '</span>' : ''}
                    </label>`).join('')}
            </div>
        </div>`).join('');

    document.getElementById('modModalBody').innerHTML = body;
    openModal('modModal');
}

function addFromModal() {
    if (!selectedProduct) return;

    // Check required groups
    let totalExtra = 0;
    const modifiers = [];
    let missingRequired = false;

    for (const g of selectedProduct.modGroups) {
        const checked = document.querySelectorAll(`input[name="mod_${g.id}"]:checked`);
        if (g.is_required && checked.length === 0) {
            missingRequired = true;
            break;
        }
        checked.forEach(inp => {
            totalExtra += parseInt(inp.dataset.price) || 0;
            modifiers.push({
                id:             parseInt(inp.value),
                group_name:     inp.dataset.groupName,
                option_name:    inp.dataset.optionName,
                price_modifier: parseInt(inp.dataset.price) || 0,
            });
        });
    }

    if (missingRequired) {
        alert('Pilih opsi wajib terlebih dahulu!');
        return;
    }

    pushToCart(selectedProduct, modifiers, selectedProduct.base_price + totalExtra);
}

// ── Confirm modal ─────────────────────────────────────────
function openConfirmModal() {
    const name = document.getElementById('customer_name').value.trim();
    if (!name) { document.getElementById('customer_name').focus(); alert('Nama customer wajib diisi!'); return; }
    if (cart.length === 0) return;

    const body = `
        <div style="font-size:13px;color:#555;margin-bottom:12px;">
            <b>Customer:</b> ${escHtml(name)}<br>
            <b>Metode:</b> ${selectedPayment}<br>
            <b>Total:</b> <span style="color:#6366f1;font-weight:700;">Rp ${grandTotal.toLocaleString('id-ID')}</span>
        </div>
        <ul style="font-size:12px;color:#888;list-style:none;padding:0;">
            ${cart.map(i => `<li>• ${escHtml(i.name)} x${i.qty} — Rp ${(i.unit_price*i.qty).toLocaleString('id-ID')}</li>`).join('')}
        </ul>`;
    document.getElementById('confirmModalBody').innerHTML = body;
    openModal('confirmModal');
}

// ── Submit Order ──────────────────────────────────────────
async function submitOrder() {
    const name = document.getElementById('customer_name').value.trim();
    const tableId = document.getElementById('table_id').value;
    const cashReceived = CASH_CHANGE_EN && selectedPayment === 'CASH'
        ? (parseInt(document.getElementById('cashInput')?.value) || null) : null;

    document.getElementById('btnFinalSubmit').disabled = true;
    document.getElementById('btnFinalSubmit').textContent = 'Memproses...';

    try {
        const res = await fetch(POS_ORDER_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
            body: JSON.stringify({
                customer_name:    name,
                table_id:         tableId || null,
                fulfillment_type: selectedFulfillment,
                payment_method:   selectedPayment,
                cash_received:    cashReceived,
                items: cart.map(i => ({
                    product_id: i.product_id,
                    name:       i.name,
                    unit_price: i.unit_price,
                    qty:        i.qty,
                    note:       i.note,
                    modifiers:  i.modifiers,
                })),
            }),
        });

        const data = await res.json();
        closeModal('confirmModal');

        if (data.success) {
            activeOrder = data.order;
            if (selectedPayment === 'QRIS') {
                openQrisModal(data.order);
            } else {
                openSuccessModal(data.order);
                if (AUDIO_ENABLED) speakNominal(data.order.grand_total, 'Tunai');
            }
        } else {
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan.'));
        }
    } catch (e) {
        alert('Network error: ' + e.message);
    } finally {
        document.getElementById('btnFinalSubmit').disabled = false;
        document.getElementById('btnFinalSubmit').textContent = '✅ Ya, Proses!';
    }
}

// ── QRIS Modal ────────────────────────────────────────────
function openQrisModal(order) {
    document.getElementById('qrisNominal').textContent = 'Rp ' + order.grand_total.toLocaleString('id-ID');
    openModal('qrisModal');
    startQrisPoll(order.id);
}

function startQrisPoll(orderId) {
    stopQrisPoll();
    qrisPollTimer = setInterval(async () => {
        try {
            const url = POS_CHECK_URL.replace('__ID__', orderId);
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            if (data.payment_status === 'PAID') {
                stopQrisPoll();
                closeModal('qrisModal');
                openSuccessModal(activeOrder);
                if (AUDIO_ENABLED) speakNominal(data.grand_total, 'QRIS');
            }
        } catch(e) {}
    }, 3000);
}

function stopQrisPoll() {
    if (qrisPollTimer) { clearInterval(qrisPollTimer); qrisPollTimer = null; }
}

async function confirmQris() {
    if (!activeOrder) return;
    document.getElementById('btnConfirmQris').disabled = true;
    try {
        const url = POS_QRIS_URL.replace('__ID__', activeOrder.id);
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
        });
        const data = await res.json();
        if (data.success) {
            stopQrisPoll();
            closeModal('qrisModal');
            openSuccessModal(activeOrder);
            if (AUDIO_ENABLED) speakNominal(data.grand_total, 'QRIS');
        } else {
            alert(data.message || 'Gagal konfirmasi.');
        }
    } catch(e) {
        alert('Error: ' + e.message);
    } finally {
        document.getElementById('btnConfirmQris').disabled = false;
    }
}

function cancelQrisModal() {
    stopQrisPoll();
    closeModal('qrisModal');
}

// ── Success Modal ─────────────────────────────────────────
function openSuccessModal(order) {
    document.getElementById('successOrderCode').textContent = order.order_code;
    document.getElementById('successTotal').textContent = 'Total: Rp ' + order.grand_total.toLocaleString('id-ID');
    openModal('successModal');
}

function printReceipt() {
    if (activeOrder?.receipt_url) window.open(activeOrder.receipt_url, '_blank');
}

function resetPos() {
    cart = [];
    activeOrder = null;
    document.getElementById('customer_name').value = '';
    document.getElementById('table_id').value = '';
    if (CASH_CHANGE_EN) {
        const ci = document.getElementById('cashInput');
        if (ci) ci.value = '';
    }
    renderCart();
    closeModal('successModal');
}

// ── Modal helpers ─────────────────────────────────────────
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

// ── Audio TTS (Web Speech API) ────────────────────────────
function numberToWords(n) {
    if (n === 0) return 'nol';
    const s = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan',
               'sepuluh', 'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas',
               'enam belas', 'tujuh belas', 'delapan belas', 'sembilan belas'];
    if (n < 20) return s[n];
    if (n < 100) return (n < 20 ? '' : ['','','dua','tiga','empat','lima','enam','tujuh','delapan','sembilan'][Math.floor(n/10)] + ' puluh ') + (n%10 ? s[n%10] : '').trim();
    if (n < 200) return 'seratus ' + (n > 100 ? numberToWords(n - 100) : '');
    if (n < 1000) return s[Math.floor(n/100)] + ' ratus ' + (n%100 ? numberToWords(n%100) : '');
    if (n < 2000) return 'seribu ' + (n > 1000 ? numberToWords(n - 1000) : '');
    if (n < 1000000) return numberToWords(Math.floor(n/1000)) + ' ribu ' + (n%1000 ? numberToWords(n%1000) : '');
    if (n < 1000000000) return numberToWords(Math.floor(n/1000000)) + ' juta ' + (n%1000000 ? numberToWords(n%1000000) : '');
    return numberToWords(Math.floor(n/1000000000)) + ' miliar ' + (n%1000000000 ? numberToWords(n%1000000000) : '');
}

function speakNominal(amount, method) {
    if (!window.speechSynthesis) return;
    const words = numberToWords(amount).trim();
    const text = `Pembayaran ${method} diterima. ${words} rupiah.`;
    const utt = new SpeechSynthesisUtterance(text);
    utt.lang = 'id-ID';
    utt.rate = 0.9;
    window.speechSynthesis.speak(utt);
}
</script>
</x-slot:scripts>

</x-admin-layout>
