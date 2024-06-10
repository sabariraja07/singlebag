<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\FileUploadTrait;
use App\Traits\Models\HasChannels;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Product extends Model
{
    use FileUploadTrait;
    // use HasChannels;

    protected $table = 'products';

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            // $model->shop_id = current_shop_id();
            if (!empty($model->title)) {
                $slug = Str::slug($model->title, '-');
                $i = 0;
                $temp = $slug;
                while (!$model->whereSlug($temp)->whereShopId($model->shop_id)->whereNotIn('id', [$model->id ?? 0])->count() == 0) {
                    $temp = $slug .  ($i == 0 ? '' : '-' . $i);
                    $i++;
                }
                $model->attributes['slug'] = $temp;
            }
        });
    }

    protected $casts = [
        'seo' => 'json'
        // 'meta' => 'json'
    ];

    protected $appends = ['image'];

    public function getImageAttribute()
    {
        $attachment = $this->getFile('image');
        return $attachment->url ?? asset('/assets/img/empty/450x600.jpg');
    }

    public function Categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function Brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function Shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class)->select('name', 'id');
    }

    public function Files()
    {
        return $this->hasMany(File::class, 'product_id');
    }

    public function Orders()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function Reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function Price()
    {
        return $this->hasOne(Price::class);
    }

    public function Stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function Variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function associations()
    {
        return $this->hasMany(ProductAssociation::class, 'product_id');
    }

    public function inverseAssociations()
    {
        return $this->hasMany(ProductAssociation::class, 'product_target_id');
    }

    public function prices()
    {
        return $this->hasManyThrough(
            Price::class,
            ProductVariant::class,
            'product_id',
            'variant_id'
        );
    }

    public function ResellerProducts()
    {
        return $this->hasMany(ResellerProduct::class, 'product_id');
    }

    public function ResellerProduct()
    {
        return $this->hasOne(ResellerProduct::class, 'product_id');
    }

    public function ResellerOption()
    {
        return $this->hasOne(ResellerOption::class, 'product_id');
    }

    public function Attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes', 'product_id', 'attribute_id');
    }

    public function Order()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function scopeByShop($query)
    {
        if (current_shop_type() == 'reseller') {
            return $query->whereHas('ResellerProduct', function ($q) {
                $q->where('status', 1)
                    ->where('shop_id', current_shop_id());
            });
        } else {
            return $query->where('shop_id', current_shop_id());
        }
    }

    public function scopeIsActive($query, $status = 1)
    {
        return $query->where('status', $status);
    }

    public function scopeShopFilter($query, Request $request)
    {
        $order = in_array($request->order ?? "", ['desc', 'asc']) ?  $request->order : $order = 'desc';

        if ($request->order == 'best_sell') {
            $featured = 2;
        } elseif ($request->order == 'trending') {
            $featured = 1;
        } else {
            $featured = 0;
        }

        if (current_shop_type() == 'reseller') {
            $query->whereHas('ResellerProduct', function ($q) use ($request, $featured) {
                $q->where('status', 1)
                    ->where('shop_id', current_shop_id());

                if ($featured > 0)
                    $q->where('featured', $featured);

                $category_id = $request->category_id ?? null;
                if (isset($category_id)) {
                    $q->where('category_id', $category_id);
                }

                if (isset($request->brand)) {
                    $brands = explode(",", $request->brand);
                    $brand_ids = Brand::whereIn('slug', $brands)->where('shop_id', current_shop_id())->where('status', 1)->get()->pluck('id');
                    if (count($brand_ids) > 0) {
                        $q->whereIn('brand_id', $brand_ids);
                    }
                }

                $brands = $request->brands ?? [];
                if (count($brands) > 0) {
                    $q->whereIn('brand_id', $brands);
                }

                return $q;
            });
        } else {
            $query->where('shop_id', current_shop_id());

            if ($featured > 0)
                $query->where('featured', $featured);

            if (isset($request->type)) {
                $query->todayOffer();
            }

            if (isset($request->brand)) {
                $brands = explode(",", $request->brand);
                $brand_ids = Brand::whereIn('slug', $brands)->where('shop_id', current_shop_id())->where('status', 1)->get()->pluck('id');
                if (count($brand_ids) > 0) {
                    $query->whereIn('brand_id', $brand_ids);
                }
            }

            $attrs = $request->attrs ?? [];
            if (count($attrs) > 0) {
                $query->whereHas('attributes', function ($q) use ($attrs) {
                    return $q->whereIn('attributes.id', $attrs);
                });
            }

            if (!empty($request->min_price)) {
                $min_price = $request->min_price;
                $query->whereHas('price', function ($q) use ($min_price) {
                    return $q->where('price', '>=', $min_price);
                });
            }

            if (!empty($request->max_price)) {
                $max_price = $request->max_price;
                $query->whereHas('price', function ($q) use ($max_price) {
                    return $q->where('price', '<=', $max_price);
                });
            }

            $this->brands = $request->brands ?? [];
            if (count($this->brands) > 0) {
                $query->whereIn('brand_id', $this->brands);
            }

            if ($featured != 0) {
                $query->orderBy('featured', 'desc');
            } else {
                $query->orderBy('created_at', $order);
            }

            $category_id = $request->category_id ?? null;
            if (isset($category_id)) {
                $query->whereHas('categories', function ($q) use ($category_id) {
                    return $q->where('id', $category_id);
                });
            }
        }

        if (!empty($request->term)) {
            $query->where('title', 'LIKE', '%' . $request->term . '%');
        }

        // if (count($this->cats) > 0) {
        //     $query->whereHas('categories', function ($q) {
        //         return $q->whereIn('categories.id', $this->cats);
        //     });
        // }

        // $category_id = $request->category_id ?? null;
        // if (isset($category_id)) {
        //   $query->whereHas('categories', function ($q) use ($category_id) {
        //     return $q->where('category_id', $category_id);
        //   });
        // }

        return $query->isActive()
            ->where('type', 'product')
            // ->with('content', 'attributes', 'categories', 'price', 'options', 'stock', 'affiliate');
            // ->withCount('options')
            ->with('categories:id,name,slug', 'price', 'stock')
            ->select('id', 'title', 'slug', 'short_description', 'status', 'featured', 'affiliate', 'avg_rating')
            ->withCount([
                'options as required_options_count' => function ($query) {
                    $query->where('is_required', 1)->where('type', 1);
                },
            ]);
    }

    public function scopeFeaturedFilter($query, $featured = 0)
    {

        if (current_shop_type() == 'reseller') {
            $query->whereHas('ResellerProduct', function ($q) use ($featured) {
                $q->where('status', 1)
                    ->where('shop_id', current_shop_id());

                if ($featured > 0)
                    $q->where('featured', $featured);
                return $q;
            });
        } else {
            $query->where('shop_id', current_shop_id());
            if ($featured > 0) {
                $query->where('featured', $featured)
                    ->orderBy('featured', 'desc');
            }
        }

        return $query->isActive()
            ->where('type', 'product')
            ->with('categories:id,name,slug', 'price', 'stock')
            ->select('id', 'title', 'slug', 'short_description', 'status', 'featured', 'affiliate', 'avg_rating')
            ->withCount([
                'options as required_options_count' => function ($query) {
                    $query->where('is_required', 1)->where('type', 1);
                },
            ]);
    }

    public function scopeShopByDetail($query)
    {
        return $query->byShop()
            ->isActive()
            ->where('type', 'product')
            ->withCount('reviews')
            ->with('categories', 'brand', 'price', 'options', 'attributes', 'stock');
    }

    public function scopeShopByList($query)
    {
        return $query->byShop()
            ->isActive()
            ->where('type', 'product')
            // ->with('content', 'attributes', 'categories', 'price', 'options', 'stock', 'affiliate');
            // ->withCount('options')
            ->with('categories:id,name,slug', 'price', 'stock')
            ->select('id', 'title', 'slug', 'short_description', 'status', 'featured', 'affiliate', 'avg_rating')
            ->withCount([
                'options as required_options_count' => function ($query) {
                    $query->where('is_required', 1)->where('type', 1);
                },
            ]);
    }

    public function scopeTodayOffer($query)
    {
        return $query->whereHas('price', function ($q) {
            return $q->where('starting_date', '<=', date('Y-m-d'))->where(function ($q) {
                $q->where('ending_date', '>=', date('Y-m-d'))->orWhereNull('ending_date');
            });
        });
    }

    public function isStockAvailable($qty = 1)
    {
        $stock =  $this->stock()->first();
        if ($stock->stock_manage == 0) {
            return true;
        }
        if ($stock->stock_manage == 1 && $stock->stock_qty >= $qty && $stock->stock_status == 1) {
            return true;
        }
        return false;
    }

    public function inStock()
    {
        if (!isset($this->stock)) {
            return true;
        }

        if ($this->stock->stock_manage == 0) {
            return true;
        }

        if ($this->stock->stock_status == 0 && $this->stock->stock_manage == 1) {
            return false;
        }

        if ($this->stock->stock_manage == 1 && $this->stock->stock_qty  < 1) {
            return false;
        }

        return true;
    }

    public static function supplier_price_calculation($product_id, $qty = 1, $options = [])
    {
        $product = Product::with('price')->where('id', $product_id)->first();

        $supplier_price = [
            'price' => 0,
            'unit_price' => 0,
            'subtotal' => 0,
            'itemTax' => 0,
            'totalTax' => 0,
            'discount' => 0,
            'total' => 0,
        ];
        if (!isset($product)) {
            return $supplier_price;
        }
        $supplier_price['price'] = $product->price->price ?? 0;
        $supplier_price['unit_price'] = $supplier_price['price'];
        if ($options != null) {
            foreach ($options ?? [] as $option) {
                if ($option['amount_type'] == 1) {
                    $supplier_price['unit_price'] += $option['amount'];
                } else {
                    $supplier_price['unit_price'] += ($supplier_price['price'] * ($option['amount'] * 0.01));
                }
            }
        }

        $supplier_price['subtotal'] = $supplier_price['unit_price'] * $qty;
        $supplier_price['itemTax'] = $supplier_price['unit_price'] * ($product->tax * 0.01);
        $supplier_price['totalTax'] = $supplier_price['subtotal'] * ($product->tax * 0.01);
        $supplier_price['total'] = $supplier_price['subtotal'] + $supplier_price['totalTax'] - $supplier_price['discount'];

        return $supplier_price;
    }

    public function createVariants()
    {

        Validator::make([
            'optionValues' => $this->optionValues,
        ], [
            'optionValues' => 'array',
            'optionValues.*' => 'numeric',
        ])->validate();

        $valueModels = ProductOptionValue::findMany($this->optionValues);

        if ($valueModels->count() != count($this->optionValues)) {
            //'One or more option values do not exist in the database'
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
