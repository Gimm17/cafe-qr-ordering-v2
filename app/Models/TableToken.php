<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TableToken extends Model
{
    protected $fillable = ['table_id','token','is_active','rotated_at'];

    protected $casts = [
        'is_active' => 'boolean',
        'rotated_at' => 'datetime',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(CafeTable::class, 'table_id');
    }
}
