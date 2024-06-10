<?php

namespace App\Http\Controllers\Seller;

use App\Models\Shop;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\ShopOption;
use Illuminate\Http\Request;
use App\Models\ResellerProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ResellerProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type = 1)
    {
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
            $posts = $posts->where($request->type, 'LIKE', '%' . $request->src . '%');
        }

        $posts = $posts->latest()->paginate(30);

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request)
    {
        $posts = Product::select('title')->where('type', 'product');
        $posts = $posts->where('title',  'LIKE', $request->search . '%');
        $posts = $posts->where('status', 1)
        ->with('stock')
        ->whereHas('shop', function ($q) {
            $q->where('shop_type', 'supplier');
        })
        ->get();
        $response = array();
        foreach($posts as $p){
        $response[] = array("label"=>$p->title);
      }
        return response()->json($response);
    }

    public function search(Request $request)
    {
        $posts = Product::where('type', 'product');

        if ($request->supplier) {
            $posts = $posts->where('shop_id', $request->supplier);
        }

        if ($request->src) {
            $posts = $posts->where($request->type, 'LIKE', '%' . $request->src . '%');
        }

        $posts = $posts->where('status', 1)
            ->with('stock')
            ->whereHas('shop', function ($q) {
                $q->where('shop_type', 'supplier');
            })
            ->latest()
            ->paginate(20);

        $src = $request->src;
        $type = 1;


        return view('reseller.products.products', compact('posts', 'src', 'type', 'request'));
    }

    public function search_supplier(Request $request)
    {
        $auth_id = Auth::id();
        $shops = Shop::where('shop_type', 'supplier')->where('status', 'active');
        if ($request->src) {
            $shops = $shops->where('name', 'LIKE', '%' . $request->src . '%');
        }

        $shops = $shops->latest()
            ->paginate(30);

        $src = $request->src;

        return view('reseller.products.supplier', compact('shops', 'src', 'request'));
    }

    public function suppliers(Request $request)
    {
        $auth_id = Auth::id();
        $shops = Shop::where('shop_type', 'supplier')->where('status', 'active');
        if ($request->src) {
            $shops = $shops->where('name', 'LIKE', '%' . $request->src . '%');
        }

        $shops = $shops->latest()
            ->paginate(30);

        $src = $request->src;
        return view('reseller.products.supplier', compact('shops', 'src', 'request'));
    }

    public function add_product(Request $request, $id)
    {
        $shop_id = current_shop_id();
        $bank_details = ShopOption::where('shop_id', $shop_id)->where('key', 'bank_details')->first();
        if (!isset($bank_details)) {
            return back()->with('error', 'Please update your bank details to add product.');
        }

        $product = Product::find($id);
        $reseller_product = ResellerProduct::where('shop_id', $shop_id)->where('product_id', $id)->first();

        $posts = Product::where('type', '!=', 'page');
        $posts = $posts->whereHas('ResellerProduct', function ($q) {
            $q->where('status', '<>', 4)
                ->where('shop_id', current_shop_id());
        })
        ->where('status', 1);
        $posts_count = $posts->count();
        
        if (user_plan_limit('product_limit', $posts_count)) {
            Session::flash('error', trans('Maximum product limit exceeded'));
            return back();
        }
        if ($reseller_product) {
            Session::flash('error', trans('Product Already Added'));
            return back();
        } else {
            ResellerProduct::create([
                'title' => $product->title,
                'slug' => $product->slug,
                'amount' => 0,
                'amount_type' => 1,
                'status' => 3,
                'product_id' => $product->id,
                'brand_id' => null,
                'category_id' => null,
                'group_product_id' => null,
                'shop_id' => $shop_id,
            ]);
            Session::flash('success', trans('Product Added Successfully'));
            return back();
        }


        Session::flash('error', trans('Error'));
        return back();
    }



    public function update_price(Request $request, $id)
    {
        $supplier_product = Product::find($id);
        $product = ResellerProduct::where('product_id', $supplier_product->id)->where('shop_id', current_shop_id())->first();
        if(!isset($product))
        {
            $product->product_id = $supplier_product->id;
            $product->shop_id = current_shop_id();
        }
        $product->title = $request->title ?? $supplier_product->title;
        $product->slug = $request->slug ?? $supplier_product->slug;
        $product->amount = $request->amount ?? 0.00;
        $product->amount_type = $request->amount_type ?? 1;
        $product->brand_id = $request->brand_id ?? null;
        $product->category_id = $request->category_id ?? null;
        $product->save();
    }

    public function show($id)
    {
        $product = Product::where('type', 'product')
                    ->whereHas('shop', function ($q) {
                        $q->where('shop_type', 'supplier');
                    })
                    ->where('status', 1)
                    ->with('categories', 'brand', 'price', 'options', 'attributes', 'stock')
                    ->findorFail($id);

        $options = DB::table('product_attributes')->where('product_id', $product->id)->get()->pluck('attribute_id')->toArray();
        $variations = Attribute::with(['options' => function ($query) use ($options) {
            $query->whereIn('id', $options);
          }])
          ->whereHas('options', function ($query) use ($options) {
            $query->whereIn('id', $options);
          })
          ->where('shop_id', $product->shop_id)
          ->get();

        $image = $product->getFile('image');
        $images = $product->getFiles('images');
        $gallery = [];
        if (isset($image))
        $gallery[] = $product->getFile('image');
        if (isset($images))
        $gallery =  array_merge($gallery, $product->getFiles('images'));

        return (string) view('reseller.products.view', compact('product', 'variations', 'gallery'));
    }
}
