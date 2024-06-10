<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerRegisterMail extends Mailable
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
             
        
        return $this->from($data->shop->email ?? env('MAIL_TO'))
                    ->subject('Congratulations! Your account successfully registered in ' . ucfirst($this->data->shop->name) .'.')
                    ->view('email.customer_register',compact('data'));
    }
}
