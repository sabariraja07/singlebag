<?php

namespace App\Http\Controllers\Storefront;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WishlistController extends Controller
{
    public function index()
    {
        $products = Product::with('prices')->paginate(20);
        $toReturn = [];
        // foreach ($products as $product) {
        //     $toReturn = $product;
        //     $toReturn->price->formatted = $product->price->price->formatted;
        // }
        return response()->json($products);
    }

    public function show(Request $request, $id)
    {
    }
}
