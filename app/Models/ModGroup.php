<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ModGroup extends Model
{
    protected $fillable = [
        'name',
        'type',
        'is_required',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(ModOption::class)->orderBy('sort_order');
    }

    public function activeOptions(): HasMany
    {
        return $this->hasMany(ModOption::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_mod_groups')
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    public function isSingleSelect(): bool
    {
        return $this->type === 'SINGLE';
    }

    public function isMultipleSelect(): bool
    {
        return $this->type === 'MULTIPLE';
    }
}
