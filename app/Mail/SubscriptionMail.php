<?php

namespace App\Mail;

use App\Models\Option;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionMail extends Mailable
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

        $info = $this->data['info'];
        $comment = $this->data['comment'];
        $order = $this->data['order'] ?? '';
        $location = $this->data['location'] ?? '';
        $order_content = $this->data['order_content'] ?? '';
        $base_url = $this->data['url'] ?? '';
        $product_file_url = $this->data['product_file_url'] ?? '';


        $info_sub = \App\Models\Subscription::with('plan_info', 'PaymentMethod', 'user', 'shop')->findorFail($info->id);
        $shop = \App\Models\Shop::with(['domain', 'user'])->find($info_sub->shop->id);
        $company_info = \App\Models\Option::where('key', 'company_info')->first();
        $company_info = json_decode($company_info->value);
        $gst = \App\Models\Option::where('key', 'gst')->first();
        $company_info->gst = $gst ? $gst->value : null;
        $pdf = \PDF::loadView('email.subscription_invoicepdf', compact('company_info', 'info', 'shop'));

        $gst = Option::where('key', 'gst')->first();
        $gst = $gst ? $gst->value : null;


        return $this->from(env('MAIL_TO'))
            ->subject('[' . strtoupper(env('APP_NAME')) . ']' . ' - Subscription')
            ->view('email.subscription_invoice', compact('info', 'comment', 'gst', 'order', 'location', 'order_content', 'base_url', 'product_file_url'))
            ->attachData($pdf->output(), 'invoice_' . $info->order_no . '.pdf');
    }
}
