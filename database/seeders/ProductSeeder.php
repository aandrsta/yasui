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
        $elektronik = Category::where('slug', 'elektronik')->first();
        $fashion = Category::where('slug', 'fashion')->first();
        $makanan = Category::where('slug', 'makanan-minuman')->first();

        // Elektronik
        Product::create([
            'category_id' => $elektronik->id,
            'name' => 'TWS Earbud Pro',
            'slug' => 'tws-earbud-pro',
            'description' => 'TWS Earbud dengan kualitas suara premium bass mantap, active noise cancellation, baterai tahan hingga 24 jam.',
            'price' => 299000,
            'stock' => 50,
            'image' => 'products/tws-earbud-pro.png',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $elektronik->id,
            'name' => 'Smartwatch Sport X',
            'slug' => 'smartwatch-sport-x',
            'description' => 'Smartwatch dengan sensor detak jantung komprehensif, pemantau tidur, waterproof IP68, sangat cocok untuk berolahraga.',
            'price' => 599000,
            'stock' => 30,
            'image' => 'products/smartwatch-sport-x.png',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $elektronik->id,
            'name' => 'Keyboard Mechanical RGB',
            'slug' => 'keyboard-mechanical-rgb',
            'description' => 'Keyboard mechanical dengan switch blue/red yang tactile, backlit RGB dengan 18 mode efek pencahayaan dinamis.',
            'price' => 450000,
            'stock' => 25,
            'image' => 'products/keyboard-mechanical-rgb.png',
            'is_featured' => false,
        ]);

        Product::create([
            'category_id' => $elektronik->id,
            'name' => 'Powerbank Fast Charging 20000mAh',
            'slug' => 'powerbank-fast-charging-20000mah',
            'description' => 'Powerbank kapasitas besar 20000mAh dengan dual output fast charging 22.5W, aman untuk dibawa ke dalam pesawat.',
            'price' => 199000,
            'stock' => 100,
            'image' => 'products/powerbank-20000mah.png',
            'is_featured' => false,
        ]);

        // Fashion
        Product::create([
            'category_id' => $fashion->id,
            'name' => 'Kaos Polos Cotton Combed 30s',
            'slug' => 'kaos-polos-cotton-combed-30s',
            'description' => 'Kaos polos premium 100% cotton combed 30s. Bahan sangat adem, menyerap keringat, dan tidak mudah melar.',
            'price' => 79000,
            'stock' => 150,
            'image' => 'products/kaos-polos.png',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $fashion->id,
            'name' => 'Jaket Hoodie Premium',
            'slug' => 'jaket-hoodie-premium',
            'description' => 'Jaket hoodie fleece tebal dan lembut dengan detail jaitan rapi. Nyaman dipakai saat dingin maupun untuk gaya kasual harian.',
            'price' => 249000,
            'stock' => 40,
            'image' => 'products/hoodie-premium.png',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $fashion->id,
            'name' => 'Celana Chino Slimfit',
            'slug' => 'celana-chino-slimfit',
            'description' => 'Celana panjang chino model slimfit bahan stretch katun twill premium. Sangat nyaman dan fleksibel untuk acara formal maupun santai.',
            'price' => 189000,
            'stock' => 60,
            'image' => 'products/chino-slimfit.png',
            'is_featured' => false,
        ]);

        Product::create([
            'category_id' => $fashion->id,
            'name' => 'Kemeja Flanel Casual',
            'slug' => 'kemeja-flanel-casual',
            'description' => 'Kemeja flanel lengan panjang motif kotak-kotak klasik dengan bahan katun flanel tebal bertekstur lembut dan hangat.',
            'price' => 169000,
            'stock' => 45,
            'image' => 'products/flanel-casual.png',
            'is_featured' => false,
        ]);

        // Makanan & Minuman
        Product::create([
            'category_id' => $makanan->id,
            'name' => 'Kopi Susu Gula Aren 1L',
            'slug' => 'kopi-susu-gula-aren-1l',
            'description' => 'Kopi susu gula aren premium dalam kemasan 1 liter, dibuat dari biji kopi arabika pilihan dan susu segar berkualitas tinggi.',
            'price' => 65000,
            'stock' => 20,
            'image' => 'products/kopi-susu-1l.png',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $makanan->id,
            'name' => 'Keripik Singkong Pedas',
            'slug' => 'keripik-singkong-pedas',
            'description' => 'Keripik singkong renyah dengan bumbu cabai asli yang gurih, pedas manis, tanpa bahan pengawet.',
            'price' => 15000,
            'stock' => 200,
            'image' => 'products/keripik-singkong.png',
            'is_featured' => false,
        ]);

        Product::create([
            'category_id' => $makanan->id,
            'name' => 'Cokelat Almond Premium',
            'slug' => 'cokelat-almond-premium',
            'description' => 'Cokelat batang premium dengan paduan kacang almond panggang yang renyah dan gurih di setiap gigitan.',
            'price' => 35000,
            'stock' => 80,
            'image' => 'products/cokelat-almond.png',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $makanan->id,
            'name' => 'Teh Matcha Bubuk Organik 100g',
            'slug' => 'teh-matcha-bubuk-organik-100g',
            'description' => '100% bubuk teh hijau matcha Jepang asli kualitas kuliner (culinary grade). Sangat cocok untuk matcha latte, cake, dan minuman segar.',
            'price' => 89000,
            'stock' => 35,
            'image' => 'products/matcha-100g.png',
            'is_featured' => false,
        ]);
    }
}
