<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\StoreWelcomeMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewStoreWelcomeMail;

class StoreWelcomeMailJob implements ShouldQueue
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
        // Mail::to($data->email)->send(new StoreWelcomeMail($data)); 
        Mail::to($data->email)->send(new NewStoreWelcomeMail($data)); 
    }
}
