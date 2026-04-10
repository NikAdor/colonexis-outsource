<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@colonexis.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@colonexis.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STAFF,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'client@colonexis.com'],
            [
                'name' => 'Client User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_CLIENT,
                'email_verified_at' => now(),
            ]
        );
    }
}
