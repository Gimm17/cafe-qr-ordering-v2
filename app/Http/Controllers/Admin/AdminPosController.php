<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CafeTable;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Services\OrderService;
use Illuminate\Http\Request;

class AdminPosController extends Controller
{
    public function __construct(private OrderService $orders) {}

    /**
     * Halaman utama POS / Kasir.
     */
    public function index()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $products   = Product::with('activeModGroups.options')
            ->active()->where('is_sold_out', false)->orderBy('name')->get();
        $tables = CafeTable::orderBy('table_no')->get();

        // Pre-build JSON for JavaScript (avoids N+1 lazy-load in Blade)
        $productsJson = $products->map(fn($p) => [
            'id'          => $p->id,
            'name'        => $p->name,
            'base_price'  => (int) $p->base_price,
            'image_url'   => $p->image_url ? asset($p->image_url) : null,
            'category_id' => $p->category_id,
            'modGroups'   => $p->activeModGroups->map(fn($g) => [
                'id'          => $g->id,
                'name'        => $g->name,
                'is_required' => (bool) $g->is_required,
                'max_select'  => (int) $g->max_select,
                'options'     => $g->options->map(fn($o) => [
                    'id'             => $o->id,
                    'name'           => $o->name,
                    'price_modifier' => (int) $o->price_modifier,
                ])->values()->all(),
            ])->values()->all(),
        ])->values()->all();

        return view('admin.pos', [
            'categories'           => $categories,
            'products'             => $products,
            'productsJson'         => $productsJson,
            'tables'               => $tables,
            'posQrisMode'          => Setting::getValue('pos_qris_mode', 'external'),
            'posQrisImage'         => Setting::getValue('pos_qris_image'),
            'posCashChangeEnabled' => Setting::getValue('pos_cash_change_enabled', '1'),
            'posAudioEnabled'      => Setting::getValue('pos_audio_enabled', '1'),
        ]);
    }

    /**
     * JSON endpoint — daftar produk aktif dengan modifier groups.
     */
    public function products()
    {
        $products = Product::with('activeModGroups.options')
            ->active()->where('is_sold_out', false)->orderBy('name')->get()
            ->map(fn($p) => [
                'id'          => $p->id,
                'name'        => $p->name,
                'base_price'  => (int) $p->base_price,
                'image_url'   => $p->image_url ? asset($p->image_url) : null,
                'category_id' => $p->category_id,
                'modGroups'   => $p->activeModGroups->map(fn($g) => [
                    'id'          => $g->id,
                    'name'        => $g->name,
                    'is_required' => (bool) $g->is_required,
                    'max_select'  => (int) $g->max_select,
                    'options'     => $g->options->map(fn($o) => [
                        'id'             => $o->id,
                        'name'           => $o->name,
                        'price_modifier' => (int) $o->price_modifier,
                    ]),
                ]),
            ]);

        return response()->json($products);
    }

    /**
     * Buat order POS baru (CASH atau QRIS).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name'                  => ['required', 'string', 'max:60'],
            'table_id'                       => ['nullable', 'integer', 'exists:cafe_tables,id'],
            'fulfillment_type'               => ['required', 'in:DINE_IN,PICKUP'],
            'payment_method'                 => ['required', 'in:CASH,QRIS'],
            'cash_received'                  => ['nullable', 'integer', 'min:0'],
            'items'                          => ['required', 'array', 'min:1'],
            'items.*.product_id'             => ['required', 'integer'],
            'items.*.name'                   => ['required', 'string'],
            'items.*.unit_price'             => ['required', 'integer'],
            'items.*.qty'                    => ['required', 'integer', 'min:1'],
            'items.*.note'                   => ['nullable', 'string', 'max:200'],
            'items.*.modifiers'              => ['nullable', 'array'],
            'items.*.modifiers.*.id'         => ['required', 'integer'],
            'items.*.modifiers.*.group_name' => ['required', 'string'],
            'items.*.modifiers.*.option_name'=> ['required', 'string'],
            'items.*.modifiers.*.price_modifier' => ['required', 'integer'],
        ]);

        $subtotal = collect($data['items'])->sum(fn($i) => (int)$i['unit_price'] * (int)$i['qty']);
        $totals   = [
            'subtotal'    => $subtotal,
            'tax'         => 0,
            'service'     => 0,
            'discount'    => 0,
            'grand_total' => $subtotal,
        ];

        $order = $this->orders->createPosOrder(
            $data['table_id'] ?? null,
            $data['customer_name'],
            $data['fulfillment_type'],
            $data['items'],
            $totals,
            $data['payment_method'],
            $data['cash_received'] ?? null
        );

        return response()->json([
            'success' => true,
            'order'   => [
                'id'             => $order->id,
                'order_code'     => $order->order_code,
                'grand_total'    => $order->grand_total,
                'payment_status' => $order->payment_status,
                'payment_method' => $data['payment_method'],
                'receipt_url'    => route('admin.orders.receipt', $order),
            ],
        ]);
    }

    /**
     * Konfirmasi pembayaran QRIS secara manual oleh kasir.
     */
    public function confirmQris(Order $order)
    {
        if ($order->payment_status === 'PAID') {
            return response()->json(['success' => false, 'message' => 'Pesanan sudah dibayar.']);
        }

        $order->update(['payment_status' => 'PAID', 'order_status' => 'DIPROSES']);
        $order->payment?->update(['status' => 'settlement']);

        return response()->json([
            'success'     => true,
            'grand_total' => $order->grand_total,
        ]);
    }

    /**
     * Polling status pembayaran — untuk auto-update modal QRIS.
     */
    public function checkPayment(Order $order)
    {
        return response()->json([
            'payment_status' => $order->payment_status,
            'grand_total'    => $order->grand_total,
        ]);
    }
}
