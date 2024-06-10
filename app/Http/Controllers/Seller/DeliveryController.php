<?php

namespace App\Http\Controllers\Seller;

use App\Models\Customer;
use App\Exports\AgentExport;
use Illuminate\Http\Request;
use App\Models\DeliveryAgent;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Support\Facades\Session;

class DeliveryController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {

    $posts = Customer::where('shop_id', current_shop_id());

    if ($request->src) {
      $posts = $posts->where($request->type, 'LIKE', '%' . $request->src . '%');
    }

    $src = $request->src ?? '';

    $posts = $posts->with('agent_preview')->whereHas('agent_preview')->latest()->paginate(20);

    return view('seller.delivery.index', compact('posts', 'src'));
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('seller.delivery.create');
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

    $posts_count = Customer::where('shop_id', $shop_id)->where('user_type', 'agent')->count();
    if (user_plan_limit('agent_limit', $posts_count)) {

      $error['errors']['error'] = 'Maximum agents limit exceeded';
      return response()->json($error, 401);
    }

    $request->validate([
      'user_image' => 'required|max:1000|image',
      'first_name' => 'required|max:20',
      'last_name' => 'required|max:20',
      'email' => 'required|email_address|max:50',
      'password' => 'required|min:6|without_spaces',
      'user_id' => 'required|max:50',
      'user_id_image' => 'required|max:1000|image',
      // 'mobile_number' => 'required|max:10',
      'mobile' =>  [
        Rule::unique('customers')
          ->where('mobile', $request->mobile)
          ->where('shop_id',  $shop_id)
          ->where('user_type',  'agent'),
      ],
      'email' =>  [
        Rule::unique('customers')
          ->where('email', $request->email)
          ->where('shop_id',  $shop_id)
          ->where('user_type',  'agent'),
      ],
    ]);

    $customer_list =  Customer::where('shop_id', $shop_id)->get();

