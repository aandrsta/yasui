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
            'name' => 'Admin Yasui',
            'email' => 'admin@yasui.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Gedung Admin Yasui Lantai 3, Jakarta',
        ]);

        // Buyers
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '081111111111',
            'address' => 'Jl. Mawar No. 12, Jakarta',
        ]);

        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '082222222222',
            'address' => 'Jl. Melati No. 45, Bandung',
        ]);

        User::create([
            'name' => 'Andi Wijaya',
            'email' => 'andi@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '083333333333',
            'address' => 'Jl. Anggrek No. 78, Surabaya',
        ]);
    }
}
