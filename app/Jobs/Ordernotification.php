<?php

namespace App\Jobs;

use App\Mail\AdminOrderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SellerOrderMail;
use Illuminate\Support\Facades\Mail;

class Ordernotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data=$this->data;
        $order_content = $data['order_content'];
        Mail::to($order_content->email)->send(new SellerOrderMail($data)); 
        Mail::to($data['store_email'])->send(new AdminOrderMail($data));
    }
}
