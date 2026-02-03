<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'unit_price', 'qty', 'note', 'line_total'
    ];

    protected $casts = [
        'unit_price' => 'int',
        'qty' => 'int',
        'line_total' => 'int',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function mods(): HasMany
    {
        return $this->hasMany(OrderItemMod::class);
    }

    public function getModsSummaryAttribute(): string
    {
        return $this->mods->map(function ($mod) {
            return $mod->mod_option_name;
        })->implode(', ');
    }

    public function getModsTotalAttribute(): int
    {
        return $this->mods->sum('price_modifier');
    }
}
