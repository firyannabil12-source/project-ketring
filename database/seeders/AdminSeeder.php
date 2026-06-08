<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@rishacatering.com'],
            [
                'name' => 'Admin Risha Catering',
                'email' => 'admin@rishacatering.com',
                'password' => Hash::make('rishacatering123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
