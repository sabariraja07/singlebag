<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Domain;
use App\Models\Subscription;
class ReportController extends Controller
{
	public function index(Request $request)
	{
		if (!Auth()->user()->can('report.view')) {
			abort(401);
		}
		if ($request->start) {
			$start = date("Y-m-d",strtotime($request->start));
			$end = date("Y-m-d",strtotime($request->end));

			$order_count=Subscription::whereDate('created_at', '>=' ,$start)->whereDate('created_at', '<=' ,$end)->count();
			
			$order_expired=Subscription::whereDate('created_at', '>=' ,$start)->whereDate('created_at', '<=' ,$end)->where('status',3)->count();
			$order_sum=Subscription::whereDate('created_at', '>=' ,$start)->whereDate('created_at', '<=' ,$end)->whereHas('PaymentMethod',function($q){
				return $q->where('status',1);
			})->sum('amount');
			$order_tax=Subscription::whereDate('created_at', '>=' ,$start)->whereDate('created_at', '<=' ,$end)->whereHas('PaymentMethod',function($q){
				return $q->where('status',1);
			})->sum('tax');
			$posts=Subscription::whereDate('created_at', '>=' ,$start)->whereDate('created_at', '<=' ,$end)->with('plan_info','PaymentMethod')->latest()->paginate(40);
		}
		
		else{
		$order_count=Subscription::count();
		
		$order_expired=Subscription::where('status',3)->count();
		$order_sum=Subscription::whereHas('PaymentMethod',function($q){
			return $q->where('status',1);
		})->sum('amount');
		$order_tax=Subscription::whereHas('PaymentMethod',function($q){
			return $q->where('status',1);
		})->sum('tax');
		$posts=Subscription::with('plan_info','PaymentMethod')->latest()->paginate(40);	
		}
		

		$start = $start ?? '';
		$end = $end ?? '';

		return view('admin.report.index',compact('start','end','order_count','order_sum','order_expired','posts','request','order_tax'));
	}
}
