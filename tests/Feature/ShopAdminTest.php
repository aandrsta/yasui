<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $normalUser;
    protected $category;
    protected $product;

    /**
     * Set up testing environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create a normal user
        $this->normalUser = User::factory()->create([
            'role' => 'user',
            'email' => 'user@gmail.com'
        ]);

        // 2. Create an admin user
        $this->adminUser = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@gmail.com'
        ]);

        // 3. Create a test category
        $this->category = Category::create([
            'name' => 'Figures',
            'slug' => 'figures',
            'description' => 'Test figures'
        ]);

        // 4. Create a test product
        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Nendoroid Satoru Gojo',
            'slug' => 'nendoroid-satoru-gojo',
            'description' => 'High quality figure',
            'price' => 750000,
            'stock' => 5,
            'is_featured' => true
        ]);
    }

    /**
     * Test that guests and normal users cannot access admin pages.
     */
    public function test_guest_and_user_cannot_access_admin(): void
    {
        // Guests should be redirected to login
        $response1 = $this->get('/admin/dashboard');
        $response1->assertRedirect('/login');

        // Authenticated normal users should be blocked with 403 Forbidden
        $response2 = $this->actingAs($this->normalUser)
            ->get('/admin/dashboard');
        $response2->assertStatus(403);
    }

    /**
     * Test that admin can access dashboard overview.
     */
    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Panel Pengelola Toko');
        $response->assertSee('Total Pendapatan');
        $response->assertSee('Total Pembeli');
    }

    /**
     * Test that admin can create a product.
     */
    public function test_admin_can_create_product(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->post('/admin/products', [
                'category_id' => $this->category->id,
                'name' => 'Figma Megumi Fushiguro',
                'description' => 'Megumi action figure figma',
                'price' => 850000,
                'stock' => 8,
                'is_featured' => 1
            ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.products.index'));

        // Assert database has the new product
        $this->assertDatabaseHas('products', [
            'name' => 'Figma Megumi Fushiguro',
            'price' => 850000,
            'stock' => 8,
            'is_featured' => true
        ]);
    }

    /**
     * Test that admin can update an order's status.
     */
    public function test_admin_can_update_order_status(): void
    {
        // Create an unpaid order for the normal user
        $order = Order::create([
            'user_id' => $this->normalUser->id,
            'order_number' => 'ORD-TEST111',
            'total_price' => 750000,
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_UNPAID,
            'shipping_name' => 'Andi',
            'shipping_phone' => '08111222333',
            'shipping_address' => 'Jl. Mawar No. 5',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->patch('/admin/orders/' . $order->id . '/status', [
                'status' => 'processing'
            ]);

        $response->assertStatus(302);

        // Assert status was updated to processing
        $order->refresh();
        $this->assertEquals(Order::STATUS_PROCESSING, $order->status);
    }

    /**
     * Test that user can view their own order history listing page.
     */
    public function test_user_can_view_order_history(): void
    {
        // Create an order for the user
        $order = Order::create([
            'user_id' => $this->normalUser->id,
            'order_number' => 'ORD-MYHISTORY',
            'total_price' => 750000,
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_UNPAID,
            'shipping_name' => 'Andi',
            'shipping_phone' => '08111222333',
            'shipping_address' => 'Jl. Mawar No. 5',
        ]);

        $response = $this->actingAs($this->normalUser)
            ->get('/orders');

        $response->assertStatus(200);
        $response->assertSee('ORD-MYHISTORY');
        $response->assertSee('Riwayat Pesanan Saya');
    }
}
