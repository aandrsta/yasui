<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $figures = Category::where('slug', 'figures')->first();
        $modelKits = Category::where('slug', 'model-kits')->first();
        $characterGoods = Category::where('slug', 'character-goods')->first();
        $plushies = Category::where('slug', 'plushies')->first();

        // Figures
        Product::create([
            'category_id' => $figures->id,
            'name' => 'Nendoroid Hatsune Miku: V4X',
            'slug' => 'nendoroid-hatsune-miku-v4x',
            'description' => 'Nendoroid original Hatsune Miku versi V4X dari Good Smile Company. Dilengkapi dengan 3 faceplates ekspresi wajah berbeda dan aksesoris sayap ikonik.',
            'price' => 850000,
            'stock' => 15,
            'image' => 'products/nendoroid-miku-v4x.png',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $figures->id,
            'name' => 'Pop Up Parade Nakano Miku',
            'slug' => 'pop-up-parade-nakano-miku',
            'description' => 'Figure Nakano Miku dari serial The Quintessential Quintuplets dalam lini Pop Up Parade. Detail seragam sekolah khas dan pose pemalu yang sangat menawan.',
            'price' => 650000,
            'stock' => 8,
            'image' => 'products/pupp-nakano-miku.png',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $figures->id,
            'name' => 'Scale Figure 1/7 Tokisaki Kurumi: Bunny Ver.',
            'slug' => 'scale-figure-tokisaki-kurumi-bunny',
            'description' => 'Scale figure premium 1/7 Tokisaki Kurumi dari Date A Live dengan pakaian bunny suit hitam glossy. Menggunakan material PVC berkualitas tinggi dengan detail lekukan yang presisi.',
            'price' => 2450000,
            'stock' => 3,
            'image' => 'products/kurumi-bunny.png',
            'is_featured' => false,
        ]);

        // Model Kits
        Product::create([
            'category_id' => $modelKits->id,
            'name' => 'RG 1/144 RX-93-v2 Hi-v Gundam',
            'slug' => 'rg-rx-93-v2-hi-gundam',
            'description' => 'Real Grade 1/144 Hi-v Gundam (Hi-Nu Gundam) buatan Bandai Spirits. Merupakan salah satu gunpla RG terbaik dengan artikulasi luar biasa dan detail mekanis yang sangat kaya.',
            'price' => 720000,
            'stock' => 10,
            'image' => 'products/rg-hi-nu-gundam.png',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $modelKits->id,
            'name' => 'HG 1/144 Gundam Calibarn',
            'slug' => 'hg-gundam-calibarn',
            'description' => 'High Grade 1/144 Gundam Calibarn dari anime Mobile Suit Gundam: Witch from Mercury. Dilengkapi dengan Broom-like Variable Rod Rifle dan efek transparan pelangi.',
            'price' => 360000,
            'stock' => 18,
            'image' => 'products/hg-gundam-calibarn.png',
            'is_featured' => false,
        ]);

        Product::create([
            'category_id' => $modelKits->id,
            'name' => '30MS Tokai Teio (Special Collaboration Ver.)',
            'slug' => '30ms-tokai-teio-collaboration',
            'description' => 'Plamo kolaborasi 30 Minutes Sisters dari Bandai dengan Uma Musume: Pretty Derby melahirkan Tokai Teio! Sangat modular, mudah dirakit, dan posenya dinamis.',
            'price' => 550000,
            'stock' => 5,
            'image' => 'products/30ms-tokai-teio.png',
            'is_featured' => false,
        ]);

        // Character Goods
        Product::create([
            'category_id' => $characterGoods->id,
            'name' => 'Acrylic Stand Gojo Satoru - Shibuya Incident Ver.',
            'slug' => 'acrylic-stand-gojo-satoru-shibuya',
            'description' => 'Acrylic stand official Jujutsu Kaisen bermotif Gojo Satoru saat arc Shibuya Incident. Cetakan high definition dengan bahan akrilik tebal 3mm.',
            'price' => 185000,
            'stock' => 40,
            'image' => 'products/acrylic-stand-gojo.png',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $characterGoods->id,
            'name' => 'Jujutsu Kaisen Character Badge Box Set (6 pcs)',
            'slug' => 'jujutsu-kaisen-badge-box-set',
            'description' => 'Satu box set kaleng pin badge original Jujutsu Kaisen berisi 6 karakter utama: Itadori, Megumi, Nobara, Gojo, Nanami, dan Sukuna.',
            'price' => 350000,
            'stock' => 12,
            'image' => 'products/jkk-badge-box.png',
            'is_featured' => false,
        ]);

        Product::create([
            'category_id' => $characterGoods->id,
            'name' => 'Chainsaw Man Pochita Metal Keychain',
            'slug' => 'chainsaw-man-pochita-metal-keychain',
            'description' => 'Gantungan kunci logam premium berbentuk Pochita dari serial Chainsaw Man. Awet, anti karat, dengan detail 3D timbul yang keren.',
            'price' => 95000,
            'stock' => 75,
            'image' => 'products/pochita-metal-keychain.png',
            'is_featured' => false,
        ]);

        // Plushies
        Product::create([
            'category_id' => $plushies->id,
            'name' => 'Nesoberi Plush Lay Down Megumi Fushiguro (M)',
            'slug' => 'nesoberi-plush-megumi-fushiguro',
            'description' => 'Boneka Nesoberi (posisi tiarap) original SEGA berukuran Medium (30cm) dari karakter Megumi Fushiguro Jujutsu Kaisen. Sangat lembut dan empuk dipeluk.',
            'price' => 420000,
            'stock' => 6,
            'image' => 'products/nesoberi-megumi.png',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $plushies->id,
            'name' => 'Spy x Family Anya Forger Chimera Plush Doll',
            'slug' => 'anya-forger-chimera-plush',
            'description' => 'Boneka plush replica dari mainan kesayangan Anya Forger, sang Chimera. Warna vibrant, bahan lembut berbulu halus, wajib dimiliki fans Spy x Family.',
            'price' => 280000,
            'stock' => 25,
            'image' => 'products/chimera-plush.png',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $plushies->id,
            'name' => 'Miku Fuwanui Plush Mascot',
            'slug' => 'miku-fuwanui-plush-mascot',
            'description' => 'Fuwanui plush mascot Hatsune Miku berukuran 10cm dengan gantungan bola besi. Cocok dipasang di tas sekolah atau ransel.',
            'price' => 150000,
            'stock' => 30,
            'image' => 'products/miku-fuwanui.png',
            'is_featured' => false,
        ]);
    }
}

