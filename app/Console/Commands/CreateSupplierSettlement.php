<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Option;
use App\Models\Settlement;
use App\Models\ShopOption;
use App\Models\OrderSettlement;
use Illuminate\Console\Command;

class CreateSupplierSettlement extends Command
{
    private $settlement_period;
    private $settlement_tax = 0;
    private $supplier_commission = 0;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settlement:supplier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create supplier settlement';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $supplier_settlement = Option::where('key', 'supplier_settlement_period')->first();
        if(!isset($supplier_settlement)){
            $this->settlement_period = 7;
        } else {
            $this->settlement_period = (int) $supplier_settlement->value ?? 7;
        }
        $tax = Option::where('key', 'settlement_tax')->first();
        $this->settlement_tax = isset($tax) ? $tax->value : 0;
        $commission = Option::where('key', 'supplier_commission')->first();
        $this->supplier_commission =  isset($commission) ? $commission->value : 0;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $shops = Shop::where('shop_type', 'supplier')->where('status', 'active');
        $shops->chunk(50, function($list) {
            $list->each(function (Shop $shop) {
                $this->create_settlement($shop);
            });
        });
    }

    public function create_settlement($shop)
    {
        $settlement_from = Carbon::now()->subDays($this->settlement_period);
        $start_date = Carbon::now()->subDays($this->settlement_period)->startOfDay();
        $end_date = Carbon::now()->endOfDay();

        $bank_details = ShopOption::where('shop_id', $shop->id)->where('key', 'bank_details')->first();
        $bank_details = $bank_details ? json_decode($bank_details->value, true) : null;

        $settlement_amount = OrderSettlement::whereHas('order', function($query) use($settlement_from) {
                                        $query->where('status', 'completed')
                                        ->where('payment_status', '1')
                                        ->where('order_type', 1)
                                        ->whereDate('updated_at', '<=', $settlement_from);
                                    })
                                    ->where('shop_id', $shop->id)
                                    ->whereNull('settlement_id')
                                    ->sum('amount');

        $settlement_total = OrderSettlement::whereHas('order', function($query) use($settlement_from) {
                                            $query->where('status', 'completed')
                                            ->where('payment_status', '1')
                                            ->where('order_type', 1)
                                            ->whereDate('updated_at', '<=', $settlement_from);
                                        })
                                        ->where('shop_id', $shop->id)
                                        ->whereNull('settlement_id')
                                        ->sum('total');

        if($settlement_total <= 0) return;

        $commission_tax = $settlement_amount * ($this->settlement_tax * 0.01);
        $charge = $settlement_amount * ($this->supplier_commission * 0.01);
        $settlement = new Settlement();
        $settlement->amount = $settlement_total - $commission_tax - $charge;
        $settlement->tax = $commission_tax;
        $settlement->total_amount = $settlement_total;
        $settlement->charge = $charge;
        $settlement->settlement_rate = $this->supplier_commission;
        $settlement->start_date = $start_date;
        $settlement->end_date = $end_date;
        $settlement->status = 'unpaid';
        $settlement->bank_details = $bank_details;
        $settlement->settlement_date = Carbon::now();
        $settlement->is_request = 0;
        $settlement->paid_at = null;
        $settlement->interval = $this->settlement_period . " Days";
        $settlement->user_id = $shop->user_id;
        $settlement->shop_id = $shop->id;
        $settlement->save();

        $orders = Order::where('status', 'completed')
            ->where('payment_status', '1')
            ->where('order_type', 1)
            ->whereDate('updated_at', '<=', $settlement_from)
            ->whereHas('order_settlements', function($query) {
                $query->whereNull('settlement_id');
            });

        $orders->chunk(50, function($list) use($settlement) {
            $list->each(function (Order $order) use($settlement) {
                OrderSettlement::where('order_id', $order->id)
                ->where('type', 0)
                ->update([
                    'commission' => ($order->total - $order->tax) * ($this->supplier_commission * 0.01),
                    'settlement_id' => $settlement->id,
                ]);
            });
        });
    }
}
