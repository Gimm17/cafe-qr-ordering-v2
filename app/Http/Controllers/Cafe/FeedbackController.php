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
            return back()->with('error', 'Feedback hanya bisa setelah pesanan SELESAI.');
        }

        if ($order->feedback) {
            return back()->with('error', 'Feedback sudah pernah dikirim untuk order ini.');
        }

        $data = $req->validate([
            'rating' => ['nullable','integer','min:1','max:5'],
            'comment' => ['nullable','string','max:700'],
        ]);

        $rating = $data['rating'] ?? null;
        $comment = isset($data['comment']) ? trim($data['comment']) : null;

        if ($rating === null && (!$comment || $comment === '')) {
            return back()->with('error', 'Isi rating atau komentar (minimal salah satu).');
        }

        OrderFeedback::create([
            'order_id' => $order->id,
            'rating' => $rating,
            'comment' => $comment ?: null,
            'status' => 'VISIBLE',
            'is_flagged' => false,
        ]);

        return back()->with('success', 'Makasih! Masukan kamu sudah terkirim.');
    }
}
