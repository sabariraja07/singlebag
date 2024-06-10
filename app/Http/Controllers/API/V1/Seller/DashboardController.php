<?php

namespace App\Http\Controllers\API\V1\Seller;

use Carbon\Carbon;
use App\Models\Page;
use App\Models\Shop;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use ApiResponser;

    public function order_statics($month)
    {
        if (!is_numeric($month)) {
            $month = Carbon::parse(date('M'))->month;
        }
        $year = Carbon::parse(date('Y'))->year;
        if ($month > Carbon::parse('M')->month) {
            $year -= 1;
        }
        //$month = Carbon::parse($month)->month;
        $user_id = Auth::id();

        $shop_id = current_shop_id();

        $today = Carbon::now();
        $firstDay = Carbon::now()->subDays(30);
        $lastEnd = Carbon::now()->subDays(31);
        $lastStart = Carbon::now()->subDays(61);

        $data['total_orders'] = Order::where('shop_id', $shop_id)->dateBetween($firstDay, $today)->count();
        $data['total_pending'] = Order::where('shop_id', $shop_id)->where('status', 'pending')->count();
        $data['total_completed'] = Order::where('shop_id', $shop_id)->dateBetween($firstDay, $today)->where('status', 'completed')->count();
        $data['total_processing'] = Order::where('shop_id', $shop_id)->whereIn('status', ['processing', 'ready-for-pickup'])->count();

        $data['total_amount'] = Order::where('shop_id', $shop_id)->dateBetween($firstDay, $today)->where('status', 'completed')->sum('total');

        $data['last_total_orders'] = Order::where('shop_id', $shop_id)->dateBetween($lastStart, $lastEnd)->count();
        $data['last_total_completed'] = Order::where('shop_id', $shop_id)->dateBetween($lastStart, $lastEnd)->where('status', 'completed')->count();

        $data['last_total_amount'] = Order::where('shop_id', $shop_id)->dateBetween($lastStart, $lastEnd)->where('status', 'completed')->sum('total');

        // $total_orders = Order::where('shop_id', $shop_id)->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->count();
        // $total_pending = Order::where('shop_id', $shop_id)->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'pending')->count();
        // $total_completed = Order::where('shop_id', $shop_id)->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'completed')->count();
        // $total_processing1 = Order::where('shop_id', $shop_id)->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'processing')->count();
        // $total_processing2 = Order::where('shop_id', $shop_id)->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'ready-for-pickup')->count();

        // $data['total_amount'] = Order::where('shop_id', $shop_id)->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'completed')->sum('total');

        // $total_processing = $total_processing1 + $total_processing2;

        // $data['total_orders'] = number_format($total_orders);
        // $data['total_pending'] = number_format($total_pending);
        // $data['total_pending'] = number_format($total_completed);
        // $data['total_processing'] = number_format($total_processing);

        if ($data['last_total_orders'] > 0) {
            $data['increase_sales'] = round((($data['total_orders'] - $data['last_total_orders']) / $data['last_total_orders']) * 100);
        } else {
            $data['increase_sales'] = 100;
        }

        if ($data['last_total_amount'] > 0) {
            $data['increase_amount'] = round((($data['total_amount'] - $data['last_total_amount']) / $data['last_total_amount']) * 100);
        } else {
            $data['increase_amount'] = 100;
        }

        if ($data['last_total_completed'] > 0) {
            $data['increase_completed'] = round((($data['total_completed'] - $data['last_total_completed']) / $data['last_total_completed']) * 100);
        } else {
            $data['increase_completed'] = 100;
        }

        $data['total_amount'] = amount_format($data['total_amount']);
        $data['last_total_amount'] = amount_format($data['last_total_amount']);

        return $this->success($data);
    }

    public function staticData()
    {
        $user_id = Auth::id();
        $year = Carbon::parse(date('Y'))->year;
        $today = Carbon::today();
        $shop_id = current_shop_id();

        $totalEarnings = Order::where('shop_id', $shop_id)->where('payment_status', 1)->where('status', 'completed')->whereYear('created_at', '=', $year)->sum('total');
        $totalEarnings = amount_format($totalEarnings);

        $totalSales = Order::where('shop_id', $shop_id)->where('status', 'completed')->whereYear('created_at', '=', $year)->count();
        $totalSales = number_format($totalSales);
        $storage_size = storageSize($shop_id);

        $today_sale_amount = Order::where('shop_id', $shop_id)->where('status', '!=', 'canceled')->whereDate('created_at', $today)->sum('total');
        $today_sale_amount = amount_format($today_sale_amount);

        $today_orders = Order::where('shop_id', $shop_id)->whereDate('created_at', $today)->count();
        $today_orders = number_format($today_orders);


        $yesterday_sale_amount = Order::where('shop_id', $shop_id)->where('status', '!=', 'canceled')->whereDate('created_at', Carbon::yesterday())->sum('total');
        $yesterday_sale_amount = amount_format($yesterday_sale_amount);


        $previous_week = strtotime("-1 week +1 day");
        $start_week = strtotime("last sunday midnight", $previous_week);
        $end_week = strtotime("next saturday", $start_week);
        $start_week = date("Y-m-d", $start_week);
        $end_week = date("Y-m-d", $end_week);


        $lastweek_sale_amount = Order::where('shop_id', $shop_id)->where('status', '=', 'completed')->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('total');
        $lastweek_sale_amount = amount_format($lastweek_sale_amount);

        $lastmonth_sale_amount = Order::where('shop_id', $shop_id)->where('status', '=', 'completed')->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->sum('total');
        $lastmonth_sale_amount = amount_format($lastmonth_sale_amount);

        $thismonth_sale_amount = Order::where('shop_id', $shop_id)->where('status', '=', 'completed')->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))->sum('total');
        $thismonth_sale_amount = amount_format($thismonth_sale_amount);

        // $orders = Order::where('shop_id', $shop_id)->whereYear('created_at', '=', $year)->orderBy('id', 'asc')->selectRaw('year(created_at) year, monthname(created_at) month, count(*) sales')
        // 	->groupBy('year', 'month')
        // 	->get();

        // $earnings = Order::where('shop_id', $shop_id)->whereYear('created_at', '=', $year)->where('status', 'completed')->orderBy('id', 'asc')->selectRaw('year(created_at) year, monthname(created_at) month, sum(total) total')
        // 	->groupBy('year', 'month')
        // 	->get();
        $products = Product::where('shop_id', $shop_id)->where('type', '!=', 'page')->count();
        $pages = Page::where('shop_id', $shop_id)->count();

        $data['totalEarnings'] = $totalEarnings;
        $data['totalSales'] = $totalSales;
        $data['storage_size'] = $storage_size . 'MB';
        $data['today_sale_amount'] = $today_sale_amount;
        $data['today_orders'] = $today_orders;
        $data['yesterday_sale_amount'] = $yesterday_sale_amount;
        $data['lastweek_sale_amount'] = $lastweek_sale_amount;
        $data['lastmonth_sale_amount'] = $lastmonth_sale_amount;
        $data['thismonth_sale_amount'] = $thismonth_sale_amount;
        // $data['orders'] = $orders;
        // $data['earnings'] = $earnings;
        $data['products'] = $products;
        $data['pages'] = $pages;
        $data['storage_used'] = (float)str_replace(',', '', $storage_size);

        //$plan_info = Shop::with('plan')->where('id', $shop_id)->first();

        // $plan_data = json_decode($plan_info->data);
        // $plan = $plan_info->subscription->plan->name ?? '';
        // $product_limit = $plan_data->product_limit;
        // $storage = $plan_data->storage;
        // $will_expired = $plan_info->will_expire;

        // $data['plan_name'] = $plan;
        // $data['product_limit'] = $product_limit;
        // $data['storage'] = $storage;

        // if ($will_expired == null) {
        // 	$expire = "Expired";
        // } else {
        // 	$expire = Carbon::parse($will_expired)->format('F d, Y');
        // }
        // $data['will_expired'] = $expire;

        return $this->success($data);
    }

    public function perfomance($period = 365)
    {
        $user_id = Auth::id();
        $shop_id = current_shop_id();

        if ($period == 1) {
            $earnings = Order::where('shop_id', $shop_id)->whereDate('created_at',  Carbon::now())->where('status', '!=', 'canceled')->orderBy('id', 'asc')->selectRaw('DATE_FORMAT(created_at, "%d-%m-%Y") year, DATE_FORMAT(created_at, "%H:00") date, sum(total) total')->groupBy('year', 'date')->get();
        } else if ($period == 30) {
            $earnings = Order::where('shop_id', $shop_id)->whereDate('created_at', '>', Carbon::now()->subDays($period))->where('status', '!=', 'canceled')->orderBy('id', 'asc')->selectRaw('year(created_at) year,DATE_FORMAT(created_at, "%d-%m-%Y") date, sum(total) total')->groupBy('year', 'date')->get();
        } else {
            $earnings = Order::where('shop_id', $shop_id)->whereDate('created_at', '>', Carbon::now()->subDays($period))->where('status', '!=', 'canceled')->orderBy('id', 'asc')->selectRaw('year(created_at) year, DATE_FORMAT(created_at, "%b-%y") date, sum(total) total')->groupBy('year', 'date')->get();
        }

        return $this->success($earnings);
    }
}
