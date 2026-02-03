<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CafeTable extends Model
{
    protected $fillable = ['table_no','name','is_active'];

    public function tokens(): HasMany
    {
        return $this->hasMany(TableToken::class, 'table_id');
    }

    public function activeToken()
    {
        return $this->tokens()->where('is_active', true)->latest()->first();
    }
}
