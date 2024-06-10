<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\CronController;

class PartnerAcademyMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:partner_academy_mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sent after two days of registration of partner';

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
        $cron->PartnerAcademyJob();
    }
}
