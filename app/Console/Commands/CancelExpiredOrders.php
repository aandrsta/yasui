<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CancelExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel unpaid pending orders that have exceeded 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = self::cancelExpired();
        $this->info("Successfully cancelled {$count} expired orders and reverted their stocks.");
        return 0;
    }

    /**
     * Cancel expired orders in real-time.
     * Returns the count of cancelled orders.
     *
     * @return int
     */
    public static function cancelExpired()
    {
        $expiredTime = Carbon::now()->subHours(24);

        $expiredOrders = Order::where('status', Order::STATUS_PENDING)
            ->where('payment_status', Order::PAYMENT_UNPAID)
            ->where('created_at', '<=', $expiredTime)
            ->with('items.product')
            ->get();

        $count = 0;
        foreach ($expiredOrders as $order) {
            DB::transaction(function () use ($order) {
                // Revert stock
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }

                $order->update([
                    'status' => Order::STATUS_CANCELLED,
                    'payment_status' => Order::PAYMENT_FAILED
                ]);
            });
            $count++;
        }

        return $count;
    }
}
