<?php

namespace App\Http\Controllers\Seller;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Page;
use App\Models\Order;
use App\Models\Product;
use Spatie\Analytics\Period;
use Spatie\Analytics\Analytics;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
	public function switchstore()
	{
		$url = request()->get('url');
		$url = $url . '/seller/dashboard';
		Cache::forget(request()->getHost() . '.admin');
		Cache::forget(request()->getHost() . '_current_shop_' . auth()->id());
		Cache::forget(request()->getHost() . '_current_shop_' . domain_info('shop_id'));
		return redirect($url);
	}
	public function dashboard()
	{
		if (!Session::has('locale')) {
			set_language();
		}
		return view('seller.new_dashboard');
	}

	public function order_statics($month)
	{
		$month = Carbon::parse($month)->month;
		$year = Carbon::parse(date('Y'))->year;

		if (current_shop_type() != 'reseller') {
			$total_orders = Order::shopFinder()->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->count();
			$total_pending = Order::shopFinder()->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'pending')->count();
			$total_completed = Order::shopFinder()->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'completed')->count();
			$total_processing1 = Order::shopFinder()->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'processing')->count();
			$total_processing2 = Order::shopFinder()->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'ready-for-pickup')->count();
			$total_processing = $total_processing1 + $total_processing2;

			$data['total_orders'] = number_format($total_orders);
			$data['total_pending'] = number_format($total_pending);
			$data['total_completed'] = number_format($total_completed);
			$data['total_processing'] = number_format($total_processing);

			return response()->json($data);
		} else {
			$total_orders = Order::shopFinder()->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->count();
			$total_pending = Order::shopFinder()->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'pending')->count();
			$total_completed = Order::shopFinder()->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'completed')->count();
			$total_processing1 = Order::shopFinder()->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'processing')->count();
			$total_processing2 = Order::shopFinder()->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'ready-for-pickup')->count();
			$total_processing = $total_processing1 + $total_processing2;

			$data['total_orders'] = number_format($total_orders);
			$data['total_pending'] = number_format($total_pending);
			$data['total_completed'] = number_format($total_completed);
			$data['total_processing'] = number_format($total_processing);

			return response()->json($data);
		}
	}

	public function staticData()
	{
		$user_id = Auth::id();
		$year = Carbon::parse(date('Y'))->year;
		$today = Carbon::today();
		$shop_id = current_shop_id();

		$totalEarnings = Order::shopFinder()->where('payment_status', 1)->where('status', 'completed')->whereYear('created_at', '=', $year)->sum('total');
		$totalEarnings = amount_format($totalEarnings);

		$totalSales = Order::shopFinder()->where('status', 'completed')->whereYear('created_at', '=', $year)->count();
		$totalSales = number_format($totalSales);
		$storage_size = storageSize($shop_id);

		$today_sale_amount = Order::shopFinder()->where('status', '!=', 'canceled')->whereDate('created_at', $today)->sum('total');
		$today_sale_amount = amount_format($today_sale_amount);

		$today_orders = Order::shopFinder()->whereDate('created_at', $today)->count();
		$today_orders = number_format($today_orders);


		$yesterday_sale_amount = Order::shopFinder()->where('status', '!=', 'canceled')->whereDate('created_at', Carbon::yesterday())->sum('total');
		$yesterday_sale_amount = amount_format($yesterday_sale_amount);


		$previous_week = strtotime("-1 week +1 day");
		$start_week = strtotime("last sunday midnight", $previous_week);
		$end_week = strtotime("next saturday", $start_week);
		$start_week = date("Y-m-d", $start_week);
		$end_week = date("Y-m-d", $end_week);


		$lastweek_sale_amount = Order::shopFinder()->where('status', '=', 'completed')->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('total');
		$lastweek_sale_amount = amount_format($lastweek_sale_amount);

		$lastmonth_sale_amount = Order::shopFinder()->where('status', '=', 'completed')->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->sum('total');
		$lastmonth_sale_amount = amount_format($lastmonth_sale_amount);

		$thismonth_sale_amount = Order::shopFinder()->where('status', '=', 'completed')->whereMonth('created_at', date('m'))
			->whereYear('created_at', date('Y'))->sum('total');
		$thismonth_sale_amount = amount_format($thismonth_sale_amount);

		$orders = Order::shopFinder()->whereYear('created_at', '=', $year)->orderBy('id', 'asc')->selectRaw('year(created_at) year, monthname(created_at) month, count(*) sales')
			->groupBy('year', 'month')
			->get();

		$earnings = Order::shopFinder()->whereYear('created_at', '=', $year)->where('status', 'completed')->orderBy('id', 'asc')->selectRaw('year(created_at) year, monthname(created_at) month, sum(total) total')
			->groupBy('year', 'month')
			->get();
		$posts = Product::where('type', '!=', 'page');

		if (current_shop_type() == 'reseller') {
			$posts = $posts->whereHas('ResellerProduct', function ($q) {
				$q->where('status', '<>', 4)
					->where('shop_id', current_shop_id());
			})
				->where('status', 1);
		} else {
			$posts = $posts->where('status', '<>', 4)->where('shop_id', current_shop_id());
		}
		$posts = $posts->count();
		$pages = Page::byShop()->where('type', 'page')->count();

		$data['totalEarnings'] = $totalEarnings;
		$data['totalSales'] = $totalSales;
		$data['storage_size'] = $storage_size . ' MB';
		$data['today_sale_amount'] = $today_sale_amount;
		$data['today_orders'] = $today_orders;
		$data['yesterday_sale_amount'] = $yesterday_sale_amount;
		$data['lastweek_sale_amount'] = $lastweek_sale_amount;
		$data['lastmonth_sale_amount'] = $lastmonth_sale_amount;
		$data['thismonth_sale_amount'] = $thismonth_sale_amount;
		$data['orders'] = $orders;
		$data['earnings'] = $earnings;
		$data['products'] = $posts;
		$data['pages'] = $pages;
		$data['storage_used'] = (float)str_replace(',', '', $storage_size);

		$plan_info = Shop::with('subscription.plan')->where('id', $shop_id)->first();

		$plan_data = json_decode($plan_info->data);
		$plan = $plan_info->subscription->plan->name ?? '';
		$product_limit = $plan_data->product_limit;
		$storage = $plan_data->storage ?? '';
		$will_expired = $plan_info->will_expire;
		// response.storage  == -1 ? 'Unlimited' : response.storage
		if (!empty($storage)) {
			if ($storage == -1) {
				$storage_limit = $storage;
			} else {
				$storage_limit = human_filesize($storage);
			}
		}
		$data['plan_name'] = $plan;
		$data['product_limit'] = $product_limit;
		$data['storage'] = $storage_limit;
		$data['storage_pie'] = $storage;

		if ($will_expired == null) {
			$expire = "Expired";
		} else {
			$expire = Carbon::parse($will_expired)->format('F d, Y');
		}
		$data['will_expired'] = $expire;

		return response()->json($data);
	}

	public function perfomance($period)
	{
		$user_id = Auth::id();
		$shop_id = current_shop_id();

		if ($period != 365) {
			$earnings = Order::shopFinder()->whereDate('created_at', '>', Carbon::now()->subDays($period))->where('status', '!=', 'canceled')->orderBy('id', 'asc')->selectRaw('year(created_at) year, date(created_at) date, sum(total) total')->groupBy('year', 'date')->get();
		} else {
			$earnings = Order::shopFinder()->whereDate('created_at', '>', Carbon::now()->subDays($period))->where('status', '!=', 'canceled')->orderBy('id', 'asc')->selectRaw('year(created_at) year, monthname(created_at) month, sum(total) total')->groupBy('year', 'month')->get();
		}


		return response()->json($earnings);
	}


	public function google_analytics($days)
	{

		if (file_exists('uploads/' . current_shop_id() . '/service-account-credentials.json')) {
			$info = google_analytics_for_user();

			Config::set('analytics.view_id', $info['view_id']);
			Config::set('analytics.service_account_credentials_json', $info['service_account_credentials_json']);

			$data['TotalVisitorsAndPageViews'] = $this->fetchTotalVisitorsAndPageViews($days);
			$data['MostVisitedPages'] = $this->fetchMostVisitedPages($days);
			$data['Referrers'] = $this->fetchTopReferrers($days);
			$data['fetchUserTypes'] = $this->fetchUserTypes($days);
			$data['TopBrowsers'] = $this->fetchTopBrowsers($days);
		} else {
			$data['TotalVisitorsAndPageViews'] = [];
			$data['MostVisitedPages'] = [];
			$data['Referrers'] = [];
			$data['fetchUserTypes'] = [];
			$data['TopBrowsers'] = [];
		}


		return response()->json($data);
	}

	public function getCountries($period)
	{
		return $country = Analytics::performQuery(Period::days($period), 'ga:sessions', ['dimensions' => 'ga:country', 'dimension' => 'ga:latitude', 'dimension' => 'ga:longitude', 'sort' => '-ga:sessions']);

		$result = collect($country['rows'] ?? [])->map(function (array $dateRow) {
			return [
				'country' =>  $dateRow[0],
				'sessions' => (int) $dateRow[1],
			];
		});

		return $result;
	}



	public function fetchTotalVisitorsAndPageViews($period)
	{

		return Analytics::fetchTotalVisitorsAndPageViews(Period::days($period))->map(function ($data) {
			$row['date'] = $data['date']->format('Y-m-d');
			$row['visitors'] = $data['visitors'];
			$row['pageViews'] = $data['pageViews'];
			return $row;
		});
	}
	public function fetchMostVisitedPages($period)
	{
		return Analytics::fetchMostVisitedPages(Period::days($period));
	}

	public function fetchTopReferrers($period)
	{
		return Analytics::fetchTopReferrers(Period::days($period));
	}

	public function fetchUserTypes($period)
	{
		return Analytics::fetchUserTypes(Period::days($period));
	}

	public function fetchTopBrowsers($period)
	{
		return Analytics::fetchTopBrowsers(Period::days($period));
	}
}
