<?php

namespace App\Http\Controllers\Seller;

use App\Models\TaxRate;
use App\Models\Country;
use App\Models\State;
use App\Models\TaxClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class TaxController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $taxes = TaxClass::where('shop_id', current_shop_id())->where('status', '<>', 0)
            ->paginate(20);

        return view('seller.tax.index', compact('taxes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('seller.tax.create');
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
            'based_on' => 'sometimes|required',
            'status' => 'required',
        ]);

        $tax = new TaxClass();
        $tax->name = $request->title;
        $tax->based_on = $request->based_on ?? 'shipping';
        $tax->shop_id = current_shop_id();
        $tax->status = $request->status ?? 1;
        $tax->save();

        // Session::flash('success', trans('Tax class Created Successfully'));
        // return back();
        return response()->json([trans('Tax Class Created Successfully')]);
    }

    public function show($id)
    {
        
        $taxes = TaxRate::where('status', '<>', 0)->with('tax_class')->where('tax_class_id', $id)
            ->get();
        $id = $id;

        return view('seller.tax.tax_rate.index', compact('taxes','id'));
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
        // $info = TaxClass::where('shop_id', current_shop_id())->with(['tax_rates'])->findorFail($id);
        $info = TaxClass::where('shop_id', current_shop_id())->findorFail($id);
        return view('seller.tax.edit', compact('info'));
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
            'name' => 'required|max:50',
            'based_on' => 'sometimes|required',
            'status' => 'required',
        ]);
        $user = Auth::user();
        $tax = TaxClass::where('shop_id', current_shop_id())->findorFail($id);
        $tax->name = $request->name;
        $tax->based_on = $request->based_on ?? 'shipping';
        $tax->shop_id = current_shop_id();
        $tax->status = $request->status ?? 1;
        $tax->save();

        // Session::flash('success', trans('Tax class Updated Successfully'));
        // return back();
        return response()->json([trans('Tax Class Updated Successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function action_destroy(Request $request)
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

                $categorys = TaxClass::where('id', $id)->first();
                $categorys->status = $request->method;
                $categorys->save();
            }
        } else {

            return response()->json(['errors' => ['Tax Class id not selected']], 400);
        }

        return response()->json(['Success']);
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
                $tax = TaxClass::where('shop_id', current_shop_id())->findorFail($id);
                $tax_meta = TaxRate::where('shipping_method_id', $tax->$id);
                $tax_meta->delete();
                $tax->delete();
            }
        }

        return response()->json([trans('Shipping Method Deleted')]);
    }

    public function update_tax_rate(Request $request)
    {
        $request->validate([
            'location' => 'required',
            'shipping_method' => 'required',
        ]);
        $tax = TaxRate::where('city_id', $request->location)->where('shipping_method_id', $request->shipping_method)->first();
        if (!isset($tax)) {
            $tax = new TaxRate();
        }
        $tax->shipping_method_id = $request->shipping_method;
        $tax->city_id = $request->location;
        $tax->status = 1;
        $tax->save();

        Session::flash('success', trans('Shipping Location created Successfully'));
        return back();
    }

    public function remove_tax_rate(Request $request, $id, $type)
    {
        $location = TaxRate::where('id',  $id)->first();
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
    public function tax_rate_destroy(Request $request)
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

                $shipping = TaxRate::findOrFail($id);
                $shipping->status = $request->method;
                $shipping->save();
            }
        } else {

            return response()->json(['errors' => ['Shipping Method id not selected']], 400);
        }

        return response()->json(['Success']);
    }

    ###### Tax Rate Index ######

    public function tax_index()
    {
        $user = Auth::user();
        $taxes = TaxRate::where('status', '<>', 0)->with('tax_class')
            ->paginate(20);

        return view('seller.tax.tax_rate.index', compact('taxes'));
    }

    ###### Tax Rate Create ######
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function  tax_create(Request $request, $id)
    {
        $id= $id;
        return view('seller.tax.tax_rate.create',compact('id'));
    }

    ###### Tax Rate Store ######

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function tax_store(Request $request)
    {
        $request->validate([
            'rate' => 'required',
        ]);

        $tax = new TaxRate();
        $tax->tax_class_id = $request->rate_class;
        if (!empty($request->country)) {
            $country_code = Country::where('id', $request->country)->first();
            $tax->country_code =  $country_code->code ?? '';
            $tax->country = $country_code->name ?? '';
        }

        // if (empty($request->state)) {
        //     $tax->state = '*';
        // } else {
        //     $tax->state_id = $request->state ?? '';
        // }
        if (!empty($request->state)) {
            $state_code = State::where('id', $request->state)->first();
            $tax->state_id = $request->state ?? '';
            $tax->state = $state_code->name ?? '';
        }
        else{
            $tax->state = '*'; 
        }


        $tax->zipcode = $request->zipcode ?? '*';
        $tax->rate = $request->rate;
        $tax->status = $request->status ?? 1;
        $tax->save();

        return response()->json([trans('Tax Rate Created Successfully')]);
    }


    ###### Tax Rate Full Action ######
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function taxrate_destroy(Request $request)
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

                $categorys = TaxRate::where('id', $id)->first();
                $categorys->status = $request->method;
                $categorys->save();
            }
        } else {

            return response()->json(['errors' => ['Tax rate id not selected']], 400);
        }

        return response()->json(['Success']);
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function taxrate_edit($id)
    {
       
        $info = TaxRate::with('tax_class')->findorFail($id);
        return view('seller.tax.tax_rate.edit', compact('info'));
    }

   
      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function  taxrate_update(Request $request, $id)
    {
        $request->validate([
            'rate' => 'required',
        ]);
        $user = Auth::user();
        $tax = TaxRate::findorFail($id);
        $tax->tax_class_id = $request->rate_class;
       
        if (!empty($request->country)) {
            $country_code = Country::where('id', $request->country)->first();
            $tax->country_code =  $country_code->code ?? '';
            $tax->country = $country_code->name ?? '';
        }

        // if ($request->state == 0) {
        //     $tax->state = '*';
        // } else {
        //     $tax->state_id = $request->state ?? '';
        // }
        if (!empty($request->state)) {
            $state_code = State::where('id', $request->state)->first();
            $tax->state_id = $request->state ?? '';
            $tax->state = $state_code->name ?? '';
        }
        else{
            $tax->state = '*'; 
        }

        $tax->zipcode = $request->zipcode ?? '*';
        $tax->rate = $request->rate;
        $tax->status = $request->status ?? 1;
        $tax->save();

        return response()->json([trans('Tax Rate Updated Successfully')]);
    }
}
