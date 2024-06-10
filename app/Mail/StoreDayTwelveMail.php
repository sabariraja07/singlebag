<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;;

class StoreDayTwelveMail extends Mailable
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
        $data = $this->data;
        $shop_name = $data['name'];
        $full_domain = $data['full_domain'];
        $name = $data['user_name'];
        
        return $this->from(env('MAIL_TO'))
                    ->subject('Keep the momentum going! But your free trial is about to end')
                    ->view('email.store_day_twelve',compact('shop_name','full_domain','name'));
    }
}
