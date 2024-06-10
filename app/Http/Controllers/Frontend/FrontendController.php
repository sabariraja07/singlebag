<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Page;
use App\Models\Brand;
use App\Models\Slider;
use App\Models\Review;
use App\Models\Banner;
use App\Models\Gateway;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\ShopOption;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Artesaos\SEOTools\Facades\JsonLd;
use Illuminate\Support\Facades\Cache;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Session;
use Artesaos\SEOTools\Facades\OpenGraph;
use Gloudemans\Shoppingcart\Facades\Cart;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Illuminate\Support\Facades\DB;

class FrontendController extends Controller
{

  public $cats;
  public $attrs;

  public function index(Request $request)
  {
    if (Cache::has(domain_info('shop_id') . 'seo')) {
      $seo = json_decode(Cache::get(domain_info('shop_id') . 'seo'));
    } else {
      $data = ShopOption::where('shop_id', domain_info('shop_id'))->where('key', 'seo')->first();
      $seo = json_decode($data->value ?? '');
    }
    $shop = Shop::where('id', domain_info('shop_id'))->first();
    JsonLdMulti::setTitle($seo->title ?? $shop->name);
    JsonLdMulti::setDescription($seo->description ?? null);
    JsonLdMulti::addImage(get_shop_logo_url(domain_info('shop_id')));

    SEOMeta::setTitle($seo->title ?? $shop->name);
    SEOMeta::setDescription($seo->description ?? null);
    SEOMeta::addKeyword($seo->tags ?? null);

    SEOTools::setTitle($seo->title  ?? $shop->name);
    SEOTools::setDescription($seo->description ?? null);
    SEOTools::setCanonical($seo->canonical ?? url('/'));
    SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
    SEOTools::opengraph()->addProperty('image', get_shop_logo_url(domain_info('shop_id')));
    SEOTools::twitter()->setTitle($seo->title ?? $shop->name);
    SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
    SEOTools::jsonLd()->addImage(get_shop_logo_url(domain_info('shop_id')));

    if (!Session::has('locale')) {
      set_language();
    }
    return view(base_view() . '::index');
  }

  public function page()
  {
    $id = request()->route()->parameter('id');
    $info = Page::where('shop_id', domain_info('shop_id'))->findorFail($id);
    JsonLdMulti::setTitle($info->title ?? env('APP_NAME'));
    JsonLdMulti::setDescription($info->excerpt->value ?? null);
    JsonLdMulti::addImage(get_shop_logo_url(domain_info('shop_id')));

    SEOMeta::setTitle($info->title ?? env('APP_NAME'));
    SEOMeta::setDescription($info->excerpt->value ?? null);

    SEOTools::setTitle($info->title ?? env('APP_NAME'));
    SEOTools::setDescription($info->excerpt->value ?? null);
    SEOTools::setCanonical(url('/'));
    SEOTools::opengraph()->addProperty('image', get_shop_logo_url(domain_info('shop_id')));
    SEOTools::twitter()->setTitle($info->title ?? env('APP_NAME'));
    SEOTools::twitter()->setSite($info->title ?? null);
    SEOTools::jsonLd()->addImage(get_shop_logo_url(domain_info('shop_id')));

    return view(base_view() . '::page', compact('info'));
  }

  public function sitemap()
  {
    if (!file_exists('uploads/' . domain_info('shop_id') . '/sitemap.xml')) {
      abort(404);
    }
    return response(file_get_contents('uploads/' . domain_info('shop_id') . '/sitemap.xml'), 200, [
      'Content-Type' => 'application/xml'
    ]);
  }

