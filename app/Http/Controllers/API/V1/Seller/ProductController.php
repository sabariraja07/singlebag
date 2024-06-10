<?php

namespace App\Http\Controllers\API\V1\Seller;

use App\Models\Shop;
use App\Models\Price;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Currency;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\ProductVariant;
use App\Models\ProductOptionValue;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Cartalyst\Converter\Laravel\Facades\Converter;

class ProductController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $type = $request->type ?? 1;
        $auth_id = Auth::id();
        $shop = Shop::where('id', current_shop_id())->first();
        $products = Product::where('type', 'product');

        if (current_shop_type() == 'reseller') {
            $products = $products->whereHas('ResellerProduct', function ($q) use ($type) {
                $q->where('status', $type)
                    ->where('shop_id', current_shop_id());
            });
        } else {
            $products = $products->where('status', $type)->where('shop_id', current_shop_id());
        }

        if ($request->src) {
            $products = $products->where($request->search_type, 'LIKE', '%' . $request->src . '%');
        }

        if ($request->highlight) {
            $products = $products->orderByRaw('id = ? DESC', [$request->highlight]);
        }

        $products = $products->withCount('orders')->latest()->paginate(30)->toArray();

        return response()->json(array_merge($products, ['status' => 'success']));
    }

    public function store(Request $request)
    {
        $product_count = Product::where('shop_id', current_shop_id())->count();
        if (user_plan_limit('product_limit', $product_count)) {
            return $this->error(trans('Maximum posts limit exceeded'), 401);
        }

        $request->validate([
            'title' => 'required|max:100',
            'short_description' => 'required|max:150',
            'type' => 'required|in:physical,digital',
        ]);

        $product = new Product();
        $product->title = $request->title;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->type = $request->type;
        $product->tax = $request->tax ?? null;
        $product->featured = $request->featured ?? 0;
        $product->brand_id = $request->brand ?? null;
        $product->shop_id = current_shop_id();
        $product->user_id = auth()->id();
        $product->status = $request->status ?? 1;
        $product->save();

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'unit_qty' => 1,
            'type' => $request->type,
            'shippable' => $request->type == 'physical' ? 1 : 0,
            'length' => 0,
            'width' => 0,
            'height' => 0,
            'weight' => 0,
            'volume' => 0
        ]);

        $options = $request->options ?? [];

        if (count($options) > 0) {
            $permutations = Arr::permutate(
                ProductOptionValue::findMany($options)
                    ->groupBy('product_option_id')
                    ->mapWithKeys(function ($values) {
                        $optionId = $values->first()->product_option_id;

                        return [$optionId => $values->map(function ($value) {
                            return $value->id;
                        })];
                    })->toArray()
            );
        }

        if (isset($variant) && $request->has('selling_price')) {
            $price = new Price();
            $price->variant_id = $variant->id;
            $price->currency_code = $request->currency ?? Currency::getDefault();
            $price->compare_price = $request->compare_price ?? $request->selling_price;
            $price->offer_price = $request->offer_price ?? null;
            $price->selling_price = $request->selling_price;
            $price->start_at = $request->start_at ?? null;
            $price->ends_at = $request->ends_at ?? null;
            $price->save();
        }

        return $this->success($product, trans('Product Created'));
    }

    public function create_variants(Request $request)
    {
        $options = $request->options ?? [];

        if (count($options) > 0) {
            $permutations = Arr::permutate(
                ProductOptionValue::findMany($options)
                    ->groupBy('product_option_id')
                    ->mapWithKeys(function ($values) {
                        $optionId = $values->first()->product_option_id;

                        return [$optionId => $values->map(function ($value) {
                            return $value->id;
                        })];
                    })->toArray()
            );

            GenerateVariants::dispatch($this->product, $this->newValues, true);
        }
    }

    public function create_variant(Request $request)
    {
        $request->validate([
            'variant.sku' => "required|string|unique:product_variants,sku,{$request->variant_id}|max:255",
        ]);
    }

    public function fetch_variant(Request $request)
    {
        $variant = ProductVariant::find(2);
        // $value = Converter::from('length.m')->to('length.cm')->convert(200)->format();
        return $this->success($variant->volumes);
    }

    public function update_variant(Request $request, $id)
    {
        $variant = ProductVariant::find(2);
        $variant->length = 10;
        $variant->save();
        // $request->validate([
        //     'variant.sku' => "required|string|unique:product_variants,sku,{$request->variant_id}|max:255",
        // ]);
    }

    public function media(Request $request)
    {
        if ($request->has('file')) {
            request()->validate([
                'file' => 'required|image',
                'product_id' => 'required',
            ]);
        } else {
            request()->validate([
                'media.*' => 'required|image',
                'product_id' => 'required',
            ]);
        }

        $product = Product::findorFail($request->product_id);

        if ($request->hasfile('file')) {
            $size = $request->file('file')->getSize();
            if (user_plan_limit('storage', storageToMB($size - $product->getFileSize('image')))) {
                return $this->error(trans('Maximum storage limit exceeded'), 401);
            }

            $product->deleteFile('image');
            $product->uploadFile($request->file, $product->shop_id  . '/product/' . $product->id . '/image', 'image');
            $response = (array) $product->getFile('image');
            return $this->success($response, trans('Feature Image updated successfully'));
        }

        if ($request->hasfile('media')) {
            $size = 0;
            foreach ($request->file('media') as $image) {
                $size += $image->getSize();
            }
            if (user_plan_limit('storage', storageToMB($size))) {
                return $this->error(trans('Maximum storage limit exceeded'), 401);
            }

            foreach ($request->file('media') as $image) {
                $product->uploadFile($image, $product->shop_id  . '/product/' . $product->id . '/images', 'images');

                return $this->success($product->getFiles('images'));
            }

            return $this->success(null);
        }
    }

    public function show($id)
    {
        $product = Product::where('shop_id', current_shop_id())->findOrFail($id);
        return $this->success($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('shop_id', current_shop_id())->findOrFail($id);
        $product->title = $request->title;
        $product->url = $request->url;
        $product->btn_text = $request->btn_text;
        $product->description = $request->description;
        $product->position = $request->position;
        $product->status = $request->status ?? 1;
        $product->text_color = $request->text_color;
        $product->save();

        if ($request->hasFile('file')) {
            $size = $request->file('file')->getSize();
            if (user_plan_limit('storage', storageToMB($size - $product->getFileSize('image')))) {
                return $this->error(trans('Maximum storage limit exceeded'), 401);
            }

            $product->deleteFile('image');
            $product->uploadFile($request->file, $product->shop_id  . '/banner/' . $product->id . '/image', 'image');
        }

        return $this->success(null, trans('Product Updated'));
    }

    public function associate(Request $request, $id)
    {
        $product = Product::where('shop_id', current_shop_id())->findorFail($id);
        $type = $request->type ?? null;
        $targets = Product::where('shop_id', current_shop_id())->whereIn('id', $request->targets)->get();
        DB::transaction(function () use ($product, $type, $targets) {
            $product->associations()->createMany(
                $targets->map(function ($model) use ($type) {
                    return [
                        'product_target_id' => $model->id,
                        'type' => $type,
                    ];
                })
            );
        });
        return $this->success(null, trans('Product association updated'));
    }

    public function price(Request $request, $id)
    {
        $request->validate([
            'compare_price' => 'required',
            'offer_price' => 'sometimes|required',
            'selling_price' => 'required',
            'start_at' => 'sometimes|required',
            'ends_at' => 'sometimes|required',
            'variant' => 'required',
            'currency' => 'required',
        ]);
        $product = Product::where('shop_id', current_shop_id())->findorFail($id);
        $price = Price::where('variant_id', $request->variant)->where('currency_code', $request->currency)->first();

        if (!isset($price))
            $price = new Price();

        $price->variant_id = $request->variant;
        $price->currency_code = $request->currency;
        $price->compare_price = $request->compare_price;
        $price->offer_price = $request->offer_price ?? null;
        $price->selling_price = $request->selling_price;
        $price->start_at = $request->start_at ?? null;
        $price->ends_at = $request->ends_at ?? null;
        $price->save();

        return $this->success($price, trans('Product association updated'));
    }

    public function stock(Request $request, $id)
    {
        $request->validate([
            'manage_stock' => 'required',
            'stock_qty' => 'required_if:manage_stock,1|numeric|min:0',
            'status' => 'required_if:manage_stock,1',
            'variant' => 'required',
        ]);

        $product = Product::findOrFail($id);
        $variant = ProductVariant::where('product_id', $product->id)->findOrFail($request->variant);
        $stock = Stock::with('variant')->find($id);
        // if (empty($stock)) {
        //     $msg['errors']['shop'] = 'Invalid input.';
        //     return response()->json($msg, 401);
        // }
        // $shop_id = current_shop_id();
        // if ($product->shop_id != $shop_id) {
        //     die();
        // }

        $stock = Stock::updateOrCreate([
            'variant_id' => $request->variant,
            'location_id' => $request->location ?? null,
        ], [
            'manage_stock' => $request->manage_stock,
            'status' => $request->status ?? 1,
            'quantity' => $request->stock_qty ?? 0,
            'alert_qty' => $request->alert_qty ?? 5,
        ]);

        return $this->success($stock, trans('Stock Update Successfully'));
    }

    public function dissociate(Request $request, $id)
    {
        $product = Product::where('shop_id', current_shop_id())->findorFail($id);
        $type = $request->type ?? null;
        $targets = Product::where('shop_id', current_shop_id())->whereIn('id', $request->targets)->get();

        DB::transaction(function () use ($product, $type, $targets) {
            $query = $product->associations()->whereIn(
                'product_target_id',
                $targets->pluck('id')
            );

            if ($type) {
                $query->whereType($type);
            }
            $query->delete();
        });

        return $this->success(null, trans('Product association updated'));
    }

    public function destroy(Request $request, $id)
    {
        $product = Product::where('shop_id', current_shop_id())->findorFail($id);
        $product->deleteFile('image');
        $product->delete();

        return $this->success(null, trans('Product Deleted'));
    }
}
