<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopCartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;
    protected $product;

    /**
     * Set up testing environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create([
            'role' => 'user',
        ]);

        // Create a test category
        $this->category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test Category Description',
        ]);

        // Create a test product
        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Anime Figure Megumi',
            'slug' => 'anime-figure-megumi',
            'description' => 'Description of Megumi',
            'price' => 500000,
            'stock' => 10,
        ]);
    }

    /**
     * Test that guests cannot access cart page.
     */
    public function test_guests_cannot_access_cart(): void
    {
        $response = $this->get('/cart');
        $response->assertRedirect('/login');
    }

    /**
     * Test that authenticated users can add items to cart.
     */
    public function test_authenticated_user_can_add_item_to_cart(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/cart', [
                'product_id' => $this->product->id,
                'quantity' => 2,
            ]);

        $response->assertRedirect('/cart');
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);
    }

    /**
     * Test that users cannot add items exceeding product stock.
     */
    public function test_user_cannot_add_item_exceeding_stock(): void
    {
        $response = $this->actingAs($this->user)
            ->from('/products/' . $this->product->slug)
            ->post('/cart', [
                'product_id' => $this->product->id,
                'quantity' => 15, // Stock is only 10
            ]);

        $response->assertRedirect('/products/' . $this->product->slug);
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('carts', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);
    }

    /**
     * Test that users can update quantity in cart.
     */
    public function test_user_can_update_cart_quantity(): void
    {
        $cartItem = Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($this->user)
            ->from('/cart')
            ->patch('/cart/' . $cartItem->id, [
                'quantity' => 5,
            ]);

        $response->assertRedirect('/cart');
        $this->assertDatabaseHas('carts', [
            'id' => $cartItem->id,
            'quantity' => 5,
        ]);
    }

    /**
     * Test that checkout creates an order, reduces stock, and clears cart.
     */
    public function test_checkout_creates_order_and_reduces_stock_and_clears_cart(): void
    {
        // 1. Add item to cart
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 3,
        ]);

        // 2. Perform checkout
        $response = $this->actingAs($this->user)
            ->post('/checkout', [
                'shipping_name' => 'Budi Santoso',
                'shipping_phone' => '08123456789',
                'shipping_address' => 'Jl. Merdeka No. 10, Jakarta',
            ]);

        // Assert redirect to order show page
        $order = Order::where('user_id', $this->user->id)->first();
        $this->assertNotNull($order);
        $response->assertRedirect('/orders/' . $order->id);

        // Assert database order and items
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'total_price' => 1500000, // 3 * 500000
            'shipping_name' => 'Budi Santoso',
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'quantity' => 3,
            'price' => 500000,
        ]);

        // Assert stock was reduced by 3 (10 - 3 = 7)
        $this->product->refresh();
        $this->assertEquals(7, $this->product->stock);

        // Assert cart is cleared
        $this->assertDatabaseMissing('carts', [
            'user_id' => $this->user->id,
        ]);
    }
}
