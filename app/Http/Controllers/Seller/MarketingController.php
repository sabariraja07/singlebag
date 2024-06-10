<?php

namespace App\Http\Controllers\Seller;

use App\Models\ShopOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class MarketingController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request->type == 'google-analytics') {


            if (user_plan_access('google_analytics') == false) {
                $msg = 'This module did not support your subscription.';
                $error['errors']['error'] = $msg;
                return response()->json($error, 401);
            }


            $validatedData = $request->validate([
                'ga_measurement_id' => 'required|max:50',
                'analytics_view_id' => 'required|max:50',
                'file' => 'mimes:json|max:50',

            ]);

            $google = ShopOption::where('shop_id', current_shop_id())->where('key', 'google-analytics')->first();
            if (empty($google)) {
                $google = new ShopOption;
                $google->shop_id = current_shop_id();
                $google->key = "google-analytics";
            }

            $data['ga_measurement_id'] = $request->ga_measurement_id;
            $data['analytics_view_id'] = $request->analytics_view_id;

            $google->value = json_encode($data);
            $google->status = $request->status;
            $google->save();

            if ($request->file) {
                $path = 'uploads/' . $google->shop_id . '/';
                $fileName = 'service-account-credentials.' . $request->file->extension();
                $request->file->move($path, $fileName);
            }

            Session::flash('success', trans('Google Analytics Updated !!'));
            return back();
        }

        if ($request->type == 'tag-manager') {
            if (user_plan_access('gtm') == false) {
                $msg = trans('This module did not support your subscription.');
                $error['errors']['error'] = $msg;
                return response()->json($error, 401);
            }

            $validatedData = $request->validate([
                'tag_id' => 'required|max:50',
            ]);

            $tag_manager = ShopOption::where('shop_id', current_shop_id())->where('key', 'tag_manager')->first();
            if (empty($tag_manager)) {
                $tag_manager = new ShopOption;
                $tag_manager->shop_id = current_shop_id();
                $tag_manager->key = "tag_manager";
            }

            $tag_manager->value = $request->tag_id;
            $tag_manager->status = $request->status;
            $tag_manager->save();

            Session::flash('success', trans('Google Tag Manager Updated !!'));
            return back();
        }



        if ($request->type == 'whatsapp') {
            if (user_plan_access('whatsapp') == false) {
                $msg =  trans('This module did not support your subscription.');
                $error['errors']['error'] = $msg;
                return response()->json($error, 401);
            }

            $validatedData = $request->validate([
                'number' => 'required|max:20',
                'shop_page_pretext' => 'required|max:50',
                'other_page_pretext' => 'required|max:50',

            ]);

            $google = ShopOption::where('shop_id', current_shop_id())->where('key', 'whatsapp')->first();
            if (empty($google)) {
                $google = new ShopOption;
                $google->shop_id = current_shop_id();
                $google->key = "whatsapp";
            }
            $data['phone_number'] = $request->number;
            $data['shop_page_pretext'] = $request->shop_page_pretext;
            $data['other_page_pretext'] = $request->other_page_pretext;


            $google->value = json_encode($data);
            $google->status = $request->status;
            $google->save();

            Session::flash('success', trans('Whatsapp Settings Updated !!'));
            return back();
        }
        if ($request->type == 'fb_pixel') {
            if (user_plan_access('facebook_pixel') == false) {
                $msg = trans('This module did not support your subscription.');
                $error['errors']['error'] = $msg;
                return response()->json($error, 401);
            }

            $validatedData = $request->validate([
                'pixel_id' => 'required|max:40',


            ]);

            $pixel = ShopOption::where('shop_id', current_shop_id())->where('key', 'fb_pixel')->first();
            if (empty($pixel)) {
                $pixel = new ShopOption;
                $pixel->shop_id = current_shop_id();
                $pixel->key = "fb_pixel";
            }



            $pixel->value = $request->pixel_id;
            $pixel->status = $request->status;
            $pixel->save();

            Session::flash('success', trans('Facebook Pixel Settings Updated !!'));
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $param
     * @return \Illuminate\Http\Response
     */
    public function show($param)
    {
        if ($param == 'facebook-pixel') {
            $fb_pixel = ShopOption::where('shop_id', current_shop_id())->where('key', 'fb_pixel')->first();

            return view('seller.marketing.facebook', compact('fb_pixel'));
        }

        if ($param == 'google-analytics') {
            $google = ShopOption::where('shop_id', current_shop_id())->where('key', 'google-analytics')->first();
            $info = json_decode($google->value ?? '');
            return view('seller.marketing.google', compact('google', 'info'));
        }

        if ($param == 'tag-manager') {
            $tag = ShopOption::where('shop_id', current_shop_id())->where('key', 'tag_manager')->first();
            $info = json_decode($tag->value ?? '');
            return view('seller.marketing.tag', compact('tag', 'info'));
        }

        if ($param == 'whatsapp') {
            $whatsapp = ShopOption::where('shop_id', current_shop_id())->where('key', 'whatsapp')->first();
            $json = json_decode($whatsapp->value ?? '');
            return view('seller.marketing.whatsapp', compact('whatsapp', 'json'));
        }

        abort(404);
    }
}
