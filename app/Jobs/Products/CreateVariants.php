<?php

namespace App\Jobs\Products;

use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Bus\Queueable;
use App\Models\ProductVariant;
use App\Models\ProductOptionValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Exceptions\InvalidProductValuesException;

class CreateVariants implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $tries = 1;

    /**
     * The product instance.
     *
     * @var \App\Models\Product
     */
    protected $product;

    /**
     * The option values to use to generate variants.
     */
    protected array $optionValues;

    protected bool $isAdditional = false;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $product, iterable $optionValues, $additional = false)
    {
        $this->product = $product;

        if ($optionValues instanceof Collection) {
            $optionValues = $optionValues->toArray();
        }

        $this->optionValues = $optionValues;
        $this->isAdditional = $additional;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Validator::make([
            'optionValues' => $this->optionValues,
        ], [
            'optionValues' => 'array',
            'optionValues.*' => 'numeric',
        ])->validate();

        $valueModels = ProductOptionValue::findMany($this->optionValues);

        if ($valueModels->count() != count($this->optionValues)) {
            throw new InvalidProductValuesException(
                'One or more option values do not exist in the database'
            );
        }

        $permutations = $this->getPermutations();

        $baseVariant = $this->product->variants->first();

        DB::transaction(function () use ($permutations, $baseVariant) {
            // Validation bits
            $rules = [];

            foreach ($permutations as $key => $optionsToCreate) {
                $variant = new ProductVariant();

                $uoms = ['length', 'width', 'height', 'weight', 'volume'];

                $attributesToCopy = [
                    'sku',
                    'gtin',
                    'mpn',
                    'ean',
                    'shippable',
                ];

                foreach ($uoms as $uom) {
                    $attributesToCopy[] = "{$uom}_value";
                    $attributesToCopy[] = "{$uom}_unit";
                }

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

                $variant->product_id = $baseVariant->product_id;
                $variant->tax_class_id = $baseVariant->tax_class_id;
                $variant->attributes = $baseVariant->attributes;
                $variant->fill($attributes);
                $variant->save();
                $variant->values()->attach($optionsToCreate);
                // $variant->prices()->createMany($pricing->toArray());
            }

            if ($baseVariant && !$this->isAdditional) {
                $baseVariant->values()->detach();
                $baseVariant->prices()->delete();
                $baseVariant->delete();
            }
        });
    }

    /**
     * Gets permutations array from the option values.
     *
     * @return array
     */
    protected function getPermutations()
    {
        return Arr::permutate(
            ProductOptionValue::findMany($this->optionValues)
                ->groupBy('product_option_id')
                ->mapWithKeys(function ($values) {
                    $optionId = $values->first()->product_option_id;

                    return [$optionId => $values->map(function ($value) {
                        return $value->id;
                    })];
                })->toArray()
        );
    }
}
