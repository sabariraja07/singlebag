<?php

namespace App\Http\Controllers\API\V1\Seller;

use App\Models\Order;
use App\Models\Stock;
use Barryvdh\DomPDF\PDF;
use App\Models\ShopOption;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Mail\CustomerOrderMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use ApiResponser;
    protected $user_id;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $shop_id = current_shop_id();
        $type = $request->type ?? 'all';

        $orders = Order::where('shop_id', $shop_id);

        if (!empty($request->term)) {
            $orders = $orders->where('order_no', $request->term)->where('status', $type);
        }

        if (!empty($request->payment_status) && !empty($request->status) && !empty($request->start)  && !empty($request->end)) {
            $arr['status'] = $request->status;
            if ($request->payment_status == 'cancel') {
                $arr['payment_status'] = 0;
            } else {
                $arr['payment_status'] = $request->payment_status;
            }


            $start = date("Y-m-d", strtotime($request->start));
            $end = date("Y-m-d", strtotime($request->end));

            $orders = $orders->whereBetween('created_at', [$start, $end]);
        }

        if ($type != 'all') {
            $orders = $orders->where('status', $type);
        }

        $orders = $orders->with('customer')->withCount('order_items')->orderBy('id', 'DESC')->paginate(10)->toArray();


        // $pendings = Order::where('shop_id', $shop_id)->where('status', 'pending')->count();
        // $processing = Order::where('shop_id', $shop_id)->where('status', 'processing')->count();
        // $pickup = Order::where('shop_id', $shop_id)->where('status', 'ready-for-pickup')->count();
        // $completed = Order::where('shop_id', $shop_id)->where('status', 'completed')->count();
        // $canceled = Order::where('shop_id', $shop_id)->where('status', 'canceled')->count();
        // $archived = Order::where('shop_id', $shop_id)->where('status', 'archived')->count();

        // $type = $type;
        // $status = [
        //     'pending' => trans('pending'),
        //     'processing' => trans('processing'),
        //     'ready-for-pickup' => trans('ready-for-pickup'),
        //     'completed' => trans('completed'),
        //     'archived' => trans('archived'),
        //     'canceled' => trans('canceled'),
        //     'picked_up' => trans('picked_up'),
        //     'delivered' => trans('delivered'),
        // ];
        return response()->json(array_merge($orders, ['status' => 'success']));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shop_id = current_shop_id();
        $info = Order::where('shop_id', $shop_id)->with('order_item', 'customer', 'order_content', 'shipping_info', 'gateway')->findorFail($id);
        $info->customer_info = json_decode($info->order_content->value ?? '');
        return $this->success($info);
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

        $order = Order::where('shop_id', $shop_id)->with('order_item_with_stock', 'order_item_with_file', 'customer', 'order_content', 'shipping_info')->findorFail($id);

        if ($request->has('status')) {
            $order->status = $request->status;
        }
        if ($request->has('payment_status')) {
            $order->payment_status = $request->payment_status;
        }
        $order->save();


        if ($request->status == 'completed') {

            foreach ($order->order_item_with_stock as $row) {
                if ($row->stock != null) {
                    $available_qty = $row->stock->stock_qty;
                    $order_qty = $row->qty;
                    if ($order_qty >= $available_qty) {
                        $final_qty = 0;
                        $final_stock_status = 0;
                    } else {
                        $final_qty = $available_qty - $order_qty;
                        $final_stock_status = 1;
                    }

                    $stock = Stock::find($row->stock->id);
                    $stock->stock_status = $final_stock_status;
                    $stock->stock_qty = $final_qty;
                    $stock->save();
                }
            }
        }

        if (!empty($request->mail_notify)) {


            $location = ShopOption::where('key', 'location')->where('shop_id', $shop_id)->first();
            $store_email = ShopOption::where('key', 'store_email')->where('shop_id', $shop_id)->first();
            $location = json_decode($location->value ?? '');

            $order_content = json_decode($order->order_content->value ?? '');
            $data['order'] = $order;
            $data['location'] = $location;
            $data['order_content'] = $order_content;
            $data['url'] = my_url();
            $data['customer_email'] = $order_content->email ?? '';
            $data['admin_email'] = $store_email->value ?? Auth::user()->email;
            if (!empty($order_content->email)) {
                if (env('QUEUE_MAIL') == 'on') {
                    dispatch(new \App\Jobs\SendInvoiceEmail($data));
                } else {
                    Mail::to($data['customer_email'])->send(new CustomerOrderMail($data));
                }
            }
        }

        return $this->success([], 'Order Updated');
    }

    public function invoice($id)
    {
        $shop_id = current_shop_id();
        $order = Order::where('shop_id', $shop_id)->with('order_item', 'customer', 'order_content', 'shipping_info')->findorFail($id);

        $location = \App\Models\ShopOption::where('key', 'location')->where('shop_id', $shop_id)->first();
        $location = json_decode($location->value ?? '');

        $gst = \App\Models\ShopOption::where('key', 'gst')->where('shop_id', $shop_id)->first();
        $gst = $gst->value ?? '';

        $order_content = json_decode($order->order_content->value ?? '');

        $pdf = PDF::loadView('email.invoice', compact('order', 'order_content', 'location', 'gst'));
        return $pdf->download('invoice.pdf');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $shop_id = current_shop_id();
        if ($request->method == 'delete') {
            if ($request->ids) {
                foreach ($request->ids as $id) {
                    $order = Order::where('shop_id', $shop_id)->findorFail($id);
                    $order->delete();
                }
            }
        } else {
            if ($request->ids) {
                foreach ($request->ids as $id) {
                    $order = Order::where('shop_id', $shop_id)->findorFail($id);
                    $order->status = $request->method;
                    $order->save();
                }
            }
        }

        return response()->json(['Success']);
    }
}