  public function shop(Request $request)
  {
    if (Cache::has(domain_info('shop_id') . 'seo')) {
      $seo = json_decode(Cache::get(domain_info('shop_id') . 'seo'));
    } else {
      $data = ShopOption::where('shop_id', domain_info('shop_id'))->where('key', 'seo')->first();
      $seo = json_decode($data->value ?? '');
    }
    $shop = Shop::where('id', domain_info('shop_id'))->first();
    if (!empty($seo)) {
      JsonLdMulti::setTitle('Shop - ' . $seo->title ?? $shop->name ?? '');
      JsonLdMulti::setDescription($seo->description ?? null);
      JsonLdMulti::addImage(get_shop_logo_url(domain_info('shop_id')));

      SEOMeta::setTitle('Shop - ' . $seo->title ?? $shop->name ?? '');
      SEOMeta::setDescription($seo->description ?? null);
      SEOMeta::addKeyword($seo->tags ?? null);

      SEOTools::setTitle('Shop - ' . $seo->title ?? $shop->name ?? '');
      SEOTools::setDescription($seo->description ?? null);
      SEOTools::setCanonical($seo->canonical ?? url('/'));
      SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
      SEOTools::opengraph()->addProperty('image', get_shop_logo_url(domain_info('shop_id')));
      SEOTools::twitter()->setTitle('Shop - ' . $seo->title ?? $shop->name ?? '');
      SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
      SEOTools::jsonLd()->addImage(get_shop_logo_url(domain_info('shop_id')));
    }


    $src = $request->src ?? null;
    return view(base_view() . '::shop', compact('src'));
  }

  public function cart()
  {
    if (Cache::has(domain_info('shop_id') . 'seo')) {
      $seo = json_decode(Cache::get(domain_info('shop_id') . 'seo'));
    } else {
      $data = ShopOption::where('shop_id', domain_info('shop_id'))->where('key', 'seo')->first();
      $seo = json_decode($data->value ?? '');
    }
    $shop = Shop::where('id', domain_info('shop_id'))->first();
    if (!empty($seo)) {
      JsonLdMulti::setTitle('Cart - ' . $seo->title ?? $shop->name ?? '');
      JsonLdMulti::setDescription($seo->description ?? null);
      JsonLdMulti::addImage(get_shop_logo_url(domain_info('shop_id')));

      SEOMeta::setTitle('Cart - ' . $seo->title ?? $shop->name ?? '');
      SEOMeta::setDescription($seo->description ?? null);
      SEOMeta::addKeyword($seo->tags ?? null);

      SEOTools::setTitle('Cart - ' . $seo->title ?? $shop->name ?? '');
      SEOTools::setDescription($seo->description ?? null);
      SEOTools::setCanonical($seo->canonical ?? url('/'));
      SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
      SEOTools::opengraph()->addProperty('image', get_shop_logo_url(domain_info('shop_id')));
      SEOTools::twitter()->setTitle('Cart - ' . $seo->title ?? $shop->name ?? '');
      SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
      SEOTools::jsonLd()->addImage(get_shop_logo_url(domain_info('shop_id')));
    }

    return view(base_view() . '::cart');
  }

  public function wishlist()
  {
    if (Cache::has(domain_info('shop_id') . 'seo')) {
      $seo = json_decode(Cache::get(domain_info('shop_id') . 'seo'));
    } else {
      $data = ShopOption::where('shop_id', domain_info('shop_id'))->where('key', 'seo')->first();
      $seo = json_decode($data->value ?? '');
    }
    $shop = Shop::where('id', domain_info('shop_id'))->first();
    if (!empty($seo)) {
      JsonLdMulti::setTitle('Wishlist - ' . $seo->title ?? $shop->name ?? '');
      JsonLdMulti::setDescription($seo->description ?? null);
      JsonLdMulti::addImage(get_shop_logo_url(domain_info('shop_id')));

      SEOMeta::setTitle('Wishlist - ' . $seo->title ?? $shop->name ?? '');
      SEOMeta::setDescription($seo->description ?? null);
      SEOMeta::addKeyword($seo->tags ?? null);

      SEOTools::setTitle('Wishlist - ' . $seo->title ?? $shop->name ?? '');
      SEOTools::setDescription($seo->description ?? null);
      SEOTools::setCanonical($seo->canonical ?? url('/'));
      SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
      SEOTools::opengraph()->addProperty('image', get_shop_logo_url(domain_info('shop_id')));
      SEOTools::twitter()->setTitle('Wishlist - ' . $seo->title ?? $shop->name ?? '');
      SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
      SEOTools::jsonLd()->addImage(get_shop_logo_url(domain_info('shop_id')));
    }


    return view(base_view() . '::wishlist');
  }

