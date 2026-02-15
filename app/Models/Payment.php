<?php

namespace App\Models;

use App\Helpers\MidtransHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $fillable = [
        'order_id','gateway','status','gateway_session_id','gateway_url',
        'gateway_trx_id','raw_response'
    ];

    protected $casts = [
        'raw_response' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(PaymentEvent::class);
    }

    /**
     * Get a human-readable payment method label from Midtrans data.
     * Tries raw_response first, falls back to PaymentEvent payload.
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        // Try raw_response on Payment itself
        if (!empty($this->raw_response)) {
            return MidtransHelper::getPaymentLabel($this->raw_response);
        }

        // Fallback: find the settlement/capture event
        $event = $this->events()
            ->whereIn('event_type', ['settlement', 'capture'])
            ->latest()
            ->first();

        if ($event && !empty($event->payload)) {
            return MidtransHelper::getPaymentLabel($event->payload);
        }

        return 'Midtrans';
    }
}

