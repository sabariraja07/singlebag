<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\CronController;

class FreeFourthMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:free_trail_fourth_mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fourth day from the day of registration for free trial';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cron = new CronController;
        $cron->FreeTrailFourthJob();
    }
}
