<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewStoreWelcomeMail extends Mailable
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
        $shop = $this->data;
             
        
        return $this->from(env('MAIL_FROM_ADDRESS'))
                    ->subject('Welcome to Singlebag. Congratulations on your successful store registration!')
                    ->view('email.new_store_welcome',compact('shop'));
    }
}
