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
     * Simple logic:
     * - If cafe is globally closed → always closed (checked externally)
     * - If close_order_time is null → always open
     * - If close_order_time is set → closed when current time is past close_order_time
     *   and before cafe opening time (10:00)
     *
     * The "day" runs from 10:00 to 09:59 next day.
     * Example: close_order_time = 22:30 → closed from 22:30 until 10:00 next morning
     * Example: close_order_time = 02:00 → closed from 02:00 until 10:00 next morning
     */
    public function isClosedOrder(): bool
    {
        if ($this->close_order_time === null) {
            return false;
        }

        $now = Carbon::now();
        $nowTime = $now->format('H:i:s');
        $closeTime = $this->close_order_time;

        // Normalize to H:i:s
        if (strlen($closeTime) === 5) {
            $closeTime .= ':00';
        }

        $openTime = '10:00:00'; // Cafe opens at 10:00

        $closeHour = (int) substr($closeTime, 0, 2);

        if ($closeHour >= 10) {
            // Close time is during the day (e.g., 22:30)
            // Closed: close_time <= now OR now < open_time
            // i.e., closed from 22:30 through midnight to 09:59
            if ($nowTime >= $closeTime || $nowTime < $openTime) {
                return true;
            }
        } else {
            // Close time is after midnight (e.g., 02:00)
            // Closed: close_time <= now < open_time
            // i.e., closed from 02:00 to 09:59
            // But NOT closed before close_time (e.g., 01:30 is still open)
            if ($nowTime >= $closeTime && $nowTime < $openTime) {
                return true;
            }
            // Before midnight is always open (cafe hasn't reached close time yet)
        }

        return false;
    }
}
