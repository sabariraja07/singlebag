<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FreeTrailTenthMail extends Mailable
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
        $shop_name = $data['shop_name'];
        $full_domain = $data['full_domain'];
        $name = $data['name'];
        
        
    
        return $this->from(env('MAIL_TO'))
                    ->subject('Free Trail Tenth Day Mail')
                    ->view('email.freetrail_tenth_mail',compact('shop_name','full_domain','name'));
    }
}
