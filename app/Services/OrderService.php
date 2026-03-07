<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemMod;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Create an order from a customer cart (QR flow).
     */
    public function createFromCart(
        int $tableId,
        string $customerName,
        string $fulfillmentType,
        array $cart,
        array $totals
    ): Order {
        return DB::transaction(function () use ($tableId, $customerName, $fulfillmentType, $cart, $totals) {
            $orderCode = $this->generateOrderCode('A');

            $order = Order::create([
                'order_code'       => $orderCode,
                'order_source'     => 'QR',
                'table_id'         => $tableId,
                'customer_name'    => $customerName,
                'fulfillment_type' => $fulfillmentType,
                'order_status'     => 'DITERIMA',
                'payment_status'   => 'UNPAID',
                'subtotal'         => $totals['subtotal'],
                'tax_amount'       => $totals['tax'],
                'service_amount'   => $totals['service'],
                'discount_amount'  => $totals['discount'],
                'grand_total'      => $totals['grand_total'],
            ]);

            foreach ($cart as $item) {
                $lineTotal = ((int)$item['unit_price']) * ((int)$item['qty']);

                $orderItem = OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['product_id'],
                    'product_name' => $item['name'],
                    'unit_price'   => $item['unit_price'],
                    'qty'          => $item['qty'],
                    'note'         => $item['note'] ?? null,
                    'line_total'   => $lineTotal,
                ]);

                if (!empty($item['modifiers'])) {
                    foreach ($item['modifiers'] as $mod) {
                        OrderItemMod::create([
                            'order_item_id'   => $orderItem->id,
                            'mod_option_id'   => $mod['id'],
                            'mod_group_name'  => $mod['group_name'],
                            'mod_option_name' => $mod['option_name'],
                            'price_modifier'  => $mod['price_modifier'],
                        ]);
                    }
                }
            }

            return $order;
        });
    }

    /**
     * Create a POS/Kasir order (manual by admin/barista).
     * Cash → payment_status = PAID immediately.
     * QRIS → payment_status = UNPAID, waiting for manual confirm.
     */
    public function createPosOrder(
        ?int $tableId,
        string $customerName,
        string $fulfillmentType,
        array $cart,
        array $totals,
        string $paymentMethod,    // 'CASH' | 'QRIS'
        ?int $cashReceived = null
    ): Order {
        return DB::transaction(function () use ($tableId, $customerName, $fulfillmentType,
                                                $cart, $totals, $paymentMethod, $cashReceived) {
            $orderCode = $this->generateOrderCode('K'); // prefix K- untuk kasir

            $order = Order::create([
                'order_code'       => $orderCode,
                'order_source'     => 'POS',
                'table_id'         => $tableId,
                'customer_name'    => $customerName,
                'fulfillment_type' => $fulfillmentType,
                'order_status'     => 'DITERIMA',
                'payment_status'   => $paymentMethod === 'CASH' ? 'PAID' : 'UNPAID',
                'subtotal'         => $totals['subtotal'],
                'tax_amount'       => $totals['tax']      ?? 0,
                'service_amount'   => $totals['service']  ?? 0,
                'discount_amount'  => $totals['discount'] ?? 0,
                'grand_total'      => $totals['grand_total'],
            ]);

            // Buat order items + modifiers
            foreach ($cart as $item) {
                $lineTotal = ((int)$item['unit_price']) * ((int)$item['qty']);

                $orderItem = OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['product_id'],
                    'product_name' => $item['name'],
                    'unit_price'   => $item['unit_price'],
                    'qty'          => $item['qty'],
                    'note'         => $item['note'] ?? null,
                    'line_total'   => $lineTotal,
                ]);

                foreach (($item['modifiers'] ?? []) as $mod) {
                    OrderItemMod::create([
                        'order_item_id'   => $orderItem->id,
                        'mod_option_id'   => $mod['id'],
                        'mod_group_name'  => $mod['group_name'],
                        'mod_option_name' => $mod['option_name'],
                        'price_modifier'  => $mod['price_modifier'],
                    ]);
                }
            }

            // Hitung kembalian tunai
            $cashChange = ($paymentMethod === 'CASH' && $cashReceived !== null)
                ? max(0, $cashReceived - $totals['grand_total'])
                : null;

            Payment::create([
                'order_id'      => $order->id,
                'gateway'       => $paymentMethod,  // 'CASH' or 'QRIS'
                'status'        => $paymentMethod === 'CASH' ? 'settlement' : 'pending',
                'cash_received' => $paymentMethod === 'CASH' ? $cashReceived : null,
                'cash_change'   => $cashChange,
            ]);

            return $order;
        });
    }

    /**
     * Generate a unique order code with a given prefix.
     * Format: {PREFIX}-YYYYMMDD-XXXXXX (e.g. A-20260308-ABC123, K-20260308-XYZ789)
     */
    private function generateOrderCode(string $prefix = 'A'): string
    {
        $date = now()->format('Ymd');
        $rand = strtoupper(Str::random(6));
        return "{$prefix}-{$date}-{$rand}";
    }
}
