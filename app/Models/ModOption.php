<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModOption extends Model
{
    protected $fillable = [
        'mod_group_id',
        'name',
        'price_modifier',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price_modifier' => 'integer',
        'is_active' => 'boolean',
    ];

    public function modGroup(): BelongsTo
    {
        return $this->belongsTo(ModGroup::class);
    }

    public function getFormattedPriceModifierAttribute(): string
    {
        if ($this->price_modifier == 0) {
            return '';
        }
        $prefix = $this->price_modifier > 0 ? '+' : '';
        return $prefix . 'Rp ' . number_format(abs($this->price_modifier), 0, ',', '.');
    }
}
