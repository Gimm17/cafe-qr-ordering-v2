<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private OrderService $orders
    ) {}

    public function index()
    {
        $items = $this->cart->get();
        if (count($items) === 0) {
            return redirect()->route('cafe.menu')->with('error', 'Keranjang kosong.');
        }

        return view('cafe.checkout', [
            'tableNo' => session('cafe_table_no'),
            'items' => $items,
            'totals' => $this->cart->totals(),
        ]);
    }

    public function store(Request $req)
    {
        $items = $this->cart->get();
        if (count($items) === 0) {
            return redirect()->route('cafe.menu')->with('error', 'Keranjang kosong.');
        }

        $data = $req->validate([
            'customer_name' => ['required', 'string', 'max:60'],
            'fulfillment_type' => ['required', 'in:DINE_IN,PICKUP'],
        ]);

        $tableId = (int)session('cafe_table_id');
        $totals = $this->cart->totals();

        $order = $this->orders->createFromCart(
            $tableId,
            $data['customer_name'],
            $data['fulfillment_type'],
            $items,
            $totals
        );

        $this->cart->clear();

        return redirect()->route('cafe.pay', ['order' => $order->order_code]);
    }
}
