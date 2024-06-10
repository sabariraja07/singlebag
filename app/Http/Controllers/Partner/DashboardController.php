<?php

namespace App\Http\Controllers\Partner;

use Analytics;
use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Settlement;
use Spatie\Analytics\Period;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
	public function dashboard()
	{
		$all = Shop::where('created_by', Auth::id())->count();
		$actives = Shop::where('created_by', Auth::id())->where('status', 'active')->count();
		$settlements = Settlement::where('status', 'paid')->where('user_id', Auth::id())->sum('amount');
		return view('partner.dashboard', compact('all', 'actives', 'settlements'));
	}

	public function order_statics($month)
	{
		$month = Carbon::parse($month)->month;
		$year = Carbon::parse(date('Y'))->year;
		$user_id = Auth::id();

		$shop_id = current_shop_id();

		$total_orders = Order::where('shop_id', $shop_id)->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->count();
		$total_pending = Order::where('shop_id', $shop_id)->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'pending')->count();
		$total_completed = Order::where('shop_id', $shop_id)->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'completed')->count();
		$total_processing1 = Order::where('shop_id', $shop_id)->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'processing')->count();
		$total_processing2 = Order::where('shop_id', $shop_id)->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->where('status', 'ready-for-pickup')->count();
		$total_processing = $total_processing1 + $total_processing2;

		$data['total_orders'] = number_format($total_orders);
		$data['total_pending'] = number_format($total_pending);
		$data['total_completed'] = number_format($total_completed);
		$data['total_processing'] = number_format($total_processing);

		return response()->json($data);
	}

	public function staticData()
	{
		$total_shops = Shop::where('created_by', Auth::id())->count();

		$data['total_shops'] = $total_shops;

		return response()->json($data);
	}

	public function perfomance($period)
	{
		$user_id = Auth::id();
		$shop_id = current_shop_id();

		if ($period != 365) {
			$earnings = Order::where('shop_id', $shop_id)->whereDate('created_at', '>', Carbon::now()->subDays($period))->where('status', '!=', 'canceled')->orderBy('id', 'asc')->selectRaw('year(created_at) year, date(created_at) date, sum(total) total')->groupBy('year', 'date')->get();
		} else {
			$earnings = Order::where('shop_id', $shop_id)->whereDate('created_at', '>', Carbon::now()->subDays($period))->where('status', '!=', 'canceled')->orderBy('id', 'asc')->selectRaw('year(created_at) year, monthname(created_at) month, sum(total) total')->groupBy('year', 'month')->get();
		}


		return response()->json($earnings);
	}


	public function google_analytics($days)
	{

		if (file_exists('uploads/' . Auth::id() . '/service-account-credentials.json')) {
			$info = google_analytics_for_user();

			\Config::set('analytics.view_id', $info['view_id']);
			\Config::set('analytics.service_account_credentials_json', $info['service_account_credentials_json']);

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
		return $country = \Analytics::performQuery(Period::days($period), 'ga:sessions', ['dimensions' => 'ga:country', 'dimension' => 'ga:latitude', 'dimension' => 'ga:longitude', 'sort' => '-ga:sessions']);

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

		return \Analytics::fetchTotalVisitorsAndPageViews(Period::days($period))->map(function ($data) {
			$row['date'] = $data['date']->format('Y-m-d');
			$row['visitors'] = $data['visitors'];
			$row['pageViews'] = $data['pageViews'];
			return $row;
		});
	}
	public function fetchMostVisitedPages($period)
	{
		return \Analytics::fetchMostVisitedPages(Period::days($period));
	}

	public function fetchTopReferrers($period)
	{
		return \Analytics::fetchTopReferrers(Period::days($period));
	}

	public function fetchUserTypes($period)
	{
		return \Analytics::fetchUserTypes(Period::days($period));
	}

	public function fetchTopBrowsers($period)
	{
		return \Analytics::fetchTopBrowsers(Period::days($period));
	}
}
