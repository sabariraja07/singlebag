<?php

namespace App\Http\Controllers\Seller;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
	public function index(Request $request)
	{
		if ($request->start) {

			$start = date("Y-m-d", strtotime($request->start))." 00:00:00";
			$end = date("Y-m-d", strtotime($request->end))." 23:59:59";

			$total = Order::shopFinder()->whereBetween('created_at', [$start, $end])->count();
			$completed = Order::shopFinder()->whereBetween('created_at', [$start, $end])->where('status', 'completed')->count();
			$canceled = Order::shopFinder()->whereBetween('created_at', [$start, $end])->where('status', 'canceled')->count();
			$picked_up = Order::shopFinder()->whereBetween('created_at', [$start, $end])->where('status', 'picked_up')->count();
			$proccess = Order::shopFinder()->whereBetween('created_at', [$start, $end])->whereNotIn('status', ['completed', 'canceled'])->count();

			$amounts = Order::shopFinder()->whereBetween('created_at', [$start, $end])->sum('total');
			$amount_cancel = Order::shopFinder()->where('status', 'canceled')->where('payment_status', 0)->whereBetween('created_at', [$start, $end])->sum('total');
			$amount_proccess = Order::shopFinder()->whereNotIn('status', [ 'completed', 'canceled'])->whereBetween('created_at', [$start, $end])->sum('total');
			$amount_completed = Order::shopFinder()->whereNotIn('status', ['completed'])->where('payment_status', 1)->whereBetween('created_at', [$start, $end])->sum('total');
			$amount_revenue = Order::shopFinder()->where('status', '!=', 'canceled')->whereBetween('created_at', [$start, $end])->sum('total');
			$register_order = Order::shopFinder()->where('customer_id','!=', '')->whereBetween('created_at', [$start, $end])->distinct('customer_id')->count();
			$guest_order = Order::shopFinder()->where('customer_id', NULL)->whereBetween('created_at', [$start, $end])->count();
			$orders = Order::shopFinder()->whereBetween('created_at', [$start, $end])->with('customer')->withCount('order_items')->orderBy('id', 'DESC')->paginate(40);
		} else {
			$orders = Order::shopFinder()->with('customer')->withCount('order_items')->orderBy('id', 'DESC')->paginate(40);
			$total = Order::shopFinder()->count();
			$completed = Order::shopFinder()->where('status', 'completed')->count();
			$picked_up = Order::shopFinder()->where('status', 'picked_up')->count();
			$canceled = Order::shopFinder()->where('status', 'canceled')->count();
			$proccess = Order::shopFinder()->whereNotIn('status', ['completed', 'canceled'])->count();

			$amounts = Order::shopFinder()->sum('total');
			$amount_cancel = Order::shopFinder()->where('status', 'canceled')->where('payment_status', 0)->sum('total');
			$amount_proccess = Order::shopFinder()->whereNotIn('status', ['completed', 'canceled'])->sum('total');
			$amount_completed = Order::shopFinder()->where('status', 'completed')->where('payment_status', 1)->sum('total');
			$amount_revenue = Order::shopFinder()->where('status', '!=', 'canceled')->sum('total');
			$register_order = Order::shopFinder()->where('customer_id','!=', '')->distinct('customer_id')->count();
			$guest_order = Order::shopFinder()->where('customer_id', NULL)->count();
		}

		$start = $request->start ?? '';
		$end = $request->end ?? '';
		return view('seller.report.index', compact('orders', 'start', 'end', 'total', 'completed', 'canceled', 'proccess', 'amounts', 'amount_cancel', 'amount_proccess', 'amount_completed', 'request','amount_revenue','guest_order','register_order','picked_up'));
	}

	//revenue_performane
	public function revenue_perfomance($period)
	{
		if ($period != 365) {
			$earnings = Order::shopFinder()->whereDate('created_at', '>', Carbon::now()->subDays($period))->where('status', '!=', 'canceled')->orderBy('id', 'asc')->selectRaw('year(created_at) year, date(created_at) date, sum(total) total')->groupBy('year', 'date')->get();
		} else {
			$earnings = Order::shopFinder()->whereDate('created_at', '>', Carbon::now()->subDays($period))->where('status', '!=', 'canceled')->orderBy('id', 'asc')->selectRaw('year(created_at) year, monthname(created_at) month, sum(total) total')->groupBy('year', 'month')->get();
		}

		return response()->json($earnings);
	}
}