  public function thanks()
  {
    if (Cache::has(domain_info('shop_id') . 'seo')) {
      $seo = json_decode(Cache::get(domain_info('shop_id') . 'seo'));
    } else {
      $data = ShopOption::where('shop_id', domain_info('shop_id'))->where('key', 'seo')->first();
      $seo = json_decode($data->value ?? '');
    }
    $shop = Shop::where('id', domain_info('shop_id'))->first();
    if (!empty($seo)) {
      JsonLdMulti::setTitle('Thank you - ' . $seo->title ?? $shop->name ?? '');
      JsonLdMulti::setDescription($seo->description ?? null);
      JsonLdMulti::addImage(get_shop_logo_url(domain_info('shop_id')));

      SEOMeta::setTitle('Thank you - ' . $seo->title ?? $shop->name ?? '');
      SEOMeta::setDescription($seo->description ?? null);
      SEOMeta::addKeyword($seo->tags ?? null);

      SEOTools::setTitle('Thank you - ' . $seo->title ?? $shop->name ?? '');
      SEOTools::setDescription($seo->description ?? null);
      SEOTools::setCanonical($seo->canonical ?? url('/'));
      SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
      SEOTools::opengraph()->addProperty('image', get_shop_logo_url(domain_info('shop_id')));
      SEOTools::twitter()->setTitle('Thank you - ' . $seo->title ?? $shop->name ?? '');
      SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
      SEOTools::jsonLd()->addImage(get_shop_logo_url(domain_info('shop_id')));
    }
    return view(base_view() . '::thanks');
  }

  public function make_local(Request $request)
  {

    Session::put('locale', $request->lang);
    Session::put('locale_changed', $request->lang);
    app()->setlocale($request->lang);

    return redirect('/');
  }

  public function checkout()
  {
    if (Auth::check() == true) {
      Auth::logout();
    }

    $shop_type = domain_info('shop_type');
    if (Cache::has(domain_info('shop_id') . 'seo')) {
      $seo = json_decode(Cache::get(domain_info('shop_id') . 'seo'));
    } else {
      $data = ShopOption::where('shop_id', domain_info('shop_id'))->where('key', 'seo')->first();
      $seo = json_decode($data->value ?? '');
    }
    $shop = Shop::where('id', domain_info('shop_id'))->first();
    if (!empty($seo)) {
      JsonLdMulti::setTitle('Checkout - ' . $seo->title ?? $shop->name ?? '');
      JsonLdMulti::setDescription($seo->description ?? null);
      JsonLdMulti::addImage(get_shop_logo_url(domain_info('shop_id')));

      SEOMeta::setTitle('Checkout - ' . $seo->title ?? $shop->name ?? '');
      SEOMeta::setDescription($seo->description ?? null);
      SEOMeta::addKeyword($seo->tags ?? null);

      SEOTools::setTitle('Checkout - ' . $seo->title ?? $shop->name ?? '');
      SEOTools::setDescription($seo->description ?? null);
      SEOTools::setCanonical($seo->canonical ?? url('/'));
      SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
      SEOTools::opengraph()->addProperty('image', get_shop_logo_url(domain_info('shop_id')));
      SEOTools::twitter()->setTitle('Checkout - ' . $seo->title ?? $shop->name ?? '');
      SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
      SEOTools::jsonLd()->addImage(get_shop_logo_url(domain_info('shop_id')));
    }

    if ($shop_type == 'reseller') {
      $gateways =  PaymentMethod::where('slug', 'razorpay')->get();
    } else {
      $gateways =  Gateway::byShop()->isActive()->get();
    }

    return view('checkout', compact('gateways'));
  }

  public function shipping_methods(Request $request)
  {
    $location = $request->id ?? null;
    $shipping_methods = ShippingMethod::where('status', '1')
      ->where('shop_id', current_shop_id())
      ->whereHas('shipping_locations',  function ($query) use ($location) {
        $query->where('city_id', $location)
            ->where('status', 1);
      })
      ->get();

    return response()->json($shipping_methods);
  }

