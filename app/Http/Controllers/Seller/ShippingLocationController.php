<?php

namespace App\Http\Controllers\Seller;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;
use App\Models\ShippingLocation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShippingLocationController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $taxes = ShippingLocation::where('status', '<>', 0)
            ->paginate(20);

        return view('seller.shipping-location.index', compact('taxes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('seller.shipping-location.create');
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
            'rate' => 'required',
        ]);

        $tax = new ShippingLocation();
        if (!empty($request->country)) {
            $country_code = Country::where('id', $request->country)->first();
            $tax->country_code =  $country_code->code ?? '';
            $tax->country_id = $request->country ?? '';
            $tax->country =  $country_code->name ?? '';
        } else {
            $tax->country =  '*';
        }

        if (!empty($request->state)) {
            $state_code = State::where('id', $request->state)->first();
            $tax->state_id = $request->state ?? '';
            $tax->state = $state_code->name ?? '';
        } else {
            $tax->state = '*';
        }

        if (!empty($request->city)) {
            $city_code = City::where('id', $request->city)->first();
            $tax->city_id = $request->city ?? '';
            $tax->city = $city_code->name;
        } else {
            $tax->city = '*';
        }


        $tax->estimated_days = $request->delivery_time ?? '';
        $tax->rate = $request->rate;
        $tax->zipcode = $request->zipcode ?? '*';
        $tax->status = $request->status ?? 1;
        $tax->save();


        return response()->json([trans('Shipping Location Created Successfully')]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function shipping_location_destroy(Request $request)
    {
        $request->validate(
            [
                'method' => 'required'
            ],
            [
                'method.required' => 'Please Select Action'
            ]
        );
        if ($request->ids != '') {
            foreach ($request->ids as $id) {

                $categorys = ShippingLocation::where('id', $id)->first();
                $categorys->status = $request->method;
                $categorys->save();
            }
        } else {

            return response()->json(['errors' => ['Shipping location not selected']], 400);
        }

        return response()->json(['Success']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function shipping_location_edit($id)
    {

        $info = ShippingLocation::findorFail($id);
        return view('seller.shipping-location.edit', compact('info'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function  shipping_location_update(Request $request, $id)
    {


        $request->validate([
            'rate' => 'required',
        ]);

        $user = Auth::user();
        $tax = ShippingLocation::findorFail($id);

        if (!empty($request->country)) {
            $country_code = Country::where('id', $request->country)->first();
            $tax->country_code =  $country_code->code ?? '';
            $tax->country_id = $request->country ?? '';
        } else {
            $tax->country =  '*';
        }

        if (!empty($request->state)) {
            $tax->state_id = $request->state ?? '';
        } else {
            $tax->state = '*';
        }

        if (!empty($request->city)) {
            $tax->city_id = $request->city ?? '';
        } else {
            $tax->city = '*';
        }


        $tax->estimated_days = $request->delivery_time ?? '';
        $tax->rate = $request->rate;
        $tax->zipcode = $request->zipcode ?? '';
        $tax->status = $request->status ?? 1;
        $tax->save();

        return response()->json([trans('Shipping Location Updated Successfully')]);
    }
}
