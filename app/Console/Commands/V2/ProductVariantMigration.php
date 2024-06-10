<?php

namespace App\Console\Commands\V2;

use App\Models\Shop;
use App\Models\Product;
use Illuminate\Support\Arr;
use App\Models\ProductOption;
use App\Models\ProductVariant;
use Illuminate\Console\Command;
use App\Models\ProductOptionValue;
use Illuminate\Support\Facades\DB;

class ProductVariantMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:variant-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Product Variant Migration';

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
        Shop::chunk(10, function ($list) {
            $list->each(function (Shop $shop) {
                $options = DB::table('old_product_options')->where('shop_id', $shop->id)->where('type', 1)->groupBy('name')->orderBy('name');
                $options->chunk(10, function ($list) {
                    $list->each(function ($option) {
                        $value = ProductOption::updateOrCreate([
                            'name' => $option->name,
                            'shop_id' => $option->shop_id
                        ], [
                            'type' => $option->name,
                        ]);
                        DB::table('old_product_options')->where('shop_id', $option->shop_id)->where('type', 1)->where('name', $option->name)->update(['value_id' => $value->id]);
                    });
                });
                $values = DB::table('old_product_options')->where('shop_id', $shop->id)->where('type', 0)->groupBy('name')->orderBy('name');
                $values->chunk(10, function ($list) {
                    $list->each(function ($option_value) {
                        $option = DB::table('old_product_options')->where('id', $option_value->p_id)->first();
                        if (!isset($option->value_id)) return;
                        $value = ProductOptionValue::updateOrCreate([
                            'name' => $option_value->name,
                            'product_option_id' => $option->value_id
                        ], [
                            'position' => 0,
                        ]);
                        $this->info($option->value_id);
                        DB::table('old_product_options')->where('shop_id', $option_value->shop_id)->where('type', 0)->where('name', $option_value->name)->update(['value_id' => $value->id]);
                    });
                });
            });
        });

        $products = new Product();
        $products->chunk(10, function ($list) {
            $list->each(function (Product $product) {
                $price = DB::table('old_prices')->where('product_id', $product->id)->first();
                if (!isset($price)) return;
                DB::table('old_product_options')->where('type', 1)->where('product_id', $product->id)->update(['price' => $price->price]);
                $this->info($price->price);

                $options = DB::table('old_product_options')->where('product_id', $product->id)->where('type', 0)->orderBy('id');
                $options->chunk(10, function ($list) {
                    $list->each(function ($option) {
                        $price = DB::table('old_prices')->where('product_id', $option->product_id)->first();
                        if (!isset($price)) return;
                        $amount = 0;
                        if ($option->amount_type == 1) {
                            $amount = $option->amount;
                        } else {
                            $amount = ($price->price * $option->amount * 0.01);
                        }
                        $this->info($amount);
                        DB::table('old_product_options')->where('id', $option->id)->update(['price' => $amount]);
                    });
                });
                $ids = DB::table('old_product_options')->where('type', 1)->where('product_id', $product->id)->groupBy('value_id')->get()->pluck('value_id');
                $array = [];
                if (count($ids) <= 0) return;
                foreach ($ids as $id) {
                    $values = DB::table('old_product_options')->where('type', 0)->where('product_id', $product->id)->groupBy('value_id')->get()->pluck('value_id')->toArray();
                    if (count($values) <= 0) return;
                    $array[$id] = $values;
                }
                $permutations = Arr::permutate(
                    $array
                );

                $baseVariant = ProductVariant::create([
                    'product_id' => $product->id,
                    'unit_qty' => 1,
                    'type' => 'physical',
                    'shippable' => 1,
                    'length' => 1.5,
                    'width' => 1.5,
                    'height' => 1.5,
                    'weight' => 1.5,
                    'volume' => 1.5
                ]);

                $baseVariant = $product->variants->first();

                DB::transaction(function () use ($permutations, $baseVariant, $product) {
                    // Validation bits
                    $rules = [];

                    foreach ($permutations as $key => $optionsToCreate) {
                        $variant = new ProductVariant();

                        $uoms = ['length', 'width', 'height', 'weight', 'volume'];

                        $attributesToCopy = [
                            'sku',
                            'gtin',
                            'mpn',
                            'shippable',
                            'length', 'width', 'height', 'weight', 'volume',
                            'package_unit',
                            'weight_unit',
                            'volume_unit'
                        ];

                        // foreach ($uoms as $uom) {
                        //     $attributesToCopy[] = "{$uom}_value";
                        //     $attributesToCopy[] = "{$uom}_unit";
                        // }

                        $attributes = $baseVariant->only($attributesToCopy);

                        foreach ($attributes as $attribute => $value) {
                            if ($rules[$attribute]['unique'] ?? false) {
                                $attributes[$attribute] = $attributes[$attribute] . '-' . ($key + 1);
                            }
                        }

                        // $pricing = $baseVariant->prices->map(function ($price) {
                        //     return $price->only([
                        //         'customer_group_id',
                        //         'currency_id',
                        //         'price',
                        //         'compare_price',
                        //         'quantity',
                        //     ]);
                        // });

                        $variant->options = [];
                        $variant->product_id = $product->id;
                        $variant->unit_qty = 1;
                        $variant->type = 'physical';
                        $variant->shippable = 1;
                        $variant->fill($attributes);
                        $variant->save();
                        $variant->values()->attach($optionsToCreate);
                        // $variant->prices()->createMany($pricing->toArray());
                    }
                });
                // $permutate = Arr::permutate(
                //     $array
                // );
                // $this->info(json_encode($permutate, true));
            });
        });



        $products = new Product();
        $products->chunk(10, function ($list) {
            $list->each(function (Product $product) {
                $price = DB::table('old_prices')->where('product_id', $product->id)->first();
                if (!isset($price)) return;
                $variants = $product->variants()->get();
                foreach ($variants as $variant) {
                    $values = DB::table('product_option_value_variants')->where('variant_id', $variant->id)->get()->pluck('value_id');
                    $amount = DB::table('old_product_options')->where('type', 0)->where('product_id', $product->id)->whereIn('value_id', $values)->sum('price');

                    $variant->prices()->updateOrCreate([
                        'variant_id' => $variant->id,
                        // 'shop_id' => $product->shop_id
                    ], [
                        'price' => ($price->price + $amount) * 100,
                        'currency_code' => 'INR',
                        'compare_price' => ($price->regular_price + $amount) * 100,
                        'offer_price' => ($price->price + $amount) * 100,
                        'selling_price' => ($price->regular_price) * 100,
                        'start_at' => $price->starting_date,
                        'ends_at' => $price->ending_date,
                        'quantity' => 1,
                    ]);
                    $this->info($amount);
                }
            });
        });
    }
}
