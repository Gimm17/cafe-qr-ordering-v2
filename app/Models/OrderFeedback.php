<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderFeedback extends Model
{
    protected $table = 'order_feedback';

    protected $fillable = ['order_id','rating','comment','status','is_flagged','admin_note'];

    protected $casts = [
        'rating' => 'int',
        'is_flagged' => 'boolean',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