  public function get_locations()
  {
    $locations = Category::where('type', 'city')->where('shop_id', current_shop_id())->select('id', 'name as text')->get();
    //   $locations = $locations->map(function ($item) {
    //     return [ 'id' => $item->id , 'title' > $item->name];
    // });
    return response()->json($locations);
  }

  public function detail($slug, $id)
  {
    $id = request()->route()->parameter('id');
    $info = Product::shopByDetail()->findorFail($id);
    $next = Product::byShop()->isActive()->where('type', 'product')->where('id', '>', $id)->first();
    $previous = Product::byShop()->isActive()->where('type', 'product')->where('id', '<', $id)->first();

    // $variations = collect($info->attributes)->groupBy(function ($q) {
    //   return $q->attribute->name;
    // });

    $info->load('reviews.customer');
    $options = DB::table('product_attributes')->where('product_id', $info->id)->get()->pluck('attribute_id')->toArray();
    $variations = Attribute::with(['options' => function ($query) use ($options) {
      $query->whereIn('id', $options);
    }])
    ->whereHas('options', function ($query) use ($options) {
      $query->whereIn('id', $options);
    })
    ->where('shop_id', $info->shop_id)
    ->get();



    $related = [];
    if (current_shop_type() == 'reseller') {
      $resellerProduct = $info->ResellerProduct;
      $seo = $resellerProduct->seo ?? $info->seo;
      $resellerProduct->load('category', 'brand');
      $info->category = $resellerProduct->category;
      $info->brand = $resellerProduct->brand;
      if (isset($info->category)) {
        $related = Product::shopByDetail()->where('id', '<>', $id)
          ->whereHas('ResellerProduct', function ($q) use ($resellerProduct) {
            $q->where('category_id', $resellerProduct->category_id);
          })
          ->latest()->take(20)->get();
      }
    } else {
      $seo = $info->seo;
      $categories = $info->categories()->pluck('id');
      if (isset($categories)) {
        $related = Product::shopByDetail()->where('id', '<>', $id)
          ->whereHas('categories', function ($q) use ($categories) {
            $q->whereIn('category_id', $categories);
          })
          ->latest()->take(20)->get();
      }
    }

    $related = $related ?? [];

    

    SEOMeta::setTitle($seo->title ?? $info->title);
    SEOMeta::setDescription($seo->description ?? $info->short_description ?? null);
    SEOMeta::addMeta('article:published_time', $info->updated_at->format('Y-m-d'), 'property');
    SEOMeta::addKeyword([$seo->keywords ?? null]);

    OpenGraph::setDescription($seo->description ?? $info->short_description ?? null);
    OpenGraph::setTitle($seo->title ?? $info->title);
    OpenGraph::addProperty('type', 'product');

    $image = $info->getFile('image');
    $images = $info->getFiles('images');
    $gallery = [];
    if (isset($image))
      $gallery[] = $info->getFile('image');
    if (isset($images))
      $gallery =  array_merge($gallery, $info->getFiles('images'));
    foreach ($gallery as $row) {
      OpenGraph::addImage(asset($row->url));
      JsonLdMulti::addImage(asset($row->url));
      JsonLd::addImage(asset($row->url));
    }


    JsonLd::setTitle($seo->title ?? $info->title);
    JsonLd::setDescription($seo->description ?? $info->short_description ?? null);
    JsonLd::setType('Product');

    JsonLdMulti::setTitle($seo->title ?? $info->title);
    JsonLdMulti::setDescription($seo->description ?? $info->short_description ?? null);
    JsonLdMulti::setType('Product');



    return view(base_view() . '::details', compact('info', 'gallery', 'next', 'previous', 'related', 'variations'));
  }

  public function product($id)
  {
    $id = request()->route()->parameter('id');

    $info = Product::shopByDetail()->findorFail($id);
    $next = Product::byShop()->isActive()->where('type', 'product')->where('id', '>', $id)->first();
    $previous = Product::byShop()->isActive()->where('type', 'product')->where('id', '<', $id)->first();

    $variations = collect($info->attributes)->groupBy(function ($q) {
      return $q->attribute->name;
    });

    return response()->json(['info' => $info, 'next' => $next, 'previous' => $previous, 'variations' => $variations], 200);
  }

