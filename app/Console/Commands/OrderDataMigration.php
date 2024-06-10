<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Address;
use App\Models\Customer;
use App\Models\OrderLine;
use App\Models\OrderItem;
use App\Models\OrderMeta;
use App\Models\OrderAddress;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OrderDataMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:data-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'order data migration';

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
                $address_meta = OrderMeta::where('order_id', $order->id)->where('key', 'content')->first();
                $address_meta = $address_meta ? json_decode($address_meta->value) : null;
                $customer = null;

                if (isset($address_meta)) {
                    $customer = Customer::where('email', $address_meta->email ?? null)->orWhere('mobile', $address_meta->phone ?? null)->first();
                    $address = Address::create([
                        'customer_id' => $customer ? $customer->id : null,
                        'title' => $address_meta->name ?? null,
                        'first_name' => $address_meta->first_name ?? $address_meta->name ?? '',
                        'last_name' => $address_meta->last_name ?? "",
                        'company_name' => null,
                        'contact_email' => $address_meta->email ?? null,
                        'contact_phone' => $address_meta->phone ?? null,
                        'line_one' => $address_meta->address ?? '',
                        'line_two' => null,
                        'line_three' => null,
                        'state_id' => null,
                        'city_id' => null,
                        'postcode' => $address_meta->zip_code ?? null,
                        'delivery_instructions' => null,
                        'shipping_default' => 1,
                        'billing_default' => 0,
                        'meta' => null,
                    ]);
                    OrderAddress::updateOrCreate(['order_id' => $order->id, 'type' => 'shipping'], $address->toArray());
                }

                $order->update([
                    'compare_currency_code' => 'INR',
                    'currency_code' => 'INR',
                    'exchange_rate' => 1,
                    'placed_at' =>  $order->created_at,
                    'channel' => 'online',
                    'notes' => $address_meta->comment ?? null,
                    'subtotal' =>  $address_meta->sub_total ?? $order->subtotal,
                    'tax' =>  $address_meta->tax ?? $order->tax
                ]);

                $item = OrderLine::where('order_id', $order->id)->first();
                if (isset($item)) {
                    $product = Product::find($item->product_id);
                    OrderItem::updateOrCreate(
                        [
                            'order_id' => $order->id,
                            'product_id' => $item->product_id
                        ],
                        [
                            'type' => 'physical',
                            'title' => $product ? $product->title : "",
                            'description' => $product ? $product->short_description : "",
                            'option' => '',
                            'unit_price' => $item->amount,
                            'unit_quantity' => 1,
                            'quantity' => $item->qty,
                            'sub_total' => $this->getSubTotal($item),
                            'discount_total' => $this->getDiscount($item),
                            'meta' => $item->info,
                            'tax_breakdown' => [],
                            'tax_total' => $this->getTax($item),
                            'shipping_total' => 0,
                            'total' => ($item->amount * $item->qty),
                        ]
                    );
                    $this->info($item->id);
                }
            });
        });
    }

    public function getSubTotal($item)
    {
        if (!isset($item->info->subTotal))
            return 0;
        return $item->info->subTotal;
    }

    public function getTax($item)
    {
        if (!isset($item->info->tax))
            return 0;
        return $item->info->tax;
    }

    public function getDiscount($item)
    {
        if (!isset($item->info->discount))
            return 0;
        return $item->info->discount;
    }
}
