<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckPendingPayments extends Command
{
    protected $signature = 'payments:check-pending
                            {--hours=24 : Hanya cek order dalam N jam terakhir}
                            {--limit=10 : Maksimal order per run}';

    protected $description = 'Cek status pembayaran pending ke Midtrans API (cron fallback jalur ke-3)';

    public function handle(PaymentService $paymentService): int
    {
        $pendingOrders = Order::where('payment_status', 'PENDING')
            ->where('created_at', '>=', now()->subHours($this->option('hours')))
            ->whereHas('payment', fn($q) => $q->where('gateway', 'midtrans'))
            ->orderBy('created_at', 'desc')
            ->limit($this->option('limit'))
            ->get();

        if ($pendingOrders->isEmpty()) {
            $this->info('Tidak ada pending payment.');
            return self::SUCCESS;
        }

        $count = $pendingOrders->count();
        $this->info("Memeriksa {$count} order pending...");

        foreach ($pendingOrders as $order) {
            try {
                $status = $paymentService->syncPaymentStatus($order);
                $this->line("  [{$order->order_code}] → {$status}");
                // Sleep 1 detik antar order untuk hindari rate limit Midtrans
                if ($count > 1) sleep(1);
            } catch (\Throwable $e) {
                Log::error('CheckPendingPayments error', [
                    'order' => $order->order_code,
                    'error' => $e->getMessage(),
                ]);
                $this->error("  [{$order->order_code}] ERROR: {$e->getMessage()}");
            }
        }

        return self::SUCCESS;
    }
}
