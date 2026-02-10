<?php

namespace App\Models;

// NOTE: Jika project Laravel kamu sudah punya User.php, jangan overwrite.
// Cukup pastikan ada kolom boolean `is_admin` dan property $casts.

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','password'];
    // is_admin dikeluarkan dari $fillable untuk mencegah privilege escalation (F-07)

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];
}
