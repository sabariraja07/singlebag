<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FreeTrailFourthMail extends Mailable
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
        
    
        return $this->from(env('MAIL_TO'))
                    ->subject('Free Trail Fourth Day Mail')
                    ->view('email.freetrail_fourth_mail',compact('shop_name','full_domain'));
    }
}
