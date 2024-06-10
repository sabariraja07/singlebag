<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use PDO;

class OrderTrackController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function order_track(Request $request)
    {
        $shop_id = current_shop_id(); 
        $orders = Order::where('order_no', $request->order_no)->shopFinder()->first();
        // return view('ordertrack', compact('orders'));
        return view(base_view() . '::ordertrack', compact('orders'));

    }

}
