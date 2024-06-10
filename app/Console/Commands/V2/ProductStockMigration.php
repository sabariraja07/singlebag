<?php

namespace App\Console\Commands\V2;

use App\Models\Shop;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Support\Arr;
use App\Models\ProductOption;
use App\Models\ProductVariant;
use Illuminate\Console\Command;
use App\Models\ProductOptionValue;
use Illuminate\Support\Facades\DB;

class ProductStockMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:stock-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Product stock Migration';

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
        $products = new Product();
        $products->chunk(10, function ($list) {
            $list->each(function (Product $product) {
                $stock = DB::table('old_stocks')->where('product_id', $product->id)->first();

                $this->info($stock->id);
                $variants = ProductVariant::where('product_id', $product->id);
                $variants->chunk(10, function ($list) use ($stock) {
                    $list->each(function (ProductVariant $variant) use ($stock) {
                        if (!$stock) {
                            Stock::updateOrCreate([
                                'variant_id' => $variant->id
                            ], [
                                'manage_stock' => 0,
                                'status' => 0,
                                'quantity' => 0,
                                'alert_qty' => 5,
                                'location_id' => null,
                            ]);
                            return;
                        } else {
                            Stock::updateOrCreate([
                                'variant_id' => $variant->id
                            ], [
                                'manage_stock' => $stock->stock_manage,
                                'status' => $stock->stock_manage,
                                'quantity' => $stock->stock_qty,
                                'alert_qty' => 5,
                                'location_id' => null,
                            ]);
                        }
                        if ($stock->sku)
                            $variant->update(['sku' => $stock->sku . '-' . $variant->id]);
                    });
                });
            });
        });
    }
}