    if (count($customer_list) > 0) {
      foreach ($customer_list as $customer) {
        $agent = DeliveryAgent::where('customer_id', $customer->id)->get();
        if (count($agent) > 0) {
          foreach ($agent  as $age) {
            $get_agent = DeliveryAgent::where('agent_id',  $request->user_id)->first();
            if (empty($get_agent)) {
              $fileName = time() . 'avathar' . '.' . $request->user_image->extension();
              $path = 'uploads/' . current_shop_id() . '/' . date('y/m');
              $request->user_image->move($path, $fileName);
              $name = $path . '/' . $fileName;

              $user = new Customer;
              $user->first_name = $request->first_name;
              $user->last_name = $request->last_name;
              $user->email = $request->email;
              $user->mobile = $request->mobile;
              $user->shop_id = $shop_id;
              $user->user_type = 'agent'; //agent status add to customer table
              $user->password = Hash::make($request->password);
              $user->save();


              $fileNamee = time() . 'image_id' . '.' . $request->user_id_image->extension();
              $pathh = 'uploads/' . current_shop_id() . '/' . date('y/m');
              $request->user_id_image->move($pathh, $fileNamee);
              $image_id = $pathh . '/' . $fileNamee;


              $data = new DeliveryAgent;
              $data->customer_id = $user->id;
              $data->avathar = $name;
              $data['agent_id'] = $request->user_id;
              $data->image_id = $image_id;
              $data['status'] = $request->status;
              $data->save();

              return response()->json([trans('Delivery Agent Created Successfully')]);
            } else {
              return response()->json(['errors' => ['Agent ID Already Exist']], 400);
              // return back();
            }
          }
        }
      }
    } else {
      $fileName = time() . 'avathar' . '.' . $request->user_image->extension();
      $path = 'uploads/' . current_shop_id() . '/' . date('y/m');
      $request->user_image->move($path, $fileName);
      $name = $path . '/' . $fileName;

      $user = new Customer;
      $user->first_name = $request->first_name;
      $user->last_name = $request->last_name;
      $user->email = $request->email;
      $user->mobile = $request->mobile;
      $user->shop_id = $shop_id;
      $user->user_type = 'agent'; //agent status add to customer table
      $user->password = Hash::make($request->password);
      $user->save();


      $fileNamee = time() . 'image_id' . '.' . $request->user_id_image->extension();
      $pathh = 'uploads/' . current_shop_id() . '/' . date('y/m');
      $request->user_id_image->move($pathh, $fileNamee);
      $image_id = $pathh . '/' . $fileNamee;


      $data = new DeliveryAgent;
      $data->customer_id = $user->id;
      $data->avathar = $name;
      $data['agent_id'] = $request->user_id;
      $data->image_id = $image_id;
      $data['status'] = $request->status;
      $data->save();

      return response()->json([trans('Delivery Agent Created Successfully')]);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {

    $delivery = Customer::where('shop_id', current_shop_id())->whereHas('preview')->findorFail($id);
    if (file_exists($delivery->name)) {
      unlink($delivery->name);
    }
    $delivery->delete();

    return back();
  }

  #####  delivery edit function #####
  public function edit($id)
  {
    $info = Customer::where('shop_id', current_shop_id())->whereHas('preview')->findOrFail($id);
    return view('seller.delivery.edit', compact('info'));
  }


  #####  delivery update function #####

  public function update(Request $request, $id)
  {
    $shop_id = current_shop_id();
    $posts_count = Customer::where('shop_id', current_shop_id())->findOrFail($id);

    $request->validate([
      'user_image' => 'max:1000|image',
      'first_name' => 'required|max:20',
      'last_name' => 'required|max:20',
      'email' => 'required|email_address|max:50',
      // 'mobile' => 'required|max:10',
      'user_id' => 'required|max:50',
      'user_id_image' => 'max:1000|image',
      'email' =>  [
        Rule::unique('customers')
          ->whereNotIn('id', [$id])
          ->where('email', $request->email)
          ->where('shop_id',  $shop_id)
          ->where('user_type',  'agent'),
      ],
    ]);
    if ($request->mobile) {
      $request->validate([
        'mobile' =>  [
          Rule::unique('customers')
            ->whereNotIn('id', [$id])
            ->where('mobile', $request->mobile)
            ->where('shop_id',  $shop_id)
            ->where('user_type',  'agent'),
        ],
      ]);
    }

    if ($request->change_password) {
      $request->validate([
        'password' => 'required|min:6|without_spaces',
      ]);
    }


    $user =  Customer::where('shop_id', $shop_id)->findorFail($id);
    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;
    $user->email = $request->email;
    $user->mobile = $request->mobile;
    $user->shop_id = $shop_id;
    if ($request->change_password) {
      $user->password = Hash::make($request->password);
    }

    $user->save();

    $data = DeliveryAgent::where('customer_id', $user->id)->first();

    if (!isset($data)) {

      $data = new DeliveryAgent();
    }


    if ($request->has('user_image')) {
      $fileName =  time() . 'avathar' . '.' . $request->user_image->extension();
      $path = 'uploads/' . current_shop_id() . '/' . date('y/m');
      $request->user_image->move($path, $fileName);
      $name = $path . '/' . $fileName;
      $data->avathar = $name;
    }

    if ($request->has('user_id_image')) {
      $fileNamee =  time() . 'image_id' . '.' . $request->user_id_image->extension();
      $pathh = 'uploads/' . current_shop_id() . '/' . date('y/m');
      $request->user_id_image->move($pathh, $fileNamee);
      $image_id = $pathh . '/' . $fileNamee;
      $data->image_id = $image_id;
    }

    if ($request->has('user_id')) {
      if ($data->agent_id == $request->user_id) {
        $data->status = $request->status;
        $data->save();
        return response()->json([trans('Delivery Agent Updated Successfully')]);
      } else {
        $get_agent = DeliveryAgent::where('agent_id',  $request->user_id)->first();
        if (empty($get_agent)) {
          $data['agent_id'] = $request->user_id;
          $data->save();
        } else {
          return response()->json(['errors' => ['Agent ID Already Exist']], 400);
        }
      }
    }

    $data->customer_id = $user->id;
    // $data['agent_id'] = $request->user_id;
    $data->status = $request->status;
    $data->save();

    return response()->json([trans('Delivery Agent Updated Successfully')]);
  }
  public function agent_view($id)
  {
    $info = Customer::where('shop_id', current_shop_id())->with('preview')->withCount('agent_orders', 'agent_orders_ready_pickup', 'agent_orders_processing', 'agent_orders_complete')->findorFail($id);
    $earnings = \App\Models\Order::where('agent_id', $id)->where('payment_status', 1)->sum('total');
    $orders = \App\Models\Order::where('agent_id', $id)->with('gateway')->withCount('order_item')->latest()->paginate(20);
    return view('seller.delivery.show', compact('info', 'earnings', 'orders'));
  }

  public function agent_report_download(Request $request)
  {

    $agent_id = $request->id;
    $from_date = $request->from_date;
    $to_date = $request->to_date;

    return Excel::download(new AgentExport($agent_id, $from_date, $to_date), 'agent.xlsx');
  }

  public function login($id)
  {
    if (current_shop_type() != 'supplier') {
      if (user_plan_access('customer_panel') !== true) {
        return back();
      }
    }

    $find_agent = Customer::where('shop_id', current_shop_id())->findorFail($id);

    // Auth::logout();
    Auth::guard('customer')->loginUsingId($find_agent->id);
    return redirect('seller/agent/dashboard');
  }

  public function agent_dashboard()
  {
    if (!Session::has('locale')) {
      set_language();
    }
    if (Auth::guard('customer')->check()) {
      $user = Auth::guard('customer')->user();
      $shop_id = $user->shop_id;
      $pendings = Order::where('shop_id', $shop_id)->where('status', 'ready-for-pickup')->where('agent_id', $user->id)->count();
      $pickup = Order::where('shop_id', $shop_id)->where('status', 'picked_up')->where('agent_id', $user->id)->count();
      $completed = Order::where('shop_id', $shop_id)->whereIn('status', ['completed', 'delivered'])->where('agent_id', $user->id)->count();

      return view('agent_dashboard', compact('pendings',  'pickup', 'completed', 'user'));
    }
  }
  public function agent_order()
  {

    $user = Auth::guard('customer')->user();
    $types = [
      'ready-for-pickup',
      'completed',
      'picked_up',
      'delivered'
    ];

    $orders = Order::where('shop_id',  $user->shop_id);

    $orders = $orders->whereIn('status', $types)
      ->with('customer')
      ->withCount('order_items')->where('agent_id', $user->id)
      ->orderBy('id', 'DESC')
      ->paginate(40);

    return view('agent.orders.index', compact('orders'));
  }

      /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'method' => 'required'
        ],
        [
            'method.required' => 'Please Select Action'
        ]); 
        if ($request->ids != '') {
            foreach ($request->ids as $id) {
              
                    $categorys = DeliveryAgent::where('customer_id', $id)->first();
                    $categorys->status = $request->method;
                    $categorys->save();
                
            }
        } else {

            return response()->json(['errors' => ['Agent id not selected']], 400);
        }

        return response()->json(['Success']);
      
    }
}
