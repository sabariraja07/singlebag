<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Order;
use App\Models\ShopOption;
use App\Mail\AdminOrderMail;
use App\Mail\SellerOrderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SellerNewOrderNotification;
use Illuminate\Support\Facades\Log;

class SupplierOrderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $order = $this->order;
            $shop = Shop::with('user')->find($order->shop_id);
            $get_order = Order::where('shop_id', $shop->id)->with('order_item_with_stock', 'order_item_with_file', 'customer', 'order_content', 'shipping_info', 'order_stock_meta', 'files', 'gateway')->find($order->id);

            $store_email = $shop->user ? $shop->user->email : '';
            $location = ShopOption::where('key', 'location')->where('shop_id', $order->shop_id)->first();
            $location = json_decode($location->value ?? '');
            $order_content = json_decode($get_order->order_content->value ?? '');

            if (isset($shop->user)) {
                $message = [
                    'title' => 'New order created',
                    'message' => $order->order_no . ' order created successfully.',
                    'order_id' => $order->id,
                    'url' => '',
                ];
                Notification::send($shop->user, new SellerNewOrderNotification($message));
            }


            $mail_data['order'] = $get_order ?? '';
            $mail_data['location'] = $location;
            $mail_data['order_content'] = $order_content;


            $mail_data['store_email'] = $store_email;
            $mail_data['order_no'] = $order->order_no;
            $mail_data['base_url'] = url('/');
            $mail_data['site_name'] = $shop->name;
            $mail_data['order_url'] = url('/seller/order', $order->id);
            $mail_data['shop_id'] =  $order->shop_id;

            $method = "email";

            if ($method == 'email') {
                if (env('QUEUE_MAIL') == 'on') {
                    dispatch(new \App\Jobs\Ordernotification($mail_data));
                } else {
                    Mail::to($order_content->email)->send(new SellerOrderMail($mail_data));
                    Mail::to($store_email)->send(new AdminOrderMail($mail_data));
                    dispatch(new \App\Jobs\ResellerOrderEmail($this->order));
                }
            }
        }catch (\Exception $e) {
            Log::warning($e->getMessage());
        }
    }
}
