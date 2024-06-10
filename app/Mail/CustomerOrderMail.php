<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerOrderMail extends Mailable
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
        $this->data=$data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data=$this->data;
        $order=$data['order'];
        $location=$data['location'] ?? '';
        $order_content=$data['order_content'];
        $base_url=$data['url'];
        $product_file_url=$data['product_file_url'] ?? '';
        $gst = \App\Models\ShopOption::where('key', 'gst')->where('shop_id', $order->shop_id)->first();
        $gst = $gst->value ?? '';
        $get_shop_name = \App\Models\Shop::where('id', $order->shop_id)->first();
        $user_mail = \App\Models\User::where('id', $get_shop_name->user_id)->first();
        $pdf = \PDF::loadView('email.invoice', compact('order', 'order_content', 'location', 'gst','get_shop_name'));
  
        return $this->from($user_mail->email ?? env('MAIL_TO'))
                    ->subject('Order Mail')
                    ->view('email.email_invoice',compact('order','location','order_content','base_url','product_file_url','get_shop_name'))
                    ->attachData($pdf->output(), 'invoice_' . $order->order_no . '.pdf');
    }
}
