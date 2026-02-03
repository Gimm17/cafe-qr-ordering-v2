<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        return view('cafe.cart', [
            'tableNo' => session('cafe_table_no'),
            'items' => $this->cart->getFormattedItems(),
            'totals' => $this->cart->totals(),
        ]);
    }

    public function add(Request $req)
    {
        $data = $req->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:20'],
            'note' => ['nullable', 'string', 'max:200'],
            'modifiers' => ['nullable', 'array'],
            'modifiers.*' => ['integer', 'exists:mod_options,id'],
        ]);

        $product = Product::findOrFail($data['product_id']);
        if ($product->is_sold_out || !$product->is_active) {
            return back()->with('error', 'Menu sedang tidak tersedia.');
        }

        $modifierIds = $data['modifiers'] ?? [];
        
        $this->cart->add(
            $product,
            (int)($data['qty'] ?? 1),
            $data['note'] ?? null,
            $modifierIds
        );
        
        return redirect()->route('cafe.cart')->with('success', 'Ditambahkan ke keranjang.');
    }

    public function update(Request $req)
    {
        $data = $req->validate([
            'cart_key' => ['required', 'string'],
            'qty' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        $this->cart->update($data['cart_key'], (int)$data['qty']);
        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(Request $req)
    {
        $data = $req->validate([
            'cart_key' => ['required', 'string'],
        ]);

        $this->cart->remove($data['cart_key']);
        return back()->with('success', 'Item dihapus.');
    }
}
