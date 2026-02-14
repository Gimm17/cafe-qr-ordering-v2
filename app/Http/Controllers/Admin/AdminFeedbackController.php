<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderFeedback;

class AdminFeedbackController extends Controller
{
    public function index()
    {
        $feedback = OrderFeedback::with('order', 'product')
            ->latest()
            ->paginate(30);

        return view('admin.feedback', ['feedback' => $feedback]);
    }

    public function toggle(OrderFeedback $feedback)
    {
        $feedback->update([
            'status' => $feedback->status === 'VISIBLE' ? 'HIDDEN' : 'VISIBLE',
        ]);

        return back()->with('success','Status feedback diubah.');
    }
}
