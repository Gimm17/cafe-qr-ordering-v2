<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $order->order_code }}</title>
    <style>
        /* ── Reset & Base ────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            color: #1a1a1a;
            line-height: 1.5;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ── Receipt Container ───────────────────────────── */
        .receipt {
            max-width: 420px;
            margin: 24px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(0,0,0,.08);
            overflow: hidden;
        }

        /* ── Header ──────────────────────────────────────── */
        .receipt-header {
            text-align: center;
            padding: 28px 24px 20px;
            border-bottom: 2px dashed #e5e5e5;
        }
        .receipt-logo {
            width: 64px;
            height: 64px;
            object-fit: contain;
            margin: 0 auto 10px;
            display: block;
            border-radius: 8px;
        }
        .receipt-header .cafe-name {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #1a1a1a;
        }
        .receipt-header .cafe-tagline {
            font-size: 12px;
            color: #888;
            margin-top: 2px;
        }
        .receipt-header .order-code {
            font-size: 16px;
            font-weight: 700;
            margin-top: 14px;
            color: #333;
            letter-spacing: 0.5px;
        }
        .receipt-header .order-date {
            font-size: 12px;
            color: #888;
            margin-top: 4px;
        }

        /* ── Info Grid ───────────────────────────────────── */
        .receipt-info {
            padding: 16px 24px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .receipt-info .info-item {
            font-size: 12px;
        }
        .receipt-info .info-label {
            color: #888;
            font-size: 11px;
        }
        .receipt-info .info-value {
            font-weight: 600;
            color: #333;
        }

        /* ── Status Section ──────────────────────────────── */
        .receipt-status {
            padding: 14px 24px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            border-bottom: 2px dashed #e5e5e5;
        }
        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .chip-success {
            background: #ecfdf5;
            color: #065f46;
        }
        .chip-paid {
            background: #eff6ff;
            color: #1e40af;
        }
        .chip-method {
            background: #faf5ff;
            color: #6b21a8;
        }

        /* ── Items Table ─────────────────────────────────── */
        .receipt-items {
            padding: 0 24px;
        }
        .receipt-items .items-title {
            font-size: 12px;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 16px 0 10px;
        }
        .item-row {
            padding: 10px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .item-row:last-child {
            border-bottom: none;
        }
        .item-main {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
        }
        .item-name {
            font-size: 13px;
            font-weight: 600;
            color: #1a1a1a;
        }
        .item-mods {
            font-size: 11px;
            color: #888;
            margin-top: 2px;
        }
        .item-note {
            font-size: 11px;
            color: #b45309;
            margin-top: 2px;
        }
        .item-price-col {
            text-align: right;
            flex-shrink: 0;
        }
        .item-qty {
            font-size: 11px;
            color: #888;
        }
        .item-subtotal {
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        /* ── Total ───────────────────────────────────────── */
        .receipt-total {
            margin: 0 24px;
            padding: 16px 0;
            border-top: 2px dashed #e5e5e5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .total-label {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a1a;
        }
        .total-amount {
            font-size: 20px;
            font-weight: 800;
            color: #1a1a1a;
        }

        /* ── Footer ──────────────────────────────────────── */
        .receipt-footer {
            text-align: center;
            padding: 20px 24px 28px;
            border-top: 1px solid #f0f0f0;
        }
        .receipt-footer .thankyou {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        .receipt-footer .footer-info {
            font-size: 11px;
            color: #aaa;
            margin-top: 6px;
        }

        /* ── Buttons (screen only) ───────────────────────── */
        .receipt-actions {
            max-width: 420px;
            margin: 0 auto 32px;
            padding: 0 16px;
            display: flex;
            gap: 10px;
        }
        .btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all .15s;
        }
        .btn-primary {
            background: #4f46e5;
            color: #fff;
        }
        .btn-primary:hover { background: #4338ca; }
        .btn-outline {
            background: #fff;
            color: #555;
            border: 1px solid #ddd;
        }
        .btn-outline:hover { background: #f9f9f9; }

        /* ── Print Styles ────────────────────────────────── */
        @media print {
            body {
                background: #fff;
                margin: 0;
                padding: 0;
            }
            .receipt {
                max-width: 100%;
                margin: 0;
                border-radius: 0;
                box-shadow: none;
            }
            .receipt-actions {
                display: none !important;
            }

            /* ── Thermal 80mm support ── */
            @page {
                margin: 6mm;
            }
        }

        /* ── Thermal 80mm media query ────────────────────── */
        @media print and (max-width: 80mm) {
            .receipt-header .cafe-name { font-size: 16px; }
            .receipt-header .order-code { font-size: 14px; }
            .receipt-info { grid-template-columns: 1fr; gap: 6px; }
            .total-amount { font-size: 16px; }
            @page { margin: 3mm; }
        }

        /* ── BW Theme ────────────────────────────────────── */
        @if(($receiptTheme ?? 'normal') === 'bw')
        .chip-success,
        .chip-paid,
        .chip-method {
            background: #f5f5f5 !important;
            color: #333 !important;
            border: 1px solid #ccc;
        }
        .receipt-header .cafe-tagline { color: #555; }
        .total-amount { color: #000 !important; }
        .item-note { color: #555 !important; }
        .receipt-logo { filter: grayscale(1); }
        @endif
    </style>
</head>
<body>

<div class="receipt">
    <!-- Header -->
    <div class="receipt-header">
        @if(($receiptShowLogo ?? '0') === '1' && !empty($receiptLogoUrl))
            <img src="{{ $receiptLogoUrl }}" alt="Logo" class="receipt-logo">
        @endif
        <div class="cafe-name">{{ $cafeName }}</div>
        <div class="cafe-tagline">{{ $cafeTagline }}</div>
        <div class="order-code">{{ $order->order_code }}</div>
        <div class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</div>
    </div>

    <!-- Info -->
    @if(($receiptShowCustomer ?? '1') === '1')
    <div class="receipt-info">
        <div class="info-item">
            <div class="info-label">Customer</div>
            <div class="info-value">{{ $order->customer_name }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Meja</div>
            <div class="info-value">{{ $order->table->table_no ?? '-' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Tipe</div>
            <div class="info-value">{{ $order->fulfillment_type === 'DINE_IN' ? 'Dine In' : 'Takeaway' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Tanggal</div>
            <div class="info-value">{{ $order->created_at->format('d/m/Y') }}</div>
        </div>
    </div>
    @endif



    <!-- Items -->
    <div class="receipt-items">
        <div class="items-title">Rincian Pesanan</div>
        @foreach($order->items as $item)
        <div class="item-row">
            <div class="item-main">
                <div>
                    <div class="item-name">{{ $item->product_name }}</div>
                    @if($item->mods->isNotEmpty())
                        <div class="item-mods">{{ $item->mods_summary }}</div>
                    @endif
                    @if($item->note)
                        <div class="item-note">📝 {{ $item->note }}</div>
                    @endif
                </div>
                <div class="item-price-col">
                    <div class="item-qty">{{ $item->qty }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}</div>
                    <div class="item-subtotal">Rp {{ number_format($item->line_total, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Payment Method -->
    @if(($receiptShowPayment ?? '1') === '1')
    <div style="margin:0 24px;padding:10px 0;border-top:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:12px;color:#888;">Metode Pembayaran</span>
        <span style="font-size:13px;font-weight:600;color:#333;">💳 {{ $paymentMethod }}</span>
    </div>
    @endif

    <!-- Total -->
    <div class="receipt-total">
        <span class="total-label">Grand Total</span>
        <span class="total-amount">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
    </div>

    <!-- Footer -->
    <div class="receipt-footer">
        <div class="thankyou">{{ $receiptFooterText ?? 'Terima kasih! 🙏' }}</div>
        @php
            $location = $receiptLocation ?? $cafeAddress ?? null;
        @endphp
        @if($location || $cafeWhatsapp)
        <div class="footer-info">
            @if($location){{ $location }}@endif
            @if($location && $cafeWhatsapp) • @endif
            @if($cafeWhatsapp)WA: {{ $cafeWhatsapp }}@endif
        </div>
        @endif
    </div>
</div>

<!-- Actions (hidden on print) -->
<div class="receipt-actions">
    <button class="btn btn-primary" onclick="window.print()">🖨️ Cetak / Save PDF</button>
    <a href="{{ $backUrl }}" class="btn btn-outline">← Kembali</a>
</div>

</body>
</html>
