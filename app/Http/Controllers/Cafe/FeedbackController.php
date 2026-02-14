<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderFeedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $req, Order $order)
    {
        // only after SELESAI
        if ($order->order_status !== 'SELESAI') {
            return back()->with('error', 'Review hanya bisa setelah pesanan SELESAI.');
        }

        // Check if already reviewed
        if ($order->feedback()->exists()) {
            return back()->with('error', 'Kamu sudah pernah memberi review untuk pesanan ini.');
        }

        $data = $req->validate([
            'items' => ['required', 'array'],
            'items.*.rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'items.*.comment' => ['nullable', 'string', 'max:500'],
        ]);

        $items = $data['items'] ?? [];
        $savedCount = 0;

        $order->load('items');

        foreach ($items as $orderItemId => $review) {
            $rating = $review['rating'] ?? null;
            $comment = isset($review['comment']) ? trim($review['comment']) : null;

            // Skip items without rating AND comment
            if ($rating === null && (!$comment || $comment === '')) {
                continue;
            }

            // Verify the item belongs to this order
            $orderItem = $order->items->firstWhere('id', $orderItemId);
            if (!$orderItem) {
                continue;
            }

            OrderFeedback::create([
                'order_id' => $order->id,
                'product_id' => $orderItem->product_id,
                'order_item_id' => $orderItem->id,
                'rating' => $rating,
                'comment' => $comment ?: null,
                'status' => 'VISIBLE',
                'is_flagged' => false,
            ]);

            $savedCount++;
        }

        if ($savedCount === 0) {
            return back()->with('error', 'Isi rating atau komentar minimal untuk satu produk.');
        }

        return back()->with('success', 'Makasih! Review kamu sudah terkirim. 🎉');
    }
}
