<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ModOption;
use Illuminate\Support\Facades\Session;

class CartService
{
    private string $key = 'cafe_cart';

    public function get(): array
    {
        return Session::get($this->key, []);
    }

    /**
     * Add product to cart with optional modifiers
     * Cart key is now a unique ID for each cart line (product + modifiers combination)
     */
    public function add(Product $product, int $qty = 1, ?string $note = null, array $modifierIds = []): string
    {
        $cart = $this->get();
        
        // Get modifier details
        $modifiers = [];
        $modifiersTotal = 0;
        
        if (!empty($modifierIds)) {
            $modOptions = ModOption::with('modGroup')->whereIn('id', $modifierIds)->get();
            foreach ($modOptions as $option) {
                $modifiers[] = [
                    'id' => $option->id,
                    'group_name' => $option->modGroup->name,
                    'option_name' => $option->name,
                    'price_modifier' => $option->price_modifier,
                ];
                $modifiersTotal += $option->price_modifier;
            }
        }

        // Create unique key for this product + modifiers combination
        $cartKey = $this->generateCartKey($product->id, $modifierIds);

        if (!isset($cart[$cartKey])) {
            $cart[$cartKey] = [
                'cart_key' => $cartKey,
                'product_id' => $product->id,
                'name' => $product->name,
                'image_url' => $product->image_url,
                'base_price' => $product->base_price,
                'modifiers' => $modifiers,
                'modifiers_total' => $modifiersTotal,
                'unit_price' => $product->base_price + $modifiersTotal,
                'qty' => 0,
                'note' => null,
            ];
        }

        $cart[$cartKey]['qty'] += max(1, $qty);
        if ($note !== null) {
            $cart[$cartKey]['note'] = trim($note) ?: null;
        }

        Session::put($this->key, $cart);
        
        return $cartKey;
    }

    private function generateCartKey(int $productId, array $modifierIds): string
    {
        sort($modifierIds);
        $modHash = md5(implode(',', $modifierIds));
        return "{$productId}_{$modHash}";
    }

    public function update(string $cartKey, int $qty): void
    {
        $cart = $this->get();

        if (!isset($cart[$cartKey])) return;

        if ($qty <= 0) {
            unset($cart[$cartKey]);
        } else {
            $cart[$cartKey]['qty'] = $qty;
        }

        Session::put($this->key, $cart);
    }

    public function updateNote(string $cartKey, ?string $note): void
    {
        $cart = $this->get();
        
        if (!isset($cart[$cartKey])) return;
        
        $cart[$cartKey]['note'] = trim($note) ?: null;
        Session::put($this->key, $cart);
    }

    public function remove(string $cartKey): void
    {
        $cart = $this->get();
        unset($cart[$cartKey]);
        Session::put($this->key, $cart);
    }

    public function clear(): void
    {
        Session::forget($this->key);
    }

    public function count(): int
    {
        $cart = $this->get();
        $count = 0;
        foreach ($cart as $item) {
            $count += (int)$item['qty'];
        }
        return $count;
    }

    public function totals(): array
    {
        $cart = $this->get();
        $subtotal = 0;
        $count = 0;

        foreach ($cart as $item) {
            $unitPrice = (int)$item['unit_price'];
            $qty = (int)$item['qty'];
            $subtotal += $unitPrice * $qty;
            $count += $qty;
        }

        // Tax and service can be configured later
        $taxRate = 0; // 10% = 0.1
        $serviceRate = 0; // 5% = 0.05
        
        $tax = (int)round($subtotal * $taxRate);
        $service = (int)round($subtotal * $serviceRate);
        $grandTotal = $subtotal + $tax + $service;

        return [
            'subtotal' => $subtotal,
            'count' => $count,
            'tax' => $tax,
            'service' => $service,
            'discount' => 0,
            'grand_total' => $grandTotal,
        ];
    }

    public function getFormattedItems(): array
    {
        $cart = $this->get();
        $items = [];
        
        foreach ($cart as $item) {
            $modifiersSummary = collect($item['modifiers'] ?? [])
                ->pluck('option_name')
                ->implode(', ');
            
            $items[] = array_merge($item, [
                'modifiers_summary' => $modifiersSummary,
                'line_total' => (int)$item['unit_price'] * (int)$item['qty'],
                'formatted_unit_price' => 'Rp ' . number_format($item['unit_price'], 0, ',', '.'),
                'formatted_line_total' => 'Rp ' . number_format((int)$item['unit_price'] * (int)$item['qty'], 0, ',', '.'),
            ]);
        }
        
        return $items;
    }
}
