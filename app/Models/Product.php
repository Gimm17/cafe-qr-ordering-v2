<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'base_price', 'image_url',
        'is_active', 'is_sold_out', 'is_best_seller'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_sold_out' => 'boolean',
        'is_best_seller' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Product reviews from order feedback.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(OrderFeedback::class)
            ->where('status', 'VISIBLE')
            ->whereNotNull('rating');
    }

    public function getAverageRatingAttribute(): ?float
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? round($avg, 1) : null;
    }

    public function getReviewCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function modGroups(): BelongsToMany
    {
        return $this->belongsToMany(ModGroup::class, 'product_mod_groups')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('product_mod_groups.sort_order');
    }

    public function activeModGroups(): BelongsToMany
    {
        return $this->belongsToMany(ModGroup::class, 'product_mod_groups')
            ->where('mod_groups.is_active', true)
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('product_mod_groups.sort_order');
    }

    public function getPriceRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->base_price, 0, ',', '.');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)->where('is_sold_out', false);
    }

    public function scopeBestSeller($query)
    {
        return $query->where('is_best_seller', true);
    }
}
