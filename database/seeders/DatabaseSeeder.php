<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Demo Super Admin Pusat
        User::firstOrNew(['email' => 'pusat@sikopmas.test'])
            ->forceFill([
                'name' => 'Super Admin Pusat',
                'role' => 'pusat',
                'status' => 'active',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ])->save();

        // 2. Akun Demo Admin Koperasi (Tenant)
        User::firstOrNew(['email' => 'koperasi@sikopmas.test'])
            ->forceFill([
                'name' => 'Admin Koperasi A',
                'role' => 'koperasi',
                'status' => 'active',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ])->save();
    }
}