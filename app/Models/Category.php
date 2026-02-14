<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Category extends Model
{
    protected $fillable = ['name', 'sort_order', 'is_active', 'close_order_time'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Check if this category is currently closed for ordering.
     *
     * Logic: if close_order_time is null, always open.
     * If close_order_time is between 00:00-05:59, it means the cafe
     * operates past midnight (e.g., coffee until 02:00 AM).
     * In that case, the category is closed when now >= close_order_time AND now < 06:00.
     * For normal hours (06:00-23:59), closed when now >= close_order_time.
     */
    public function isClosedOrder(): bool
    {
        if ($this->close_order_time === null) {
            return false;
        }

        $now = Carbon::now();
        $nowTime = $now->format('H:i:s');
        $closeTime = $this->close_order_time;

        // Normalize close_order_time to H:i:s format
        if (strlen($closeTime) === 5) {
            $closeTime .= ':00';
        }

        $closeHour = (int) substr($closeTime, 0, 2);

        if ($closeHour < 6) {
            // Close time is after midnight (e.g., 02:00 AM)
            // Closed when: current time >= close_time AND current time < 06:00
            // OR it's daytime and past the implicit "open" start
            // The category is OPEN during: 06:00 -> 23:59 -> 00:00 -> close_time
            // The category is CLOSED during: close_time -> 05:59
            if ($nowTime >= $closeTime && $nowTime < '06:00:00') {
                return true;
            }
            return false;
        } else {
            // Close time is during normal hours (e.g., 22:30)
            // Closed when: now >= close_time (until midnight)
            // But open again after midnight (for categories that close before midnight,
            // the early morning is before cafe opens)
            if ($nowTime >= $closeTime) {
                return true;
            }
            // Also closed in early morning hours (00:00-05:59) for normal close times
            // because the cafe is not yet open
            if ($nowTime < '06:00:00') {
                return true;
            }
            return false;
        }
    }
}
