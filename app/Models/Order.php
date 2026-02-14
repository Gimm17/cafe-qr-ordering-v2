<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'order_code','table_id','customer_name','fulfillment_type',
        'order_status','payment_status','subtotal','tax_amount','service_amount',
        'discount_amount','grand_total'
    ];

    protected $casts = [
        'subtotal' => 'int',
        'tax_amount' => 'int',
        'service_amount' => 'int',
        'discount_amount' => 'int',
        'grand_total' => 'int',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(CafeTable::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function feedback()
    {
        return $this->hasMany(OrderFeedback::class);
    }
}
