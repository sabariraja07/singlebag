<?php

namespace App\Console\Commands\V2;

use Illuminate\Console\Command;
use App\Jobs\ProductPriceUpdateJob;

class ProductPriceUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:product-price-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command will work when offer price available or expired products only.';

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

        dispatch(new ProductPriceUpdateJob());
    }
}
