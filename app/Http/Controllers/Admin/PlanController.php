<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Shop;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Subscriber;
use Illuminate\Support\Facades\Session;


class PlanController extends Controller
{
    protected $id;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!Auth()->user()->can('plan.list')) {
            abort(401);
        }
        $posts = Plan::withCount('active_users')->where('status','!=','-1')->latest()->get();
        return view('admin.plan.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth()->user()->can('plan.create')) {
            abort(401);
        }
        return view('admin.plan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->shop_type == 'Seller'){
            $free_trail_plan = Plan::where('is_trial',1)->where('shop_type','Seller')->first();
        }
        if($request->shop_type == 'Supplier'){
            $free_trail_plan = Plan::where('is_trial',1)->where('shop_type','Supplier')->first();
        }
        if($request->shop_type == 'Reseller'){
            $free_trail_plan = Plan::where('is_trial',1)->where('shop_type','Reseller')->first();
        } 
        if($free_trail_plan && $request->is_trial == 1 && $request->status == 1){
            return response()->json(['errors' => ['Trial Plan is already enabled for this shop type! So Please select "No" in Is Trial? to Enable this plan']], 302);
        }
        
        $plan_data['product_limit'] = $request->product_limit ?? "";
        $plan_data['customer_limit'] = $request->customer_limit ?? "";
        $plan_data['agent_limit'] = $request->agent_limit ?? "";
        $plan_data['storage'] = $request->storage ?? "";

        if($request->shop_type == 'Seller'){
            $plan_data['inventory'] = $request->inventory;
            $plan_data['pos'] = $request->pos;
            $plan_data['whatsapp'] = $request->whatsapp;
            $plan_data['custom_css'] = $request->custom_css;
            $plan_data['custom_js'] = $request->custom_js;
            $plan_data['live_support'] = $request->live_support;
            $plan_data['customer_panel'] = $request->customer_panel;
            $plan_data['pwa'] = $request->pwa;
            $plan_data['qr_code'] = $request->qr_code;
            $plan_data['facebook_pixel'] = $request->facebook_pixel;
            $plan_data['gtm'] = $request->gtm;
            $plan_data['google_analytics'] = $request->google_analytics;
            $plan_data['custom_domain'] = $request->custom_domain;
        } else if($request->shop_type == 'Supplier') {
            $plan_data['inventory'] = $request->inventory;
            $plan_data['pos'] = "false";
            $plan_data['whatsapp'] = "false";
            $plan_data['custom_css'] = "false";
            $plan_data['custom_js'] = "false";
            $plan_data['live_support'] = "false";
            $plan_data['customer_panel'] = "false";
            $plan_data['custom_domain'] = "false";
            $plan_data['pwa'] = "false";
            $plan_data['qr_code'] = "false";
            $plan_data['gtm'] = "false";
            $plan_data['facebook_pixel'] = "false";
            $plan_data['google_analytics'] = "false";
        } else if ($request->shop_type == 'Reseller') {
            $plan_data['inventory'] = "false";
            $plan_data['pos'] = "false";
            $plan_data['whatsapp'] = "false";
            $plan_data['custom_css'] = "false";
            $plan_data['custom_js'] = "false";
            $plan_data['live_support'] = "false";
            $plan_data['customer_panel'] = $request->customer_panel;
            $plan_data['pwa'] = $request->pwa;
            $plan_data['qr_code'] = $request->qr_code;
            $plan_data['facebook_pixel'] = $request->facebook_pixel;
            $plan_data['gtm'] = $request->gtm;
            $plan_data['google_analytics'] = $request->google_analytics;
            $plan_data['custom_domain'] = $request->custom_domain;
        }

        $plan_data['location_limit'] = $request->location_limit;
        $plan_data['category_limit'] = $request->category_limit;
        $plan_data['brand_limit'] = $request->brand_limit;
        $plan_data['variation_limit'] = $request->variation_limit;

       
        $plan = new Plan();
        $plan->shop_type = $request->shop_type;
        $plan->name = $request->name;
        $plan->description = $request->description;
        $plan->price = $request->price;
        $plan->days = $request->days;
        $plan->data = json_encode($plan_data);
        $plan->status = $request->status;
        $plan->featured = $request->featured;
        $plan->is_default = 0;
        $plan->is_trial = $request->is_trial ?? 0;
        $plan->save();

        if ($request->has('file')) {
            $plan->deleteFile('image');
            $plan_data['image'] = $plan->uploadFile($request->file, 'admin/plan/'. $plan->id . '/image', 'image');
        }

        $plan->data = json_encode($plan_data);
        $plan->save();

        return response()->json(['Plan Created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth()->user()->can('plan.show')) {
            abort(401);
        }
        $this->id = $id;

        $posts = Shop::with('user')->whereHas('subscription', function ($q) {
            return $q->where('plan_id', $this->id);
        })->with('subscription')->latest()->paginate(40);
        return view('admin.plan.show', compact('posts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth()->user()->can('plan.edit')) {
            abort(401);
        }
        $info = Plan::find($id);
        $plan_info = json_decode($info->data);
        return view('admin.plan.edit', compact('info', 'plan_info'));
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
        $plan = Plan::findorFail($id);
        $free_trail_plan = Plan::where('is_trial',1)->where('shop_type',$plan->shop_type)->where('id', '<>', $id)->first();
        if(isset($free_trail_plan) && $request->is_trial == 1 && $request->status == 1){
            return response()->json(['errors' => ['Trial Plan is already enabled for this shop type! So Please select "No" in Is Trial? to Enable this plan']], 302);
        }

        $plan_data['product_limit'] = $request->product_limit ?? "";
        $plan_data['customer_limit'] = $request->customer_limit ?? "";
        $plan_data['agent_limit'] = $request->agent_limit ?? "";
        $plan_data['storage'] = $request->storage ?? "";

        if($plan->shop_type == 'Seller' || $plan->shop_type == NULL){
            $plan_data['inventory'] = $request->inventory;
            $plan_data['pos'] = $request->pos;
            $plan_data['whatsapp'] = $request->whatsapp;
            $plan_data['custom_css'] = $request->custom_css;
            $plan_data['custom_js'] = $request->custom_js;
            $plan_data['live_support'] = $request->live_support;
            $plan_data['customer_panel'] = $request->customer_panel;
            $plan_data['pwa'] = $request->pwa;
            $plan_data['qr_code'] = $request->qr_code;
            $plan_data['facebook_pixel'] = $request->facebook_pixel;
            $plan_data['gtm'] = $request->gtm;
            $plan_data['google_analytics'] = $request->google_analytics;
            $plan_data['custom_domain'] = $request->custom_domain;
        } else if($plan->shop_type == 'Supplier') {
            $plan_data['inventory'] = $request->inventory;
            $plan_data['pos'] = "false";
            $plan_data['whatsapp'] = "false";
            $plan_data['custom_css'] = "false";
            $plan_data['custom_js'] = "false";
            $plan_data['live_support'] = "false";
            $plan_data['customer_panel'] = "false";
            $plan_data['custom_domain'] = "false";
            $plan_data['pwa'] = "false";
            $plan_data['qr_code'] = "false";
            $plan_data['gtm'] = "false";
            $plan_data['facebook_pixel'] = "false";
            $plan_data['google_analytics'] = "false";
        } else if ($plan->shop_type == 'Reseller') {
            $plan_data['inventory'] = "false";
            $plan_data['pos'] = "false";
            $plan_data['whatsapp'] = "false";
            $plan_data['custom_css'] = "false";
            $plan_data['custom_js'] = "false";
            $plan_data['live_support'] = "false";
            $plan_data['customer_panel'] = $request->customer_panel;
            $plan_data['pwa'] = $request->pwa;
            $plan_data['qr_code'] = $request->qr_code;
            $plan_data['facebook_pixel'] = $request->facebook_pixel;
            $plan_data['gtm'] = $request->gtm;
            $plan_data['google_analytics'] = $request->google_analytics;
            $plan_data['custom_domain'] = $request->custom_domain;
        }

        $plan_data['location_limit'] = $request->location_limit;
        $plan_data['category_limit'] = $request->category_limit;
        $plan_data['brand_limit'] = $request->brand_limit;
        $plan_data['variation_limit'] = $request->variation_limit;


        if ($request->has('file')) {
            $plan->deleteFile('image');
            $plan_data['image'] = $plan->uploadFile($request->file, 'admin/plan/'. $plan->id . '/image', 'image');
        }

        $plan->name = $request->name;
        $plan->description = $request->description;
        $plan->price = $request->price;
        $plan->days = $request->days;
        $plan->data = json_encode($plan_data);
        $plan->status = $request->status;
        $plan->featured = $request->featured;
        $plan->is_trial = $request->is_trial ?? 0;
        $plan->is_default = 0;
        $plan->save();



        Shop::whereHas('subscription', function($q) use($plan){
            $q->where('plan_id', $plan->id);
        })->update(['data' => $plan->data]);

        return response()->json(['Plan Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if (!empty($request->type)) {
        //     if ($request->type == 'delete') {
        //         foreach ($request->ids as $row) {
        //             Plan::destroy($row);
        //         }
        //     }
        // }
        $plan = Plan::find($id);
        if($plan){
            $subscription = Subscription::where('plan_id',$plan->id)->first();
            if($subscription){
                Session::flash('error', 'Plan is already in use');
            } else {
            $plan->status = -1;
            $plan->save();
                Session::flash('success', 'Plan Deleted Successfully');
            }
        }
        return back();
    }
}
