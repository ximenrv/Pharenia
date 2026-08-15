<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrador Pharenia',
                'email' => 'admin@gmail.com',
                'birthdate' => '1990-01-01',
                'role' => 'admin',
                'password' => Hash::make('phareniadmin'),
                'email_verified_at' => now(),
            ]
        );
    }
}