<?php

namespace App\Http\Controllers\Seller;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $shop_id = current_shop_id();
        $posts = Review::hasCustomer()->withProduct()->latest()->paginate(20);
        return view('seller.review.index', compact('posts'));
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
    public function store(Request $request)
    {
        //
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
        $info = Review::findOrFail($id);
        return view('seller.review.edit', compact('info'));
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
        $validatedData = $request->validate([
            'status' => 'required',
        ]);

        $post = Review::findOrFail($id);
        $post->status = $request->status ?? 1;
        $post->save();

        // Session::flash('success', trans('Review Updated !!'));
        // return back();
        return response()->json([trans('Review Updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'method' => 'required'
        ],
        [
            'method.required' => 'Please Select Action'
        ]);
        
        if ($request->ids != '') {
            foreach ($request->ids as $id) {
                $review = Review::findOrFail($id);
                $review->status = $request->method;
                $review->save();
            }
        } else {

            return response()->json(['errors' => ['Review id not selected']], 400);
        }

        return response()->json(['Success']);
        // if ($request->method == 'delete') {
        //     $shop_id = current_shop_id();
        //     if ($request->ids) {
        //         foreach ($request->ids as $id) {
        //             Review::whereHas('customer', function ($q) use ($shop_id) {
        //                 $q->where('shop_id', $shop_id);
        //             })->where('id', $id)->delete();
        //         }
        //     }
        // }

        // return response()->json(trans('Review Deleted'));
    }
}
