<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use App\Models\ShippingLocation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShippingMethodController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $posts = ShippingMethod::where('shop_id', current_shop_id())->where('status', '!=', 5)
            ->paginate(20);

        return view('seller.shipping-method.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('seller.shipping-method.create');
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
            'title' => 'required|max:50',
            'cost' => 'required|max:50',
            // 'locations' => 'required',
        ]);

        $post = new ShippingMethod();
        $post->title = $request->title;
        $post->shop_id = current_shop_id();
        $post->cost = $request->cost;
        $post->estimated_delivery = $request->estimated_delivery ?? "";
        $post->status = $request->status ?? 1;
        $post->save();

        Session::flash('success', trans('Shipping Method Created Successfully'));
        return back();
    }

    public function show($id)
    {
        $user = Auth::user();
        $info = ShippingMethod::where('shop_id', current_shop_id())->with(['locations', 'locations.city'])->findorFail($id);
        return response()->json($info);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $info = ShippingMethod::where('shop_id', current_shop_id())->with(['locations', 'locations.city'])->findorFail($id);
        return view('seller.shipping-method.edit', compact('info'));
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
            'title' => 'required|max:50',
            'cost' => 'required|max:50',
        ]);
        $user = Auth::user();
        $post = ShippingMethod::where('shop_id', current_shop_id())->findorFail($id);
        $post->title = $request->title;
        $post->shop_id = current_shop_id();
        $post->cost = $request->cost;
        $post->estimated_delivery = $request->estimated_delivery ?? "";
        $post->status = $request->status ?? 1;
        $post->save();

        Session::flash('success', trans('Method Updated Successfully'));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        if ($request->method == 'delete') {
            foreach ($request->ids as $key => $id) {
                $post = ShippingMethod::where('shop_id', current_shop_id())->findorFail($id);
                $post_meta = ShippingLocation::where('shipping_method_id', $post->$id);
                $post_meta->delete();
                $post->delete();
            }
        }

        return response()->json([trans('Shipping Method Deleted')]);
    }

    public function update_location(Request $request)
    {
        $request->validate([
            'location' => 'required',
            'shipping_method' => 'required',
        ]);
        $post = ShippingLocation::where('city_id', $request->location)->where('shipping_method_id', $request->shipping_method)->first();
        if (!isset($post)) {
            $post = new ShippingLocation();
        }
        $post->shipping_method_id = $request->shipping_method;
        $post->city_id = $request->location;
        $post->status = 1;
        $post->save();

        Session::flash('success', trans('Shipping Location created Successfully'));
        return back();
    }

    public function remove_location(Request $request, $id, $type)
    {
        $location = ShippingLocation::where('id',  $id)->first();
        if ($type == 0) {
            $location->status = 0;
            $location->save();

            Session::flash('success', trans('Shipping Location Disabled'));
            return back();
        } elseif ($type == 1) {
            $location->status = 1;
            $location->save();

            Session::flash('success', trans('Shipping Location Enabled'));
            return back();
        }
    }

   /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function shippingmethod_destroy(Request $request)
    {
        $request->validate([
            'method' => 'required'
        ],
        [
            'method.required' => 'Please Select Action'
        ]); 
        if ($request->ids != '') {
            foreach ($request->ids as $id) {
                
                    $shipping = ShippingMethod::findOrFail($id);
                    $shipping->status = $request->method;
                    $shipping->save();
               
               
            }
        } else {

            return response()->json(['errors' => ['Shipping Method id not selected']], 400);
        }

        return response()->json(['Success']);
       
    }

}
