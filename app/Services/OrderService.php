<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemMod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function createFromCart(
        int $tableId,
        string $customerName,
        string $fulfillmentType,
        array $cart,
        array $totals
    ): Order {
        return DB::transaction(function () use ($tableId, $customerName, $fulfillmentType, $cart, $totals) {
            $orderCode = $this->generateOrderCode();

            $order = Order::create([
                'order_code' => $orderCode,
                'table_id' => $tableId,
                'customer_name' => $customerName,
                'fulfillment_type' => $fulfillmentType,
                'order_status' => 'DITERIMA',
                'payment_status' => 'UNPAID',
                'subtotal' => $totals['subtotal'],
                'tax_amount' => $totals['tax'],
                'service_amount' => $totals['service'],
                'discount_amount' => $totals['discount'],
                'grand_total' => $totals['grand_total'],
            ]);

            foreach ($cart as $item) {
                $lineTotal = ((int)$item['unit_price']) * ((int)$item['qty']);
                
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'unit_price' => $item['unit_price'],
                    'qty' => $item['qty'],
                    'note' => $item['note'] ?? null,
                    'line_total' => $lineTotal,
                ]);

                // Save modifiers for this order item
                if (!empty($item['modifiers'])) {
                    foreach ($item['modifiers'] as $mod) {
                        OrderItemMod::create([
                            'order_item_id' => $orderItem->id,
                            'mod_option_id' => $mod['id'],
                            'mod_group_name' => $mod['group_name'],
                            'mod_option_name' => $mod['option_name'],
                            'price_modifier' => $mod['price_modifier'],
                        ]);
                    }
                }
            }

            return $order;
        });
    }

    private function generateOrderCode(): string
    {
        $date = now()->format('Ymd');
        $rand = strtoupper(Str::random(6));
        return "A-{$date}-{$rand}";
    }
}
