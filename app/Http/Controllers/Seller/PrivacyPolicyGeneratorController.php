<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShopOption;
use Illuminate\Support\Facades\Session;
class PrivacyPolicyGeneratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {       
        return view('seller.store.privacy_policy_generator.create');
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
            'store_name' => 'required|max:30',
            'email' => 'required|max:50|email_address',
            'mobile_number' => 'required|integer|digits:10',
            'address' => 'required|max:250',
            'city' => 'required|max:20',
            'state' => 'required|max:20',
            'country' => 'required|max:20',
            'zip_code' => 'required|max:20',
        ],
        [
            'mobile_number.integer' => 'Invalid mobile number',
            'mobile_number.digits' => 'Invalid mobile number',
        ]);

        $user = auth()->user();
        $company_information = new ShopOption;
        $company_information->key = 'company_information';
        $data['store_name'] = $request->store_name;
        $data['email'] = $request->email;
        $data['mobile_number'] = $request->mobile_number;
        $data['address'] = $request->address;
        $data['city'] = $request->city;
        $data['state'] = $request->state;
        $data['country'] = $request->country;
        $data['zip_code'] = $request->zip_code;
        $data['website'] = $request->website;

        $company_information->value = json_encode($data);
        $company_information->shop_id = $request->shop_id;
        
    if($company_information->save())
      {
        Session::flash('success', trans('Your custom privacy policy has been successfully generated ....!!!'));
        return redirect()->route('seller.privacy-policy-generator.show', $company_information->id);
      }
      else {
        Session::flash('error', trans('Something Went Wrong....!!!'));
      }
    }

   
    public function show($id)
    {
       $info=ShopOption::where('shop_id',current_shop_id())->findorFail($id);
       $company_info = json_decode($info->value ?? '');

       return view('seller.store.privacy_policy_generator.show', compact('company_info'));
    
    }
   
}
