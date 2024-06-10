<?php

namespace App\Http\Controllers\Seller;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Coupon::where('shop_id', current_shop_id())->latest()->paginate(20);
        return view('seller.coupon.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('seller.coupon.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $posts_count = Coupon::where('shop_id', current_shop_id())->count();
        if (user_plan_limit('product_limit', $posts_count)) {
            $error['errors']['error'] = trans('Maximum posts limit exceeded');
            return response()->json($error, 401);
        }
        
        $request->validate([
            'coupon_code' => 'required|max:50',
            'date' => 'required|date',
            'percent' => 'required|numeric|max:100',
            'status' => 'required'
        ]);

        $post = new Coupon();
        $post->title = $request->title ?? '';
        $post->description = $request->description ?? null;
        $post->code = $request->coupon_code;
        $post->expiry_date = Carbon::parse($request->date)->endOfDay();
        $post->amount = $request->percent;
        $post->discount_type = 0;
        $post->status = $request->status ?? 1;
        $post->shop_id = current_shop_id();
        $post->user_id = auth()->id();
        $post->save();

        return response()->json('Coupon Created Successfully....!!!');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = Coupon::where('shop_id', current_shop_id())->findOrFail($id);

        return view('seller.coupon.edit', compact('info'));
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
        $request->validate([
            'coupon_code' => 'required|max:50',
            'date' => 'required|date',
            'percent' => 'required|numeric|max:100',
            'status' => 'required'
        ]);

        $post = Coupon::where('shop_id', current_shop_id())->findOrFail($id);
        $post->title = $request->title ?? '';
        $post->description = $request->description ?? null;
        $post->code = $request->coupon_code;
        $post->expiry_date = Carbon::parse($request->date)->endOfDay();
        $post->amount = $request->percent;
        $post->discount_type = 0;
        $post->status = $request->status ?? 1;
        $post->save();

        // Session::flash('success', trans('Coupon Updated !!'));
        // return back();
        return response()->json([trans('Coupon Updated')]);
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
                $coupon = Coupon::findOrFail($id);
                $coupon->status = $request->method;
                $coupon->save();
            }
        } else {

            return response()->json(['errors' => ['Coupon id not selected']], 400);
        }

        return response()->json(['Success']);

        //  $auth_id=Auth::id();
        // if ($request->method=='delete') {
        //    foreach ($request->ids as $key => $id) {
        //        $post = Coupon::where('shop_id',current_shop_id())->findorFail($id);
        //        $post->delete();
        //    }
        // }

        // return response()->json([trans('Coupon Deleted')]);
    }
}
