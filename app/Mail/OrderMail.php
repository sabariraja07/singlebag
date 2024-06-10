<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
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
        $info=$data['info'];


        $info_sub = \App\Models\Subscription::with('plan_info', 'PaymentMethod', 'user', 'shop')->findorFail($info->id);
        $shop = \App\Models\Shop::with(['domain', 'user'])->find($info_sub->shop->id);
        $company_info = \App\Models\Option::where('key', 'company_info')->first();
        $company_info = json_decode($company_info->value);
        $gst = \App\Models\Option::where('key', 'gst')->first();
        $company_info->gst = $gst ? $gst->value : null;
        $pdf = \PDF::loadView('email.subscription_invoicepdf', compact('company_info', 'info', 'shop'));
              
        return $this->from(env('MAIL_TO') ??  $data['from_email'])
                    ->subject('You Got A New Order')
                    ->view('email.admin_order_recive',compact('info','shop'))
                    ->attachData($pdf->output(), 'invoice_' . $info->order_no . '.pdf');

                    
    }
}
