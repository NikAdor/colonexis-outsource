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
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@colonexis.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STAFF,
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@colonexis.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_USER,
            ]
        );
    }
}