  public function category($id)
  {
    $id = request()->route()->parameter('id');
    $info = Category::byShop()->isActive()->where('type', 'category')->findorFail($id);

    if (Cache::has(domain_info('shop_id') . 'seo')) {
      $seo = json_decode(Cache::get(domain_info('shop_id') . 'seo'));
    } else {
      $data = ShopOption::where('shop_id', domain_info('shop_id'))->where('key', 'seo')->first();
      $seo = json_decode($data->value ?? '');
    }
    $shop = Shop::where('id', domain_info('shop_id'))->first();

    JsonLdMulti::setTitle($info->name ?? env('APP_NAME'));
    JsonLdMulti::setDescription($seo->description ?? null);
    JsonLdMulti::addImage(asset($info->image ?? 'uploads/' . domain_info('shop_id') . '/logo.png'));

    SEOMeta::setTitle($info->name ?? env('APP_NAME'));
    SEOMeta::setDescription($seo->description ?? null);
    SEOMeta::addKeyword($seo->tags ?? null);

    SEOTools::setTitle($info->name ?? env('APP_NAME'));
    SEOTools::setDescription($seo->description ?? null);
    SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
    SEOTools::opengraph()->addProperty('image', asset($info->image ?? 'uploads/' . domain_info('shop_id') . '/logo.png'));
    SEOTools::twitter()->setTitle($info->name ?? env('APP_NAME'));
    SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
    SEOTools::jsonLd()->addImage(asset($info->image ?? 'uploads/' . domain_info('shop_id') . '/logo.png'));



    return view(base_view() . '::shop', compact('info'));
  }

  public function brand($id)
  {
    $id = request()->route()->parameter('id');
    $info = Brand::where('shop_id', current_shop_id())->where('status', 1)->findorFail($id);

    if (Cache::has(domain_info('shop_id') . 'seo')) {
      $seo = json_decode(Cache::get(domain_info('shop_id') . 'seo'));
    } else {
      $data = ShopOption::where('shop_id', domain_info('shop_id'))->where('key', 'seo')->first();
      $seo = json_decode($data->value ?? '');
    }
    $shop = Shop::where('id', domain_info('shop_id'))->first();

    JsonLdMulti::setTitle($info->name ?? env('APP_NAME'));
    JsonLdMulti::setDescription($seo->description ?? null);
    JsonLdMulti::addImage(asset($info->image ?? 'uploads/' . domain_info('shop_id') . '/logo.png'));

    SEOMeta::setTitle($info->name ?? env('APP_NAME'));
    SEOMeta::setDescription($seo->description ?? null);
    SEOMeta::addKeyword($seo->tags ?? null);

    SEOTools::setTitle($info->name ?? env('APP_NAME'));
    SEOTools::setDescription($seo->description ?? null);
    SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
    SEOTools::opengraph()->addProperty('image', asset($info->image ?? 'uploads/' . domain_info('shop_id') . '/logo.png'));
    SEOTools::twitter()->setTitle($info->name ?? env('APP_NAME'));
    SEOTools::twitter()->setSite($info->name ?? null);
    SEOTools::jsonLd()->addImage(asset($info->image ?? 'uploads/' . domain_info('shop_id') . '/logo.png'));

    return view(base_view() . '::shop', compact('info'));
  }

