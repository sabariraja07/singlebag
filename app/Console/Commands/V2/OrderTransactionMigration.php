<?php

namespace App\Console\Commands\V2;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class OrderTransactionMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:transaction-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'order transaction migration';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = new Order();
        $orders->chunk(10, function ($list) {
            $list->each(function (Order $order) {
                Transaction::updateOrCreate([
                    // 'parent_id' => null,
                    'shop_id' => $order->shop_id,
                    'payable_type' => get_class($order),
                    'payable_id' => $order->id,
                ], [
                    'success' => 1,
                    'type' => 'capture',
                    'gateway' => $order->payment_method ?? 'cod',
                    'amount' => $order->total,
                    'reference' => $order->transaction_id ?? Str::random(8),
                    'status' => $this->paymentStatus($order->payment_status),
                    'notes' => null,
                    'currency_code' => 'INR',
                    'log' => null,
                    'meta' => null,
                ]);
            });
        });
    }

    public function paymentStatus($status)
    {
        if ($status == 1) {
            return 'complete';
        }

        if ($status == 1) {
            return 'pending';
        }

        if ($status == 0) {
            return 'cancel';
        }

        if ($status == 3) {
            return 'incomplete';
        }

        return 'cancel';
    }
}
