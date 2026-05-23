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
            'name' => 'Figures',
            'slug' => 'figures',
            'description' => 'Koleksi figure original: Scale Figure, Nendoroid, dan Pop Up Parade premium.',
            'image' => 'categories/figures.png',
        ]);

        Category::create([
            'name' => 'Model Kits',
            'slug' => 'model-kits',
            'description' => 'Gundam / Gunpla dan berbagai model kit presisi impor Jepang.',
            'image' => 'categories/model-kits.png',
        ]);

        Category::create([
            'name' => 'Character Goods',
            'slug' => 'character-goods',
            'description' => 'Merchandise karakter otentik: Acrylic Stand, Pin Badge, dan Gantungan Kunci.',
            'image' => 'categories/character-goods.png',
        ]);

        Category::create([
            'name' => 'Plushies',
            'slug' => 'plushies',
            'description' => 'Boneka plushie lembut, Nesoberi lay-down, dan maskot karakter anime terlucu.',
            'image' => 'categories/plushies.png',
        ]);
    }
}