  public function home_page_products(Request $request)
  {
    if ($request->latest_product) {
      if ($request->latest_product == 1) {
        $data['get_latest_products'] = $this->get_latest_products();
      } else {
        $data['get_latest_products'] = $this->get_latest_products($request->latest_product);
      }
    }

    if ($request->random_product) {
      if ($request->random_product == 1) {
        $data['get_random_products'] = $this->get_random_products();
      } else {
        $data['get_random_products'] = $this->get_random_products($request->random_product);
      }
    }
    if ($request->get_offerable_products) {
      if ($request->get_offerable_products == 1) {
        $data['get_offerable_products'] = $this->get_offerable_products();
      } else {
        $data['get_offerable_products'] = $this->get_offerable_products($request->random_product);
      }
    }

    if ($request->trending_products) {
      if ($request->trending_products == 1) {
        $data['get_trending_products'] = $this->get_trending_products();
      } else {
        $data['get_trending_products'] = $this->get_trending_products($request->trending_products);
      }
    }

    if ($request->best_selling_product) {
      if ($request->best_selling_product == 1) {
        $data['get_best_selling_product'] = $this->get_best_selling_product();
      } else {
        $data['get_best_selling_product'] = $this->get_best_selling_product($request->best_selling_product);
      }
    }

    if ($request->sliders) {
      $data['sliders'] = $this->get_slider();
    }

    if ($request->menu_category) {
      $data['get_menu_category'] = $this->get_menu_category();
    }

    if ($request->bump_adds) {
      $data['bump_adds'] = $this->get_bump_adds();
    }

    if ($request->banner_adds) {
      $data['banner_adds'] = $this->get_banner_adds();
    }

    if ($request->featured_category) {
      $data['featured_category'] = $this->get_featured_category();
    }

    if ($request->featured_brand) {
      $data['featured_brand'] = $this->get_featured_brand();
    }

    if ($request->category_with_product) {
      $data['category_with_product'] = $this->get_category_with_product();
    }

    if ($request->brand_with_product) {
      $data['brand_with_product'] = $this->get_brand_with_product();
    }
    return response()->json($data);
  }

  public  function get_slider()
  {
    return Cache::remember('get_slider_' .  current_shop_id(), 420, function () {
      return  Slider::where('shop_id', current_shop_id())->where('status', 1)->latest()->get();
    });
  }

  public function get_menu_category()
  {
    return Cache::remember('get_menu_category_' .  current_shop_id(), 420, function () {
      return Category::where('type', 'category')->where('shop_id', current_shop_id())->where('menu_status', 1)->where('status', 1)->withCount('products')->get()->map(function ($q) {
        $data['id'] = $q->id;
        $data['name'] = $q->name;
        $data['slug'] = $q->slug;
        $data['products_count'] = $q->products_count;
        $data['url'] = $q->image;
        return $data;
      });
    });
  }

  public function get_featured_attributes()
  {
    if (current_shop_type() == 'reseller') {
      return [];
    }
    return Cache::remember('get_featured_attributes_' .  current_shop_id(), 420, function () {
    //   return Attribute::where('shop_id', current_shop_id())->where('featured', 1)->where('status', 1)->where('parent_id',NULL)
    //   ->whereHas('parent', function($query){
    //     $query->where('status', '1');
    // })->with('featured_with_product_count')->get();
      return Attribute::where('shop_id', current_shop_id())->where('featured', 1)->where('status', 1)->where('parent_id',NULL)
      ->with('featured_with_product_count')->get();
    });
  }

  public function get_ralated_product_with_latest_post(Request $request)
  {
    $this->cats = $request->categories ?? [];
    $avg = Review::where('product_id', $request->term)->avg('rating');
    $ratting_count = Review::where('product_id', $request->term)->count();
    $avg = (int)$avg;
    $related = Product::shopByList()->whereHas('categories', function ($q) {
      $q->whereIn('category_id', $this->cats);
    })->latest()->take(20)->get();

    $get_latest_products =  $this->get_latest_products();
    $data['get_latest_products'] = $get_latest_products;
    $data['get_related_products'] = $related;
    $data['ratting_count'] = $ratting_count;
    $data['ratting_avg'] = $avg;

    return response()->json($data);
  }

  public function get_ralated_products(Request $request)
  {
    $this->cats = $request->cats;

    $posts = Product::shopByList()->whereHas('categories', function ($q) {
      $q->whereIn('category_id', $this->cats);
    })->latest()->paginate(30);

    return response()->json($posts);
  }

  public function product_search(Request $request)
  {
    $posts = Product::shopByList()->where('title', 'LIKE', '%' . $request->src . '%')->latest()->paginate(30);
    return response()->json($posts);
  }


  public function get_featured_category()
  {
    return Cache::remember('featured_category_' .  current_shop_id(), 420, function () {
      return Category::where('shop_id', current_shop_id())->where('type', 'category')->where('featured', 1)->where('status', 1)->latest()->get()
        ->map(function ($q) {
          $data['id'] = $q->id;
          $data['name'] = $q->name;
          $data['slug'] = $q->slug;
          $data['type'] = $q->type;
          $data['url'] = $q->image;
          return $data;
        });
    });
  }

