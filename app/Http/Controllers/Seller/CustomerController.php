<?php

namespace App\Http\Controllers\Seller;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{


  public function __construct()
  {
    if (env('MULTILEVEL_CUSTOMER_REGISTER') != true) {
      abort(404);
    }
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $shop_id = current_shop_id();
    if (current_shop_type() != 'supplier') {
      $posts = Customer::where('shop_id', $shop_id)->where('user_type', 'customer'); //user type for customer check
    } else {
      $posts = Customer::whereHas('orders', function ($query) {
        $query->where('shop_id', current_shop_id());
      });
    }
    if ($request->src) {
      $posts = $posts->where($request->type, 'LIKE', '%' . $request->src . '%');
    }
    $src = $request->src ?? '';
    $posts = $posts->withCount('orders')->orderBy('orders_count', 'DESC')->latest()->paginate(20);

    return view('seller.customer.index', compact('posts', 'src'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('seller.customer.create');
  }

  public function user(Request $request)
  {
    $user = Customer::where('shop_id', current_shop_id())->where('email', $request->email)->first();

    if (!empty($user)) {
      return $user->id;
    } else {
      return response()->json(trans('Customer Not Found'), 404);
    }
  }

  public function login($id)
  {
    if (user_plan_access('customer_panel') !== true) {
      return back();
    }

    $user = Customer::where('shop_id', current_shop_id())->findorFail($id);
    Auth::logout();
    Auth::guard('customer')->loginUsingId($user->id);

    return redirect('/user/dashboard');
  }
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {

    $shop_id = current_shop_id();
    $posts_count = Customer::where('shop_id', $shop_id)->where('user_type', 'customer')->count();
    if (user_plan_limit('customer_limit', $posts_count)) {

      Session::flash('success', trans('Maximum customers limit exceeded'));
      return back();
    }


    $request->validate([
      'email' => 'required|email_address|max:50',
      'first_name' => 'required|max:20',
      'password' => 'required|min:6|without_spaces',
      'email' =>  [
        Rule::unique('customers')
          ->where('email', $request->email)
          ->where('shop_id',  $shop_id)
          ->where('user_type',  'customer'),
      ],
    ]);

    $user = new Customer;
    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;
    $user->email = $request->email;
    $user->shop_id = $shop_id;
    $user->password = Hash::make($request->password);
    $user->status =  $request->status ?? 1;
    $user->save();

    //return response()->json([trans('User Created Successfully')]);
    Session::flash('success', trans('User Created Successfully'));
    return back();
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    if (current_shop_type() != 'supplier') {
      $info = Customer::where('shop_id', current_shop_id())->withCount('orders', 'orders_complete', 'orders_processing')->findorFail($id);
    } else {
      $info = Customer::whereHas('orders', function ($query) {
        $query->where('shop_id', current_shop_id());
      })->withCount('orders', 'orders_complete', 'orders_processing')->findorFail($id);
    }
    $earnings = Order::where('customer_id', $id)->where('shop_id', current_shop_id())->where('payment_status', 1)->sum('total');
    $orders = Order::where('customer_id', $id)->where('shop_id', current_shop_id())->with('PaymentMethod', 'gateway')->withCount('order_item')->latest()->paginate(20);
    return view('seller.customer.show', compact('info', 'earnings', 'orders'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    if (current_shop_type() != 'supplier') {
      $info = Customer::where('shop_id', current_shop_id())->findorFail($id);
    } else {
      $info = Customer::whereHas('orders', function ($query) {
        $query->where('shop_id', current_shop_id());
      })->findorFail($id);
    }
    return view('seller.customer.edit', compact('info'));
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
    $shop_id = current_shop_id();
    $request->validate([
      'email' => 'required|max:50|email_address',
      'first_name' => 'required|max:20',
      'email' =>  [
        Rule::unique('customers')
          ->whereNotIn('id', [$id])
          ->where('email', $request->email)
          ->where('shop_id',  $shop_id)
          ->where('user_type',  'customer'),
      ],
    ]);


    if ($request->change_password) {
      $request->validate([
        'password' => 'required|min:6|without_spaces',
      ]);
    }
    if (current_shop_type() != 'supplier') {
      $user = Customer::where('shop_id', current_shop_id())->findorFail($id);
    } else {
      $user = Customer::whereHas('orders', function ($query) {
        $query->where('shop_id', current_shop_id());
      })->findorFail($id);
    }
    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;
    $user->email = $request->email;
    if ($request->change_password) {
      $user->password = Hash::make($request->password);
    }
    $user->status =  $request->status ?? 1;

    $user->save();

    return response()->json([trans('User Updated Successfully')]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {

    if ($request->ids != '') {
      foreach ($request->ids as $id) {
        $coupon = Customer::findOrFail($id);
        $coupon->status = $request->method;
        $coupon->save();
      }
    } else {

      return response()->json(['errors' => ['Customer id not selected']], 400);
    }
    return response()->json(['Success']);

    //  if ($request->type=='delete') {
    //     foreach ($request->ids as $key => $id) {
    //         $user=  Customer::where('shop_id', current_shop_id())->findorFail($id);
    //         $user->delete();
    //     }
    //     return response()->json([trans('Customer Deleted')]);
    // }


  }
}
