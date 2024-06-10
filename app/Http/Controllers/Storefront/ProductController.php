<?php

namespace App\Http\Controllers\Storefront;

use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Channel;
use Illuminate\Http\Request;
use Darryldecode\Cart\CartCondition;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $channel = Channel::where('type', 'online')->first();
        $products = Product::channel($channel)->whereHas('prices')->with('prices')->paginate(20);
        return response()->json($products);
    }

    public function shop(Request $request)
    {
        $products = Product::whereHas('prices')->with('prices')->paginate(20);
        return response()->json($products);
    }

    public function show(Request $request, $id)
    {
    }
}
