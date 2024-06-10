<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{

    public function list(Request $request, $id)
    {
        $limit = $request->limit ?? 10;
        $reviews = Review::whereHas('product')->where('product_id', $id)->latest()->paginate($limit);

        return response()->json($reviews, 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $id = request()->route()->parameter('id');
        $validated = $request->validate([
            // 'name' => 'required|max:50',
            'rating' => 'required|max:50',
            // 'email' => 'required|email_address|max:50',
            'comment' => 'required|max:250',
        ]);

        $shop_id = domain_info('shop_id');

        $user = Auth::guard('customer')->user();

        $review = Review::where('product_id',  $id)->where('customer_id',  $user->id)->first();
        if (isset($review)) {
            return response()->json(['message' => 'Already your review submitted'], 422);
        }

        $product = Product::where('id', $id)->first();
        if (!isset($product)) {
            return response()->json(['message' => 'Product not found.'], 422);
        }
        $rating = new Review();
        $rating->rating = $request->rating;
        $rating->name = $user->fullname;
        $rating->email = $user->email;
        $rating->comment = $request->comment;
        $rating->customer_id = $user->id;
        $rating->order_item_id = $product->id ?? null;
        $rating->product_id = $id;
        $rating->save();

        return response()->json(['message' => 'Thanks For Your Review']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
