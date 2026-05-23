<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;
    protected $product;
    protected $order;

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
            'name' => 'Test Figures',
            'slug' => 'figures',
            'description' => 'Figures category',
        ]);

        // Create a test product
        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Nendoroid Gojo',
            'slug' => 'nendoroid-gojo',
            'description' => 'Description Gojo',
            'price' => 700000,
            'stock' => 5,
        ]);

        // Create an unpaid order
        $this->order = Order::create([
            'user_id' => $this->user->id,
            'order_number' => 'ORD-TEST12345',
            'total_price' => 700000,
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_UNPAID,
            'shipping_name' => 'Budi Santoso',
            'shipping_phone' => '08123456789',
            'shipping_address' => 'Jl. Merdeka No. 10',
        ]);

        // Create order item
        OrderItem::create([
            'order_id' => $this->order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);
    }

    /**
     * Test that order detail page loads successfully even if Midtrans API fails (graceful degradation).
     */
    public function test_payment_page_loads_resiliently_on_token_failures(): void
    {
        $response = $this->actingAs($this->user)
            ->get('/orders/' . $this->order->id);

        $response->assertStatus(200);
        $response->assertViewIs('orders.show');
        $response->assertSee($this->order->order_number);
        $response->assertSee('Menunggu Pembayaran');
    }

    /**
     * Test that a valid Midtrans success webhook updates order status to Paid.
     */
    public function test_webhook_validation_and_successful_payment_update(): void
    {
        $orderId = $this->order->order_number;
        $statusCode = '200';
        $grossAmount = '700000.00';
        $serverKey = config('midtrans.server_key');
        
        // Generate valid signature key
        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $response = $this->postJson('/payment/notification', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signatureKey,
            'transaction_status' => 'settlement',
            'payment_type' => 'bank_transfer',
            'transaction_id' => 'midtrans-tx-12345',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        // Assert order status in database was updated
        $this->order->refresh();
        $this->assertEquals(Order::PAYMENT_PAID, $this->order->payment_status);
        $this->assertEquals(Order::STATUS_PROCESSING, $this->order->status);

        // Assert payment record was logged
        $this->assertDatabaseHas('payments', [
            'order_id' => $this->order->id,
            'transaction_id' => 'midtrans-tx-12345',
            'payment_type' => 'bank_transfer',
            'status' => 'paid',
        ]);
    }

    /**
     * Test that a valid Midtrans cancellation/expire webhook cancels the order and reverts stock.
     */
    public function test_webhook_failed_payment_updates_status_and_reverts_stock(): void
    {
        // Assert initial stock is 5
        $this->assertEquals(5, $this->product->stock);

        $orderId = $this->order->order_number;
        $statusCode = '202';
        $grossAmount = '700000.00';
        $serverKey = config('midtrans.server_key');
        
        // Generate valid signature key
        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $response = $this->postJson('/payment/notification', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signatureKey,
            'transaction_status' => 'expire',
            'payment_type' => 'bank_transfer',
            'transaction_id' => 'midtrans-tx-12345',
        ]);

        $response->assertStatus(200);

        // Assert order status updated to failed and cancelled
        $this->order->refresh();
        $this->assertEquals(Order::PAYMENT_FAILED, $this->order->payment_status);
        $this->assertEquals(Order::STATUS_CANCELLED, $this->order->status);

        // Assert stock was reverted back (+1 because quantity was 1)
        $this->product->refresh();
        $this->assertEquals(6, $this->product->stock);

        // Assert payment record logged
        $this->assertDatabaseHas('payments', [
            'order_id' => $this->order->id,
            'status' => 'failed',
        ]);
    }
}
