<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use Spatie\Analytics;
use App\Models\Domain;
use Spatie\Analytics\Period;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\RequestDomain;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{

    public function settings()
    {
        return view('settings');
    }

    public function dashboard()
    {
        if (!Auth()->user()->can('dashboard')) {
            return abort(401);
        }

        $request_domain_users = RequestDomain::where('status', 2)->latest()->take(4)->get();
        $orders = Subscription::with('user', 'plan_info', 'PaymentMethod')->where('status', 2)->latest()->take(20)->get();

        return view('admin.dashboard', compact('request_domain_users', 'orders'));
    }

    public function staticData()
    {
        $total_subscribers = User::whereHas('shop')->count();
        $total_domain_request = Domain::whereHas('shop')->where('status', 2)->count();
        $total_earnings = Subscription::where('status', '!=', 0)->sum('amount');
        $total_expired =  Subscription::whereNotNull('shop_id')->whereDate('will_expire', '<', Carbon::now())->count();
        $total_custom_domain_request = RequestDomain::where('status', 2)->count();
        $total_domain_request = $total_domain_request + $total_custom_domain_request;

        $year = Carbon::parse(date('Y'))->year;
        $today = Carbon::today();


        $earnings = Subscription::whereYear('created_at', '=', $year)->where('status', '!=', 0)->orderBy('id', 'asc')->selectRaw('year(created_at) year, monthname(created_at) month, sum(amount) total')->groupBy('year', 'month')->get();
        $total_earnings_this_year = Subscription::where('status', '!=', 0)->whereYear('created_at', '=', $year)->sum('amount');


        $orders = Subscription::whereYear('created_at', '=', $year)->orderBy('id', 'asc')->selectRaw('year(created_at) year, monthname(created_at) month, count(*) orders')
            ->groupBy('year', 'month')
            ->get();
        $total_order_this_year = Subscription::where('status', '!=', 0)->whereYear('created_at', '=', $year)->count();

        $data['total_subscribers'] = number_format($total_subscribers);
        $data['total_domain_request'] = number_format($total_domain_request);
        $data['total_expired'] = number_format($total_expired);
        $data['total_earnings'] = amount_admin_format($total_earnings);
        $data['earnings'] = $earnings;
        $data['total_earnings_this_year'] = amount_admin_format($total_earnings_this_year);
        $data['orders'] = $orders;
        $data['total_order_this_year'] = number_format($total_order_this_year);

        return response()->json($data);
    }

    public function perfomance($period)
    {
        if ($period != 365) {
            $earnings = Subscription::whereDate('created_at', '>', Carbon::now()->subDays($period))->where('status', '!=', 'canceled')->orderBy('id', 'asc')->selectRaw('year(created_at) year, date(created_at) date, sum(amount) total')->groupBy('year', 'date')->get();
        } else {
            $earnings = Subscription::whereDate('created_at', '>', Carbon::now()->subDays($period))->where('status', '!=', 'canceled')->orderBy('id', 'asc')->selectRaw('year(created_at) year, monthname(created_at) month, sum(amount) total')->groupBy('year', 'month')->get();
        }


        return response()->json($earnings);
    }

    public function order_statics($month)
    {
        $month = Carbon::parse($month)->month;
        $year = Carbon::parse(date('Y'))->year;

        $total_orders = Subscription::whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->count();

        $total_pending = Subscription::whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->where('status', 2)->count();

        $total_completed = Subscription::whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->where('status', 1)->count();

        $total_expired = Subscription::whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->where('status', 3)->count();

        $total_declined = Subscription::whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->where('status', 0)->count();

        $data['total_orders'] = number_format($total_orders);
        $data['total_pending'] = number_format($total_pending);
        $data['total_completed'] = number_format($total_completed);
        $data['total_processing'] = number_format($total_expired);
        $data['total_declined'] = number_format($total_declined);

        return response()->json($data);
    }


    public function google_analytics($days)
    {
        if (file_exists('uploads/service-account-credentials.json')) {
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth()->user()->can('admin.list')) {
            $users = User::role('superadmin')->where('id', '!=', 1)->latest()->get();
            return view('admin.admin.index', compact('users'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth()->user()->can('admin.create')) {
            $roles  = Role::all();
            return view('admin.admin.create', compact('roles'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation Data
        $request->validate([
            'first_name' => 'required|max:50',
            'roles' => 'required',
            'email' => 'required|max:100|email_address|unique:users',
            'password' => 'required|min:6|confirmed|without_spaces',
        ]);

        // Create New User
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name ?? "";
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        if ($request->roles) {
            $user->assignRole($request->roles);
        }


        return response()->json(['User has been created !!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth()->user()->can('admin.edit')) {
            $user = User::find($id);
            $roles  = Role::all();
            return view('admin.admin.edit', compact('user', 'roles'));
        }
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
        // Create New User
        $user = User::find($id);

        // Validation Data
        $request->validate([
            'first_name' => 'required|max:50',
            'email' => 'required|max:100|email_address|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed|without_spaces',
        ]);


        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name ?? "";
        $user->email = $request->email;
        $user->status = $request->status;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user->roles()->detach();
        if ($request->roles) {
            $user->assignRole($request->roles);
        }


        return response()->json(['User has been updated !!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        if (Auth()->user()->can('admin.delete')) {

            if ($request->status == 'delete') {
                if ($request->ids) {
                    foreach ($request->ids as $id) {
                        User::destroy($id);
                    }
                }
            } else {

                if ($request->ids) {
                    foreach ($request->ids as $id) {
                        $post = User::find($id);
                        $post->status = $request->status;
                        $post->save();
                    }
                }
            }
        }

        return response()->json('Success');
    }
}
