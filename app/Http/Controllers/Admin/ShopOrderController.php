<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Settlement;
use App\Rules\IsValidDomain;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ShopOrderController extends Controller
{
    protected $request;

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $type = $request->type ?? 'all';
        $posts = Order::with('Shop');

        if ($type == "all") {
            $posts =  $posts->latest()->paginate(40);  
        }
        else{
            $posts = $posts->where('status', $type)->latest()->paginate(40);
        }

        if (!empty($request->src) && $request->term == "order_id") {
            $this->request = $request->src;
            $posts = Order::with('Shop')->where('order_no', $this->request)->latest()->paginate(40);
        }

        // $posts = Order::with('Shop')->latest()->paginate(40);


        $all = Order::count();
        $pending = Order::where('status', 'pending')->count();
        $processing = Order::where('status', 'processing')->count();
        $completed= Order::where('status', 'completed')->count();
        $deliverd = Order::where('status', 'delivered')->count();

        return view('admin.shop.orders.index', compact('posts','request','all','pending','processing','completed','deliverd'));
    }

    public function seller(Request $request)
    {

        $type = $request->type ?? 'all';
        $posts = Order::whereHas('Shop', function ($query) {
            $query->where('shop_type', 'seller');
        });

        if ($type == "all") {
            $posts =  $posts->latest()->paginate(40);  
        }
        else{
            $posts = $posts->where('status', $type)->latest()->paginate(40);
        }

        if (!empty($request->src) && $request->term == "order_id") {
            $this->request = $request->src;
            $posts = Order::whereHas('Shop', function ($query) {
                $query->where('shop_type', 'seller');
            })->where('order_no', $this->request)->latest()->paginate(40);
        }

        // $posts = Order::with('Shop')->latest()->paginate(40);


        $all = Order::whereHas('Shop', function ($query) {
            $query->where('shop_type', 'seller');
        })->count();
        $pending = Order::where('status', 'pending')->whereHas('Shop', function ($query) {
            $query->where('shop_type', 'seller');
        })->count();
        $processing = Order::where('status', 'processing')->whereHas('Shop', function ($query) {
            $query->where('shop_type', 'seller');
        })->count();
        $completed= Order::where('status', 'completed')->whereHas('Shop', function ($query) {
            $query->where('shop_type', 'seller');
        })->count();
        $deliverd = Order::where('status', 'delivered')->whereHas('Shop', function ($query) {
            $query->where('shop_type', 'seller');
        })->count();

        return view('admin.shop.orders.index', compact('posts','request','all','pending','processing','completed','deliverd'));
    }

    public function supplier(Request $request)
    {

        $type = $request->type ?? 'all';
        $posts = Order::whereHas('Shop', function ($query) {
            $query->where('shop_type', 'supplier');
        });

        if ($type == "all") {
            $posts =  $posts->latest()->paginate(40);  
        }
        else{
            $posts = $posts->where('status', $type)->latest()->paginate(40);
        }

        if (!empty($request->src) && $request->term == "order_id") {
            $this->request = $request->src;
            $posts = Order::whereHas('Shop', function ($query) {
                $query->where('shop_type', 'supplier');
            })->where('order_no', $this->request)->latest()->paginate(40);
        }

        // $posts = Order::with('Shop')->latest()->paginate(40);


        $all = Order::whereHas('Shop', function ($query) {
            $query->where('shop_type', 'supplier');
        })->count();
        $pending = Order::where('status', 'pending')->whereHas('Shop', function ($query) {
            $query->where('shop_type', 'supplier');
        })->count();
        $processing = Order::where('status', 'processing')->whereHas('Shop', function ($query) {
            $query->where('shop_type', 'supplier');
        })->count();
        $completed= Order::where('status', 'completed')->whereHas('Shop', function ($query) {
            $query->where('shop_type', 'supplier');
        })->count();
        $deliverd = Order::where('status', 'delivered')->whereHas('Shop', function ($query) {
            $query->where('shop_type', 'supplier');
        })->count();

        return view('admin.shop.orders.supplier', compact('posts','request','all','pending','processing','completed','deliverd'));
    }

    public function reseller(Request $request)
    {

        $type = $request->type ?? 'all';
        $posts = Order::whereHas('Shop', function ($query) {
            $query->where('shop_type', 'reseller');
        });

        if ($type == "all") {
            $posts =  $posts->latest()->paginate(40);  
        }
        else{
            $posts = $posts->where('status', $type)->latest()->paginate(40);
        }

        if (!empty($request->src) && $request->term == "order_id") {
            $this->request = $request->src;
            $posts = Order::whereHas('Shop', function ($query) {
                $query->where('shop_type', 'reseller');
            })->where('order_no', $this->request)->latest()->paginate(40);
        }

        // $posts = Order::with('Shop')->latest()->paginate(40);


        $all = Order::whereHas('Shop', function ($query) {
            $query->where('shop_type', 'reseller');
        })->count();
        $pending = Order::where('status', 'pending')->whereHas('Shop', function ($query) {
            $query->where('shop_type', 'reseller');
        })->count();
        $processing = Order::where('status', 'processing')->whereHas('Shop', function ($query) {
            $query->where('shop_type', 'reseller');
        })->count();
        $completed= Order::where('status', 'completed')->whereHas('Shop', function ($query) {
            $query->where('shop_type', 'reseller');
        })->count();
        $deliverd = Order::where('status', 'delivered')->whereHas('Shop', function ($query) {
            $query->where('shop_type', 'reseller');
        })->count();

        return view('admin.shop.orders.reseller', compact('posts','request','all','pending','processing','completed','deliverd'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $info = Order::with('order_item', 'customer', 'order_content', 'shipping_info', 'gateway', 'agent_avathar_details', 'agent_details')->findorFail($id);
        $order_content = json_decode($info->order_content->value ?? '');
       
        return view('admin.shop.orders.show', compact('info', 'order_content'));

    }

}