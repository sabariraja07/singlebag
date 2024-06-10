<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Price;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductPriceUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $offer_ends = Price::whereDate('ending_date', '<', Carbon::now())->get();

        foreach ($offer_ends as $offer_end) {
            $offer_end->price = $offer_end->regular_price;
            $offer_end->save();
        }

        $offer_prices = Price::whereDate('ending_date', '>=', Carbon::now())->whereDate('starting_date', Carbon::now())->get();

        foreach ($offer_prices as $offer_price) {
            if($offer_price->price_type == 1){
                $offer_price->price = $offer_price->regular_price - $offer_price->special_price;
            }else if($offer_price->price_type == 0){
                $offer_price->price = $offer_price->regular_price - round($offer_price->regular_price * ( $offer_price->special_price * 0.01), 2);
            }
            if($offer_price->price >= 0){
                $offer_price->save();
            }
        }
    }
}
