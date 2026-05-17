<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
            'description' => 'Gadget, laptop, aksesoris elektronik berkualitas.',
            'image' => 'categories/elektronik.png',
        ]);

        Category::create([
            'name' => 'Fashion',
            'slug' => 'fashion',
            'description' => 'Pakaian pria dan wanita kekinian dan premium.',
            'image' => 'categories/fashion.png',
        ]);

        Category::create([
            'name' => 'Makanan & Minuman',
            'slug' => 'makanan-minuman',
            'description' => 'Camilan, minuman segar, dan makanan berkualitas.',
            'image' => 'categories/makanan-minuman.png',
        ]);
    }
}
