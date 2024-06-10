<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LowInventoryMail extends Mailable
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
        $stock_details = $this->data;
        $stock = $stock_details['stock'];
        $product = $stock_details['product'];
        $full_domain = $stock_details['full_domain'];
        $shop_name = $stock_details['shop_name'];          
        
        return $this->from(env('MAIL_FROM_ADDRESS'))
                    ->subject('Low Stock Quantity Alert-Update Your Stock Status.')
                    ->view('email.low_inventory_stock_alert',compact('stock','product','full_domain','shop_name'));
    }
}
