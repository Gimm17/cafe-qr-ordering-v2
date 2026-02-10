<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@local.test');
        $password = env('ADMIN_PASSWORD', 'admin123');

        // WARNING: Di production, pastikan ADMIN_EMAIL & ADMIN_PASSWORD di-set di .env
        // dengan password yang kuat. Jangan pakai default.
        if (app()->environment('production') && !env('ADMIN_PASSWORD')) {
            $this->command?->warn('⚠ ADMIN_PASSWORD belum di-set di .env! Seeder admin di-skip.');
            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'password' => Hash::make($password),
                'is_admin' => true,
            ]
        );
    }
}
