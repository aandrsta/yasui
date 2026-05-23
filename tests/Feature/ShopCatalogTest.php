<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopCatalogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up the testing environment.
     * Seed categories and products for each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed database using DatabaseSeeder
        $this->seed();
    }

    /**
     * Test that the homepage loads successfully and displays categories and featured products.
     */
    public function test_homepage_loads_successfully_and_displays_featured_products(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('shop.home');
        
        // Assert category names are visible
        $response->assertSee('Figures');
        $response->assertSee('Model Kits');
        $response->assertSee('Character Goods');
        $response->assertSee('Plushies');

        // Assert featured products are visible
        $response->assertSee('Nendoroid Hatsune Miku: V4X');
        $response->assertSee('Pop Up Parade Nakano Miku');
        $response->assertSee('RG 1/144 RX-93-v2 Hi-v Gundam');
    }

    /**
     * Test that the product catalog index loads successfully and displays all seeded products.
     */
    public function test_catalog_page_loads_successfully_and_displays_all_products(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertViewIs('shop.products');
        
        // Assert we see Page 1 products (guaranteed stable items on Page 1 due to ID ordering)
        $response->assertSee('Nendoroid Hatsune Miku: V4X');
        $response->assertSee('Pop Up Parade Nakano Miku');
        $response->assertSee('Scale Figure 1/7 Tokisaki Kurumi: Bunny Ver.');

        // Request Page 2
        $responsePage2 = $this->get('/products?page=2');
        $responsePage2->assertStatus(200);
        
        // Assert we see Page 2 products (guaranteed stable items on Page 2)
        $responsePage2->assertSee('Nesoberi Plush Lay Down Megumi Fushiguro (M)');
        $responsePage2->assertSee('Spy x Family Anya Forger Chimera Plush Doll');
    }

    /**
     * Test that catalog can filter products by search query.
     */
    public function test_catalog_page_can_search_products(): void
    {
        // Search for 'Miku'
        $response = $this->get('/products?q=Miku');

        $response->assertStatus(200);
        $response->assertSee('Nendoroid Hatsune Miku: V4X');
        // Should not see unrelated items
        $response->assertDontSee('RG 1/144 RX-93-v2 Hi-v Gundam');
        $response->assertDontSee('Acrylic Stand Gojo Satoru - Shibuya Incident Ver.');
    }

    /**
     * Test that catalog can filter products by category.
     */
    public function test_catalog_page_can_filter_by_category(): void
    {
        // Filter by 'figures' category
        $response = $this->get('/products?category=figures');

        $response->assertStatus(200);
        $response->assertSee('Nendoroid Hatsune Miku: V4X');
        $response->assertSee('Pop Up Parade Nakano Miku');
        
        // Should not show items from other categories
        $response->assertDontSee('RG 1/144 RX-93-v2 Hi-v Gundam');
        $response->assertDontSee('Spy x Family Anya Forger Chimera Plush Doll');
    }

    /**
     * Test that catalog can sort products by price ascending.
     */
    public function test_catalog_page_can_sort_by_price_ascending(): void
    {
        $response = $this->get('/products?sort=price_asc');

        $response->assertStatus(200);
        
        // Get the products ordered by price asc from the view data
        $productsInView = $response->viewData('products');
        
        $this->assertNotEmpty($productsInView);
        
        $previousPrice = 0;
        foreach ($productsInView as $product) {
            $this->assertGreaterThanOrEqual($previousPrice, (float) $product->price);
            $previousPrice = (float) $product->price;
        }
    }

    /**
     * Test that catalog can sort products by price descending.
     */
    public function test_catalog_page_can_sort_by_price_descending(): void
    {
        $response = $this->get('/products?sort=price_desc');

        $response->assertStatus(200);
        
        // Get the products ordered by price desc from the view data
        $productsInView = $response->viewData('products');
        
        $this->assertNotEmpty($productsInView);
        
        $previousPrice = PHP_INT_MAX;
        foreach ($productsInView as $product) {
            $this->assertLessThanOrEqual($previousPrice, (float) $product->price);
            $previousPrice = (float) $product->price;
        }
    }

    /**
     * Test that single product detail page loads successfully with correct specifications and related items.
     */
    public function test_product_detail_page_loads_successfully(): void
    {
        $product = Product::where('slug', 'nendoroid-hatsune-miku-v4x')->first();
        $this->assertNotNull($product);

        $response = $this->get('/products/' . $product->slug);

        $response->assertStatus(200);
        $response->assertViewIs('shop.product-detail');
        
        // Assert details are loaded
        $response->assertSee($product->name);
        $response->assertSee($product->description);
        $response->assertSee($product->formatted_price);

        // Assert related products in same category (Figures) are displayed
        $response->assertSee('Pop Up Parade Nakano Miku');
    }

    /**
     * Test that a non-existing product detail slug returns 404.
     */
    public function test_product_detail_returns_404_for_non_existing_product(): void
    {
        $response = $this->get('/products/non-existent-product-slug');

        $response->assertStatus(404);
    }
}

