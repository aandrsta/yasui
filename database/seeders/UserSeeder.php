<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Yassui',
            'email' => 'admin@yassui.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Gedung Admin Yassui Lantai 3, Jakarta',
        ]);

        // Buyers
        User::create([
            'name' => 'User Yassui',
            'email' => 'user@example.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'phone' => '081234567890',
            'address' => 'Gedung User Yassui Lantai 3, Jakarta',
        ]);
    }
}
