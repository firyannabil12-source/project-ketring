<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ketring.com'],
            [
                'name'              => 'Admin Mama Iksan',
                'email'             => 'admin@ketring.com',
                'password'          => Hash::make('mamaiksan123'),
                'role'              => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
