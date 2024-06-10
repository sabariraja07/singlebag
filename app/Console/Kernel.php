<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('make:product-price-update')->daily();
        $schedule->command('make:make_expirable_user')->daily();
        $schedule->command('make:send_mail_to_will_expire_plan_soon')->daily();
        $schedule->command('make:store_academy_mail')->daily();
        $schedule->command('make:partner_academy_mail')->daily();
        $schedule->command('make:free_trail_fourth_mail')->daily();
        $schedule->command('make:free_trail_thirteen_mail')->daily();
        $schedule->command('make:free_trail_tenth_mail')->daily();
        $schedule->command('make:free_trail_over_mail')->daily();
        $schedule->command('make:shop_mode')->everyMinute();
        $schedule->command('settlement:supplier')->daily();
        $schedule->command('make:store_day_two')->daily();
        $schedule->command('make:store_day_three')->daily();
        $schedule->command('make:store_day_four')->daily();
        $schedule->command('make:store_day_five')->daily();
        $schedule->command('make:store_day_six')->daily();
        $schedule->command('make:store_day_seven')->daily();
        $schedule->command('make:store_day_eight')->daily();
        $schedule->command('make:store_day_nine')->daily();
        $schedule->command('make:store_day_ten')->daily();
        $schedule->command('make:store_day_eleven')->daily();
        $schedule->command('make:store_day_twelve')->daily();
        $schedule->command('make:store_day_thirteen')->daily();
        $schedule->command('make:store_day_fourteen')->daily();
        $schedule->command('make:store_day_fifteen')->daily();
        $schedule->command('make:store_day_sixteen')->daily();
        $schedule->command('make:store_day_seventeen')->daily();
        $schedule->command('make:store_day_eighteen')->daily();
        $schedule->command('make:store_day_nineteen')->daily();
        $schedule->command('make:store_day_twenty')->daily();
        $schedule->command('make:store_day_twenty_one')->daily();
        $schedule->command('make:store_day_twenty_two')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
