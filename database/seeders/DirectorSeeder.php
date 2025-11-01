<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DirectorSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Director',
            'email' => 'direktur@email.com',
            'password' => Hash::make('password'),
            'role' => 'director',
            'is_active' => true,
        ]);
    }
} 