<?php

namespace App\Http\Controllers\Seller;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Stock;
use App\Models\Seo;
use App\Models\Price;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\ShopOption;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\OldProductOption;
use App\Imports\ProductImport;
use App\Models\ProductVariant;
use App\Models\ResellerOption;
use App\Models\ProductCategory;
use App\Models\ResellerProduct;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->type ?? 1;
        $auth_id = Auth::id();
        $shop = Shop::where('id', current_shop_id())->first();
        $posts = Product::where('type', 'product');

        if (current_shop_type() == 'reseller') {
            $posts = $posts->whereHas('ResellerProduct', function ($q) use ($type) {
                $q->where('status', $type)
                    ->where('shop_id', current_shop_id());
            });
        } else {
            $posts = $posts->where('status', $type)->where('shop_id', current_shop_id());
        }

        if ($request->src) {
            $posts = $posts->where($request->search_type, 'LIKE', '%' . $request->src . '%');
        }

        if ($request->highlight) {
            $posts = $posts->orderByRaw('id = ? DESC', [$request->highlight]);
        }

        $posts = $posts->withCount('orders')->latest()->paginate(30);

        $src = $request->src ?? '';

        if (current_shop_type() == 'reseller') {
            $actives = Product::where('type', 'product')
                ->whereHas('resellerproduct', function ($q) {
                    $q->where('status', 1)
                        ->where('shop_id', current_shop_id());
                })->count();
            $drafts = Product::where('type', 'product')
                ->whereHas('ResellerProduct', function ($q) {
                    $q->where('status', 2)
                        ->where('shop_id', current_shop_id());
                })->count();
            $incomplete = Product::where('type', 'product')
                ->whereHas('ResellerProduct', function ($q) {
                    $q->where('status', 3)
                        ->where('shop_id', current_shop_id());
                })->count();
            $trash = Product::where('type', 'product')
                ->whereHas('ResellerProduct', function ($q) {
                    $q->where('status', 0)
                        ->where('shop_id', current_shop_id());
                })->count();
        } else {
            $actives = Product::where('type', 'product')->where('status', 1)->where('shop_id', $shop->id)->count();
            $drafts = Product::where('type', 'product')->where('status', 2)->where('shop_id', $shop->id)->count();
            $incomplete = Product::where('type', 'product')->where('status', 3)->where('shop_id', $shop->id)->count();
            $trash = Product::where('type', 'product')->where('status', 0)->where('shop_id', $shop->id)->count();
        }

        return view('seller.products.index', compact('posts', 'src', 'type', 'actives', 'drafts', 'incomplete', 'trash', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shop = Shop::where('id', current_shop_id())->first();
        if (current_shop_type() == 'supplier') {
            $bank_details = ShopOption::where('shop_id', current_shop_id())->where('key', 'bank_details')->first();
            if (!$bank_details) {
                return back()->with('error', 'Please update your bank details to add product.');
            }
        }
        $posts_count = Product::where('shop_id', $shop->id)->where('status', '!=', 4)->count();
        if (user_plan_limit('product_limit', $posts_count)) {
            Session::flash('error', trans('Maximum product limit exceeded'));
            return back();
        }


        return view('seller.products.create');
    }

    public function create_new()
    {
        return view('seller.products.create_new');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'price' => 'required|max:50',
        ]);
        // [
        //     'title.max' => 'Product title field must not exceed 20 characters',
        // ]);
        if ($request->affiliate) {
            $request->validate([
                'purchase_link' => 'required|max:100'
            ]);
        }
        $slug = Str::slug($request->title);

        // if ($request->special_price_start != null && $request->special_price_start <=  Carbon::now()->format('Y-m-d') && $request->special_price != null) {
        //     if (!$request->has('special_price_end') || Carbon::parse($request->special_price_end)->endOfDay() >  Carbon::now()->format('Y-m-d')) {
        //         // if ($request->price_type == 1) {
        //         //     $price = $request->price - $request->special_price;
        //         // } else {
        //         //     $percent = $request->price * $request->special_price * 0.01;
        //         //     $price = $request->price - $percent;
        //         //     $price = str_replace(',', '', number_format($price, 2));
        //         $price = $request->special_price;
        //         $diff =  $request->price - $request->special_price;
        //         $price = str_replace(',', '', number_format($price, 2));
        //         // }
        //     } else {
        //         $price = $request->price;
        //     }
        // } else {
        //     $price = $request->price;
        // }

        DB::beginTransaction();
        try {

            $shop = Shop::where('id', current_shop_id())->first();
            $product = new Product;
            $product->title = $request->title;
            $product->slug = $slug;
            $product->status = 3;
            $product->type = 'product';
            $product->affiliate = $request->purchase_link ?? null;
            $product->user_id = Auth::id();
            $product->shop_id = $shop->id;
            $product->save();



            // $product_price = new Price;
            // $product_price->product_id = $product->id;
            // $product_price->price = $price;
            // $product_price->regular_price = $request->price;
            // $product_price->special_price = $diff ?? null;
            // $product_price->price_type = 1;
            // $product_price->starting_date = $request->special_price_start ?? null;
            // $product_price->ending_date = $request->special_price_end ?? null;
            // $product_price->save();

            $stock = new Stock;
            $stock->product_id = $product->id;
            $stock->stock_manage = $request->stock_manage ?? 0;
            $stock->stock_status = 1;
            $stock->stock_qty = $request->stock_qty ?? 999;
            $stock->sku = $request->sku ?? null;
            $stock->save();

            Session::flash("flash_notification", [
                "level"     => "success",
                "message"   => "Product Created Successfully"
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return back();
        }
        return redirect()->route('seller.products.edit', $product->id);
    }

    public function store_new(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'price' => 'required|max:50',
        ]);
        // [
        //     'title.max' => 'Product title field must not exceed 20 characters',
        // ]);
        if ($request->affiliate) {
            $request->validate([
                'purchase_link' => 'required|max:100'
            ]);
        }
        $slug = Str::slug($request->title);
        if ($request->special_price_start != null && $request->special_price_start <=  Carbon::now()->format('Y-m-d') && $request->special_price != null) {
            if (!$request->has('special_price_end') || Carbon::parse($request->special_price_end)->endOfDay() >  Carbon::now()->format('Y-m-d')) {
                // if ($request->price_type == 1) {
                //     $price = $request->price - $request->special_price;
                // } else {
                //     $percent = $request->price * $request->special_price * 0.01;
                //     $price = $request->price - $percent;
                //     $price = str_replace(',', '', number_format($price, 2));
                $price = $request->special_price;
                $diff =  $request->price - $request->special_price;
                $price = str_replace(',', '', number_format($price, 2));
                // }
            } else {
                $price = $request->price;
            }
        } else {
            $price = $request->price;
        }

        DB::beginTransaction();
        try {
            $shop = Shop::where('id', current_shop_id())->first();
            $product = new Product;
            $product->title = $request->title;
            $product->slug = $slug;
            $product->status = 3;
            $product->type = 'product';
            $product->affiliate = $request->purchase_link ?? null;
            $product->user_id = Auth::id();
            $product->shop_id = $shop->id;
            $product->short_description = $request->short_description ?? null;
            $product->description = $request->description ?? null;
            $product->brand_id = $request->brand ?? null;
            $product->pos = $request->pos ?? 0;
            $product->save();

            $variant = new ProductVariant;
            $variant->product_id = $product->id;
            $variant->unit_qty = 999;
            $variant->sku = $request->sku ?? null;
            $variant->save();

            $product_price = new Price;
            $product_price->variant_id = $variant->id;
            $product_price->price = $price;
            $product_price->compare_price = $request->price;
            $product_price->offer_price = $diff ?? null;
            $product_price->start_at = $request->special_price_start ?? null;
            $product_price->ends_at = $request->special_price_end ?? null;
            $product_price->save();

            $stock = new Stock;
            $stock->variant_id = $variant->id;
            $stock->manage_stock = $request->stock_manage ?? 0;
            $stock->status = 1;
            $stock->quantity = $request->stock_qty ?? 999;
            $stock->save();

            $catsArr = [];
            foreach ($request->cats ?? [] as $key => $value) {
                if (!empty($value)) {
                    $data['category_id'] = $value;
                    $data['product_id'] = $product->id;

                    array_push($catsArr, $data);
                }
            }

            ProductCategory::where('product_id', $product->id)->delete();
            if (count($catsArr) > 0) {
                ProductCategory::insert($catsArr);
            }

            Session::flash("flash_notification", [
                "level"     => "success",
                "message"   => "Product Created Successfully"
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back();
        }
        return redirect()->route('seller.products.index');
    }


    public function store_group(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);

        $product = Product::where('shop_id', current_shop_id())->findorFail($id);

        $group = new OldProductOption();
        $group->product_id = $id;
        $group->shop_id = current_shop_id();
        $group->name = $request->name;
        $group->type = 1;
        $group->is_required = $request->is_required ?? 0;
        $group->select_type = $request->select_type ?? 0;
        $group->save();

        if (current_shop_type() == 'supplier') {
            $product = Product::where('supplier_product_id', $id)->get();
            foreach ($product as $products) {
                $groups = new OldProductOption();
                $groups->product_id = $products->id;
                $groups->supplier_product_id = $id;
                $groups->supplier_option_id = $group->id;
                $groups->shop_id = $products->shop_id;
                $groups->name = $request->name;
                $groups->type = 1;
                $groups->is_required = $request->is_required ?? 0;
                $groups->select_type = $request->select_type ?? 0;
                $groups->save();
            }
        }

        return response()->json('Option Created Successfully....!!!');
    }

    public function stock_update(Request $request, $id)
    {
        $product = Product::where('shop_id', current_shop_id())->findorFail($id);

        $stock = Stock::where('product_id', $id)->first();

        if (!isset($stock)) {
            $stock = new Stock();
            $stock->product_id = $id;
        }
        $stock->stock_manage = $request->stock_manage ?? 0;
        if ($request->get('stock_manage', 0) == 1) {
            if (!$request->has('stock_qty') || !$request->has('stock_status')) {
                $msg['errors']['shop'] = 'Invalid input.';
                return response()->json($msg, 401);
            } elseif ($request->stock_qty == 0 && $request->stock_status == 1) {
                $msg['errors']['shop'] = 'In stock quantity must not be 0';
                return response()->json($msg, 401);
            } elseif ($request->stock_qty > 0 && $request->stock_status == 0) {
                $msg['errors']['shop'] = 'Out of stock quantity allows only zero';
                return response()->json($msg, 401);
            }
            $stock->stock_status = $request->stock_status;
            $stock->stock_qty = $request->stock_qty;
        } else {
            $stock->stock_status =  1;
            $stock->stock_qty =  999;
        }
        if ($request->has('sku')) {
            $stock->sku = $request->sku;
        }
        $stock->save();

        return response()->json('Stock Updated Successfully....!!!');
        // Session::flash('success', trans('Stock Updated Successfully !!'));
        // return back();
    }

    public function add_row(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);

        $product = OldProductOption::where('shop_id', current_shop_id())->where('type', 1)->findorFail($request->row_id);

        $group = new OldProductOption();
        $group->product_id = $product->product_id;
        $group->shop_id = current_shop_id();
        $group->name = $request->name;
        $group->amount = $request->price ?? 0.00;
        $group->amount_type = $request->amount_type;
        $group->type = 0;
        $group->p_id = $request->row_id;
        $group->save();

        if (current_shop_type() == 'supplier') {
            $products = OldProductOption::where('supplier_option_id', $request->row_id)->get();
            foreach ($products as $product_clone) {
                $groups = new OldProductOption();
                $groups->product_id = $product_clone->product_id;
                $groups->shop_id = $product_clone->shop_id;
                $groups->name = $request->name;
                $groups->amount = $request->price ?? 0.00;
                $groups->amount_type = $request->amount_type;
                $groups->supplier_option_id = $group->id;
                $groups->type = 0;
                $groups->p_id = $product_clone->id;
                $groups->save();
            }
        }

        return response()->json(trans('Row Created Successfully....!!!'));
    }

    public function Show(Request $request, $id)
    {
        # code...
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $type = "edit")
    {
        $user = Auth::user();

        $info = new Product();

        if (current_shop_type() == 'reseller') {
            $info = $info->whereHas('ResellerProduct', function ($q) {
                $q->where('shop_id', current_shop_id());
            });
        } else {
            $info = $info->where('shop_id', current_shop_id());
        }

        if ($type == 'edit') {
            if (current_shop_type() == 'reseller') {
                $info = $info->with(['ResellerProduct' => function ($q) {
                    $q->where('shop_id', current_shop_id());
                }])->findorFail($id);

                return view('seller.products.edit.item', compact('info'));
            }

            $info = $info->findorFail($id);
            $cats = [];

            foreach ($info->categories as $key => $value) {
                array_push($cats, $value->id);
            }

            return view('seller.products.edit.item', compact('info', 'cats'));
        }
        if ($type == 'variant') {
            $info = $info->findorFail($id);
            $options = DB::table('product_attributes')->where('product_id', $info->id)->get()->pluck('attribute_id')->toArray();
            $variations = Attribute::with(['options' => function ($query) use ($options) {
                $query->whereIn('id', $options);
            }])
                ->whereHas('options', function ($query) use ($options) {
                    $query->whereIn('id', $options);
                })
                ->where('shop_id', $info->shop_id)
                ->get();


            $attributes = Attribute::where('shop_id', current_shop_id())->whereHas('options')->with('options')->where('status', 1)->get();
            return view('seller.products.edit.variants', compact('info', 'attributes', 'variations', 'options'));
        }
        if ($type == 'price') {
            $currency_info = ShopOption::where('shop_id', current_shop_id())->where('key', 'currency')->first();
            $info = $info->with('price')->findorFail($id);
            $currency = json_decode($currency_info->value ?? '');
            if (current_shop_type() == 'reseller') {
                $info = $info->load(['ResellerProduct' => function ($q) {
                    $q->where('shop_id', current_shop_id());
                }]);
            }
            return view('seller.products.edit.price', compact('info', 'currency'));
        }

        if ($type == 'image') {
            $info = $info->findorFail($id);
            $images = $info->getFiles('images');
            $image = $info->getFile('image');
            return view('seller.products.edit.images', compact('info', 'images', 'image'));
        }

        if ($type == 'files') {
            $info = $info->with('attributes', 'files')->findorFail($id);

            return view('seller.products.edit.files', compact('info'));
        }
        if ($type == 'option') {
            if (current_shop_type() == 'reseller') {
                $info = $info->with('options.variants.ResellerOption')->findorFail($id);
            } else {
                $info = $info->with('options')->findorFail($id);
            }
            return view('seller.products.edit.option', compact('info'));
        }

        if ($type == 'seo') {
            $info = $info->findorFail($id);
            if (current_shop_type() == 'reseller') {
                $info = $info->load(['ResellerProduct' => function ($q) {
                    $q->where('shop_id', current_shop_id());
                }]);

                $seo = $info->ResellerProduct->seo ?? null;
            } else {
                $seo = $info->seo;
            }
            return view('seller.products.edit.seo', compact('info', 'seo'));
        }

        if ($type == 'inventory') {
            $info = $info->with('stock')->findorFail($id);
            return view('seller.products.edit.stock', compact('info'));
        }

        if ($type == 'express-checkout') {
            $user_id = Auth::id();
            $info = $info->with('attributes', 'options')->findorFail($id);
            $variations = collect($info->attributes)->groupBy(function ($q) {
                return $q->attribute->name;
            });
            //return $request=Request()->all();
            return view('seller.products.edit.express', compact('info', 'variations'));
        }

        abort(404);
    }

    public function edit_new($id, $type = "edit")
    {
        $user = Auth::user();

        $info = new Product();

        if (current_shop_type() == 'reseller') {
            $info = $info->whereHas('ResellerProduct', function ($q) {
                $q->where('shop_id', current_shop_id());
            });
        } else {
            $info = $info->where('shop_id', current_shop_id());
        }

        $info = $info->findorFail($id);
        $cats = [];

        foreach ($info->categories as $key => $value) {
            array_push($cats, $value->id);
        }

        // $currency_info = ShopOption::where('shop_id', current_shop_id())->where('key', 'currency')->first();
        // $info = $info->with('price')->findorFail($id);
        // $currency = json_decode($currency_info->value ?? '');
        // if (current_shop_type() == 'reseller') {
        //     $info = $info->load(['ResellerProduct' => function ($q) {
        //         $q->where('shop_id', current_shop_id());
        //     }]);
        // }

        if (current_shop_type() == 'reseller') {
            $info = $info->load(['ResellerProduct' => function ($q) {
                $q->where('shop_id', current_shop_id());
            }]);
        }

        $info = $info->with('variants.stock')->findorFail($id);

        $info = $info->findorFail($id);
        $images = $info->getFiles('images');
        $image = $info->getFile('image');

        // return $info;

        $seo = Seo::where('page_type', 'product')->where('page_id', $info->id)->first();

        return view('seller.products.edit_new', compact('info', 'seo', 'cats', 'images', 'image'));

        abort(404);
    }

    public function variation(Request $request, $id)
    {

        if (!empty($request->child)) {
            $data = [];
            foreach ($request->child ?? [] as $key => $value) {

                foreach ($value as $r => $child) {
                    // $dat['category_id'] = $key;
                    $dat['attribute_id'] = $child;
                    $dat['product_id'] = $id;
                    array_push($data, $dat);
                }
            }
            Product::where('shop_id', current_shop_id())->findorFail($id);
            ProductAttribute::where('product_id', $id)->delete();
            if (count($data) > 0) {
                ProductAttribute::insert($data);
            }

            return response()->json('Attributes Updated');
        } else {
            $delete = ProductAttribute::where('product_id', $id)->delete();
            if ($delete) {
                return response()->json('Attributes Updated');
            } else {
                $msg['errors']['email_comment'] = 'Please Select Attributes & Values';
                return response()->json($msg, 401);
            }
        }
    }

    public function option_delete(Request $request)
    {

        OldProductOption::where('p_id', $request->id)->where('shop_id', current_shop_id())->delete();
        OldProductOption::where('shop_id', current_shop_id())->where('id', $request->id)->delete();

        if (current_shop_type() == 'supplier') {
            OldProductOption::where('supplier_option_id', $request->id)->delete();
        }


        return response()->json(trans('Option Deleted Successfully....!!'));
    }
    public function row_update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);

        $option = OldProductOption::where('shop_id', current_shop_id())->findorFail($request->id);
        $option->name = $request->name;
        $option->is_required = $request->is_required ?? 0;
        $option->select_type = $request->select_type ?? 0;
        $option->save();

        if (current_shop_type() == 'supplier') {
            $options = OldProductOption::where('id', $request->id)->first();
            $options->name = $request->name;
            $options->is_required = $request->is_required ?? 0;
            $options->select_type = $request->select_type ?? 0;
            $options->save();
        }

        return response()->json(trans('Option Updated Successfully....!!'));
    }

    public function option_update(Request $request, $id)
    {
        if (current_shop_type() == 'reseller') {
            foreach ($request->options as $key => $option) {
                foreach ($option as $row) {
                    foreach ($row as $k => $item) {
                        $data = OldProductOption::find($k);
                        ResellerOption::updateOrCreate(
                            [
                                'product_option_id' => $k,
                                'shop_id' => current_shop_id(),
                            ],
                            [
                                'name' => $data->name,
                                'price' => $item['price'],
                                'amount' => $item['price'],
                                'amount_type' => $item['price_type'] ?? $data->amount_type,
                                'status' => 1
                            ]
                        );
                    }
                }
            }
        } else {
            foreach ($request->options as $key => $option) {
                foreach ($option as $row) {
                    foreach ($row as $k => $item) {
                        $data['name'] = $item['label'];
                        $data['amount'] = $item['price'];
                        $data['amount_type'] = $item['price_type'];
                        OldProductOption::where('shop_id', current_shop_id())->where('type', 0)->where('p_id', $key)->where('id', $k)->update($data);
                    }
                }
            }
        }

        return response()->json([trans('Option Updated....!!!')]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (current_shop_type() != 'reseller') {
            $request->validate([
                // 'title' => 'required|max:20',
                'title' => 'required',
            ]);
            // [
            //     'title.max' => 'Product name field must not exceed 20 characters',
            // ]);
        }
        if ($request->affiliate) {
            $request->validate([

                'purchase_link' => 'required|max:100'
            ]);
        }

        DB::beginTransaction();
        try {
            if (current_shop_type() == 'reseller') {
                $info = Product::findorFail($id);
                $product = ResellerProduct::where('shop_id', current_shop_id())->where('product_id', $info->id)->first();
                // if(!isset($product))
                // {
                //     $product = new ResellerProduct();
                //     $product->product_id = $info->id;
                //     $product->shop_id = current_shop_id();
                //     $product->title = $request->title ?? $info->title;
                //     $product->slug = $request->slug ?? $info->slug;
                //     $product->amount = $request->amount ?? 0.00;
                //     $product->amount_type = $request->amount_type ?? 1;
                // }
                $product->slug = $info->slug;
                $product->featured = $request->featured ?? 1;
                $product->status = $request->status ?? 2;
                $product->brand_id = $request->brand ?? null;
                $product->category_id = $request->category ?? null;
                $product->save();

                //SEO 

                $seo = Seo::where('page_type', 'product')->where('page_id', $info->id)->first();

                if (empty($seo)) {
                    $seo = new Seo;
                    $seo->page_type = 'product';
                    $seo->page_id = $info->id;
                }
                $seo->title = $request->meta_title ?? '';
                $seo->description = $request->meta_description ?? '';
                $seo->keywords = $request->meta_keyword ?? '';

                $seo->save();

                DB::commit();
                return response()->json([trans('Item Updated')]);
            }

            $info = Product::where('shop_id', current_shop_id())->findorFail($id);
            $info->title = $request->title;
            $info->slug = $info->slug;
            $info->featured = $request->featured;
            $info->status = $request->status ?? 2;
            $info->pos = $request->pos ?? 0;
            $info->short_description = $request->short_description ?? null;
            $info->description = $request->description ?? null;
            $info->affiliate = $request->affiliate ?? null;
            $info->brand_id = $request->brand ?? null;
            $info->tax = $request->tax ?? null;
            $info->tax_type = $request->tax_type ?? 0;
            $info->video_provider = $request->video_provider ?? null;
            $info->video_url = $request->video_url ?? null;
            $info->save();

            $catsArr = [];
            foreach ($request->cats ?? [] as $key => $value) {
                if (!empty($value)) {
                    $data['category_id'] = $value;
                    $data['product_id'] = $id;

                    array_push($catsArr, $data);
                }
            }

            ProductCategory::where('product_id', $id)->delete();
            if (count($catsArr) > 0) {
                ProductCategory::insert($catsArr);
            }

            $seo = Seo::where('page_type', 'product')->where('page_id', $info->id)->first();

            if (empty($seo)) {
                $seo = new Seo;
                $seo->page_type = 'product';
                $seo->page_id = $info->id;
            }

            $seo->title = $request->meta_title ?? '';
            $seo->description = $request->meta_description ?? '';
            $seo->keywords = $request->meta_keyword ?? '';

            $seo->save();

            DB::commit();
            return response()->json([trans('Item Updated')]);
        } catch (\Exception $e) {
            DB::rollback();
            return back();
        }
    }

    public function price(Request $request, $id)
    {
        if (current_shop_type() == 'reseller') {
            $info = Product::findorFail($id);
            $product = ResellerProduct::where('shop_id', current_shop_id())->where('product_id', $info->id)->first();
            // if(!isset($product))
            // {
            //     $product = new ResellerProduct();
            //     $product->product_id = $info->id;
            //     $product->shop_id = current_shop_id();
            //     $product->title = $request->title ?? $info->title;
            //     $product->slug = $request->slug ?? $info->slug;
            // }
            $product->price = $request->price ?? 0;
            $product->amount = $request->amount ?? 0.00;
            $product->amount_type = $request->amount_type ?? 1;
            $product->save();

            Session::flash('success', trans('Price Updated....!!'));
            return back();
        }

        if ($request->special_price_start != null && $request->special_price_start <= Carbon::now()->format('Y-m-d') && $request->special_price != null) {
            if (!$request->has('special_price_end') || Carbon::parse($request->special_price_end)->endOfDay() >  Carbon::now()->format('Y-m-d')) {
                // if ($request->price_type == 1) {
                //     $price = $request->price - $request->special_price;
                // } else {
                //     $percent = $request->price * $request->special_price * 0.01;
                //     $price = $request->price - $percent;
                //     $price = str_replace(',', '', number_format($price, 2));
                // }
                $price = $request->special_price;
                $diff =  $request->price - $request->special_price;
                $price = str_replace(',', '', number_format($price, 2));
            } else {
                $price = $request->price;
            }
        } else {
            $price = $request->price;
        }


        $product_price = Price::with('product')->findorFail($id);
        if ($product_price->product->shop_id != current_shop_id()) {
            die();
        }
        $product_price->price = $price;
        $product_price->regular_price = $request->price;
        $product_price->special_price = $diff ?? null;
        $product_price->price_type = 1;
        $product_price->starting_date = $request->special_price_start ?? null;
        $product_price->ending_date = $request->special_price_end ?? null;
        $product_price->save();
        Session::flash('success', trans('Price Updated....!!'));
        return back();
    }

    public function seo(Request $request, $id)
    {
        $product = Product::findorFail($id);
        $product->seo()->updateOrCreate([
            'page_id' => $product->id
        ], [
            'title' => $request->title ?? null,
            'description' => $request->description ?? null,
            'keywords' => $request->keywords ?? null,
            'page' => 'product',
        ]);

        Session::flash('success', trans('SEO Updated...!!'));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate(
            [
                'method' => 'required'
            ],
            [
                'method.required' => 'Please Select Action'
            ]
        );
        $auth_id = Auth::id();
        if (current_shop_type() == 'reseller') {
            if ($request->method == 'delete') {
                if ($request->ids) {

                    foreach ($request->ids as $id) {

                        $product = ResellerProduct::where('shop_id', current_shop_id())->where('product_id', $id)->first();
                        $product->status = 5;
                        $product->save();
                    }
                }
            } else {
                if (!empty($request->ids)) {
                    foreach ($request->ids as $id) {

                        $product = ResellerProduct::where('shop_id', current_shop_id())->where('product_id', $id)->first();
                        if (!empty($product)) {

                            $product->status = $request->method;
                            $product->save();
                        }
                    }
                } else {
                    return response()->json(['errors' => ['No option Seleted']], 400);
                }
            }
        } else {
            if ($request->method == 'delete') {
                if ($request->ids) {

                    foreach ($request->ids as $id) {

                        $product = Product::where('shop_id', current_shop_id())->find($id);
                        $product->status = 5;
                        $product->pos = 0;
                        $product->save();
                    }
                }
                // foreach ($request->ids as $id) {

                //     $product = Product::with('medias')->where('shop_id', current_shop_id())->find($id);
                //     if (!empty($product)) {
                //         foreach ($product->medias as $key => $row) {
                //             mediaRemove($row->id);
                //         }

                //         Product::destroy($id);
                //     }
                // }

            } else {
                if (!empty($request->ids)) {
                    foreach ($request->ids as $id) {

                        $product = Product::where('shop_id', current_shop_id())->find($id);
                        if (!empty($product)) {

                            $product->status = $request->method;
                            $product->save();
                        }
                    }
                } else {
                    return response()->json(['errors' => ['No option Seleted']], 400);
                }
            }
        }
        return response()->json('Success');
    }

    public function import(Request $request)
    {
        if (current_shop_type() == 'supplier') {
            $bank_details = ShopOption::where('shop_id', current_shop_id())->where('key', 'bank_details')->first();
            if (!$bank_details) {
                $error['errors']['error'] = trans('Please update your bank details to add product.');
                return response()->json($error, 401);
            }
        }
        $posts_count = Product::where('shop_id', current_shop_id())->where('type', 'product')->where('status', '!=', 4)->count();
        if (user_plan_limit('product_limit', $posts_count)) {
            $error['errors']['error'] = trans('Maximum product limit exceeded');
            return response()->json($error, 401);
        }
        Excel::import(new ProductImport,  $request->file('file'));

        return response()->json([trans('Product Imported Successfully')]);
    }

    public function pos_index(Request $request)
    {
        $auth_id = Auth::id();
        $shop = Shop::where('id', current_shop_id())->first();
        if ($request->src) {
            $posts = Product::where('type', 'product')->where('pos', 1)->where('shop_id', $shop->id)->where($request->type, 'LIKE', '%' . $request->src . '%')->latest()->paginate(30);
        } else {
            $posts = Product::where('type', 'product')->where('pos', 1)->where('shop_id', $shop->id)->latest()->paginate(30);
        }

        $src = $request->src ?? '';

        return view('seller.products.pos', compact('posts', 'src', 'request'));
    }
}
