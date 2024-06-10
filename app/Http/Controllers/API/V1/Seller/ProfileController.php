<?php

namespace App\Http\Controllers\API\V1\Seller;

use App\Models\User;
use App\Models\Shop;
use App\Models\Partner;
use App\Models\ShopOption;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use ApiResponser;

    public function me(Request $request)
    {
        $user = auth()->user();
        return $this->success($user);
    }

    public function shop()
    {
        $shop = Shop::with('subscription.plan')->where('user_id', auth()->id())->first();
        $shop->data = isset($shop) ? json_decode($shop->data) : null;
        $currency = ShopOption::where('shop_id', $shop->id)->where('key', 'currency')->first();
        $data = [];
        if (!empty($currency)) {
            $data['position'] = $currency->position;
            $data['name'] = $currency->code;
            $data['icon'] = $currency->symbol;
        } else {
            $data['position'] = 'left';
            $data['name'] = "INR";
            $data['icon'] = "₹";
        }
        $shop->currency = $data;
        return $this->success($shop);
    }

    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password'        => 'required',
            'new_password'         => 'required|min:8|max:30|without_spaces',
            'confirm_password' => 'required|same:new_password'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'validations fails',
                'errors' => $validator->errors()
            ], 422);
        }
        $user = $request->user();

        if (Hash::check($request->current_password, $user->password)) {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);


            return response()->json([
                'message' => ' password successfully updated',
                'errors' => $validator->errors()
            ], 200);
        } else {
            return response()->json([
                'message' => 'old password does not match',
                'errors' => $validator->errors()
            ], 422);
        }
    }


    public function update_profile(Request $request)
    {
        $user = User::find(auth()->id());

        $request->validate([
            'first_name' => 'required|max:255',
            'email'  =>  'required|email_address|unique:users,email,' . auth()->id()

        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name ?? "";
        $user->email = $request->email;
        $user->save();

        if ($user->isPartner()) {
            $partner = Partner::where('user_id', $user->id)->first();
            $partner->mobile_number = $request->mobile_number;
            $partner->save();
        }

        if (!empty($request->bank_details)) {
            $partner = Partner::where('user_id', $user->id)->first();
            $partner->bank_details = $request->bank_details;
            $partner->mobile_number = $request->mobile_number;
            $partner->save();
        }

        return response()->json([
            'message' => trans('Profile Updated Successfully !!'),
            'status' => 'success'
        ], 200);
    }
}
