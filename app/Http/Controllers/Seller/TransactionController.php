<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Gateway;

class TransactionController extends Controller
{
    protected $src;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $user_id = Auth::id();
        $user = Auth::user();
        if ($request->src) {
            $this->src = $request->src;
            $orders = Order::where('transaction_id', $request->src)->latest()->with('PaymentMethod')->where('shop_id', current_shop_id())->paginate(40);
        } else {
            $orders = Order::with('PaymentMethod')->where('shop_id', current_shop_id())->latest()->paginate(20);
        }

        $gateways = Gateway::where('shop_id', current_shop_id())
                            ->with(['method' => function($q) {
                                $q->where('status', 1);
                            }])
                            ->whereHas('method', function($q) {
                                $q->where('status', 1);
                            })
                            ->get();
        return view('seller.transaction.index', compact('orders', 'request', 'gateways'));
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
            'o_id' => 'required|max:250',
            'method' => 'required|max:250',
            'transaction_id' => 'required|max:250',
        ]);

        $transaction = Order::where('shop_id', current_shop_id())->findorFail($request->o_id);
        $transaction->payment_method = $request->method;
        $transaction->transaction_id = $request->transaction_id;
        $transaction->save();


        return response()->json([trans('Transaction Successfully Updated')]);
    }
}
