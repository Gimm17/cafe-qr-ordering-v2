<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemMod extends Model
{
    protected $fillable = [
        'order_item_id',
        'mod_option_id',
        'mod_group_name',
        'mod_option_name',
        'price_modifier',
    ];

    protected $casts = [
        'price_modifier' => 'integer',
    ];

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function modOption(): BelongsTo
    {
        return $this->belongsTo(ModOption::class);
    }
}
