<?php

namespace App\Http\Controllers\Storefront;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $products = Address::paginate(20);
        $toReturn = [];
        // foreach ($products as $product) {
        //     $toReturn = $product;
        //     $toReturn->price->formatted = $product->price->price->formatted;
        // }
        return response()->json($products);
    }

    public function create(Request $request)
    {
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'sometimes|required',
            'company_name' => 'sometimes|required',
            'line_one' => 'required',
            'line_two' => 'sometimes|required',
            'line_three' => 'sometimes|required',
            'city_id' => 'sometimes|required',
            'state_id' => 'sometimes|required',
            'country_code' => 'sometimes|required',
            'postcode' => 'sometimes|required',
            'delivery_instructions' => 'sometimes|required',
            'contact_email' => 'sometimes|required',
            'contact_phone' => 'sometimes|required',
            'shipping_default' => 'sometimes|required',
            'billing_default' => 'sometimes|required',
        ]);

        $user = auth()->user();
        $address = new Address();
        $address->user_id = $user->id;
        $address->user_type = get_class($user);
        $address->title = $request->first_name . ' ' . $request->last_name;
        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name ?? null;
        $address->company_name = $request->company_name ?? null;
        $address->postcode = $request->postcode ?? null;
        $address->contact_email = $request->contact_email ?? null;
        $address->contact_phone = $request->contact_phone ?? null;
        $address->save();

        return response()->json(['data' => $address]);
    }

    public function edit(Request $request)
    {
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'company_name' => 'required',
            'line_one' => 'required',
            'line_two' => 'required',
            'line_three' => 'required',
            'city' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_code' => 'required',
            'postcode' => 'required',
            'delivery_instructions' => 'required',
            'contact_email' => 'required',
            'contact_phone' => 'required',
            'meta' => 'required',
            'shipping_default' => 'required',
            'billing_default' => 'required',
        ]);

        $user = auth()->user();
        $address = Address::findOrFail($id);
        $address->user_id = $user->id;
        $address->user_type = get_class($user);
        $address->title = $request->first_name . ' ' . $request->last_name;
        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name ?? null;
        $address->company_name = $request->company_name ?? null;
        $address->postcode = $request->postcode ?? null;
        $address->contact_email = $request->contact_email ?? null;
        $address->contact_phone = $request->contact_phone ?? null;
        $address->status = $request->status ?? 1;
        $address->save();

        return response()->json(['data' => $address]);
    }

    public function destroy(Request $request, $id)
    {
        $address = Address::findOrFail($id);
        return response()->json(['data' => $address]);
    }
}