  public function get_featured_brand()
  {
    return Cache::remember('featured_brand_' .  current_shop_id(), 420, function () {
      return Brand::where('shop_id', current_shop_id())->withCount('products')->where('featured', 1)->where('status', 1)->latest()->get();
    });
  }

  public function get_category()
  {
    return Cache::remember('category_' .  current_shop_id(), 420, function () {
      return Category::with('childrenCategories')->where('shop_id', current_shop_id())->where('menu_status', 1)->where('type', 'category')->where('status', 1)->withCount('products')->latest()->get()->map(function ($q) {
        $data['id'] = $q->id;
        $data['name'] = $q->name;
        $data['slug'] = $q->slug;
        $data['products_count'] = $q->products_count;
        $data['url'] = $q->image;
        return $data;
      });
    });
  }

  public function get_brand()
  {
    return Cache::remember('brand_' .  current_shop_id(), 420, function () {
      return Brand::byShop()->where('status', 1)->withCount('products')->latest()->get();
    });
  }

  public function get_products(Request $request)
  {
    return Cache::remember('get_products_' .  current_shop_id(), 420, function () {
      return Product::shopByList()->latest()->paginate(30);
    });
  }

  public function get_offerable_products($limit = 20)
  {
    $posts = Product::shopByList()->todayOffer()->inRandomOrder()->take(20)->get();

    foreach ($posts as $post) {
      if (isset($post->price->ending_date))
        $post->price->ending_date = Carbon::parse($post->price->ending_date)->endOfDay()->format('Y-m-d H:i:s');
      $post->price->starting_date = Carbon::parse($post->price->starting_date)->startOfDay()->format('Y-m-d H:i:s');
    }
    return $posts;
  }

  public function get_latest_products($limit = 20)
  {
    return Product::shopByList()->latest()->take($limit)->get();
  }

  public function max_price()
  {
    return Attribute::where('shop_id', current_shop_id())->max('price');
  }

  public function min_price()
  {
    return Attribute::where('shop_id', current_shop_id())->min('price');
  }

  public function get_bump_adds()
  {
    return Cache::remember('get_bump_adds_' .  current_shop_id(), 420, function () {
      return Banner::byShop()->where('type', 'offer')->where('status', 1)->latest()->get();
    });
  }
  public function get_banner_adds()
  {
    return Cache::remember('get_banner_adds_' .  current_shop_id(), 420, function () {
      return Banner::byShop()->where('type', 'banner')->where('status', 1)->latest()->get();
    });
  }


  public function get_shop_attributes()
  {
    $data['categories'] = $this->get_category();
    $data['brands'] = $this->get_featured_brand(); // $this->get_brand();
    $data['attributes'] = $this->get_featured_attributes();
    return $data;
  }


  public function get_shop_products(Request $request)
  {
    $products = Product::shopFilter($request);
    $products = $products->paginate($request->limit ?? 20);
    return response()->json($products);
  }

  public function get_random_products($limit = 20)
  {
    $limit = request()->route()->parameter('limit') ?? 20;
    return Product::shopByList()->inRandomOrder()->take($limit)->get();
  }

  public function get_trending_products($limit = 20)
  {
    return Product::featuredFilter()->latest()->take($limit)->get();
  }

  public function get_top_rated_products($limit = 20)
  {
    return Product::shopByList()
      ->orderByDesc("avg_rating")
      ->havingRaw("avg_rating > 0")
      ->take($limit)
      ->get();
  }

  public function get_best_selling_product($limit = 20)
  {
    return Product::featuredFilter()->latest()->take($limit)->get();
  }

  public function get_category_with_product($limit = 10)
  {
    $limit = request()->route()->parameter('limit');
    return Category::where('shop_id', current_shop_id())->where('type', 'category')->with('take_products')->take($limit)->get();
  }

  public function get_brand_with_product($limit = 10)
  {
    $limit = request()->route()->parameter('limit');
    return Brand::where('shop_id', current_shop_id())->with('take_products')->take($limit)->get();
  }
}
