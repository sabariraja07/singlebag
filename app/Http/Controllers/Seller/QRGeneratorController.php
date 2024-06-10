<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShopOption;
use Illuminate\Support\Facades\Session;
class QRGeneratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {       
        return view('seller.store.qr_generator.create');
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      if($request->data_type == 'website'){
          $request->validate([
            'website' => 'required|max:30',
        ],
        [
            'mobile_number.integer' => 'Invalid mobile number',
            'mobile_number.digits' => 'Invalid mobile number',
        ]);
      } else if($request->data_type == 'phone'){
            $request->validate([
              'phone' => 'required|integer|digits:10',
          ],
          [
              'phone.integer' => 'Invalid mobile number',
              'phone.digits' => 'Invalid mobile number',
          ]);
      } else if($request->data_type == 'sms'){
          $request->validate([
            'mobile_number' => 'required|integer|digits:10',
            'message' => 'required|max:160',
          ],
          [
              'mobile_number.integer' => 'Invalid mobile number',
              'mobile_number.digits' => 'Invalid mobile number',
          ]);
        } else if($request->data_type == 'plain_text'){
          $request->validate([
            'plain_text' => 'required',
          ]);
      }

        $company_information = new ShopOption;
        $company_information->key = 'company_information';
        $data['data_type'] = $request->data_type ?? '';
        $data['mobile_number'] = $request->mobile_number ?? '';
        $data['phone'] = $request->phone ?? '';
        $data['message'] = $request->message ?? '';
        $data['website'] = $request->website ?? '';
        $data['plain_text'] = $request->plain_text ?? '';

        $company_information->value = json_encode($data);
        $company_information->shop_id = $request->shop_id;
        
    if($company_information->save())
      {
        Session::flash('success', trans('Your custom QR code has been successfully created ....!!!'));
        return response()->json(['redirect' => route('seller.qr-generator.show', $company_information->id),'message' => 'Your custom QR code has been successfully created ....!!!']);
      }
      else {
        Session::flash('error', trans('Something Went Wrong....!!!'));
      }
    }

   
    public function show($id)
    {
       $info=ShopOption::where('shop_id',current_shop_id())->findorFail($id);
       $company_info = json_decode($info->value ?? '');

       return view('seller.store.qr_generator.show', compact('company_info'));
    
    }
   
}