<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupPendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'orders:cleanup-pending
                            {--minutes=10 : Delete pending orders older than X minutes}
                            {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     */
    protected $description = 'Delete pending/unpaid orders older than specified minutes';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $minutes = (int) $this->option('minutes');
        $dryRun = $this->option('dry-run');
        $cutoff = now()->subMinutes($minutes);

        $query = Order::where('payment_status', '!=', 'PAID')
            ->where('created_at', '<', $cutoff);

        $count = $query->count();

        if ($count === 0) {
            $this->info('No pending orders older than ' . $minutes . ' minutes.');
            return self::SUCCESS;
        }

        if ($dryRun) {
            $this->warn("[DRY RUN] Would delete {$count} pending orders.");
            $orders = $query->get(['order_code', 'created_at', 'payment_status']);
            $this->table(['Order Code', 'Created At', 'Payment Status'], $orders);
            return self::SUCCESS;
        }

        // Delete orders and their related records
        $orders = $query->get();
        $deleted = 0;

        foreach ($orders as $order) {
            $order->items()->delete();
            $order->payment()->delete();
            $order->feedback()->delete();
            $order->delete();
            $deleted++;
        }

        Log::info("CleanupPendingOrders: Deleted {$deleted} pending orders older than {$minutes} minutes.");
        $this->info("Deleted {$deleted} pending orders.");

        return self::SUCCESS;
    }
}
