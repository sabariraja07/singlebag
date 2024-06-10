<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellerOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;
        $order_url = $data['order_url'];
        $order_no = $data['order_no'];
        $base_url = $data['base_url'];
        $site_name = $data['site_name'];
        $shop_id = $data['shop_id'] ?? '';
        $shop = \App\Models\Shop::where('id', $shop_id)->first();
        $user_mail = \App\Models\User::where('id', $shop->user_id)->first();

        $order = $data['order'];
        $location = $data['location'];
        $order_content = $data['order_content'];

        $gst = \App\Models\ShopOption::where('key', 'gst')->where('shop_id', $order->shop_id)->first();
        $gst = $gst->value ?? '';


        return $this->from($user_mail->email ?? env('MAIL_TO'))
            ->subject('Order Confirmation Mail ' . $order_no)
            ->view('email.seller_order_receive', compact('order_url', 'order_no', 'base_url', 'site_name', 'shop', 'shop_id', 'order', 'location', 'order_content'));
    }
}
