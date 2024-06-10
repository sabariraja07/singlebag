<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionMeta;
use App\Models\Price;
use App\Models\Option;
use App\Models\Plan;
use Carbon\Carbon;
use App\Mail\Sendmailtowillexpire;
use App\Mail\Planexpired;
use App\Models\Partner;
use App\Models\Settlement;
use App\Models\Shop;
use App\Models\ShopOption;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\StoreAcademyMail;
use App\Mail\PartnerAcademyMail;
use App\Mail\FreeTrailFourthMail;
use App\Mail\FreeTrailThirteenMail;
use App\Mail\FreeTrailTenthMail;
use App\Mail\FreeTrailOverMail;
use Illuminate\Support\Facades\Artisan;
use App\Mail\StoreDayTwoMail;
use App\Mail\StoreDayThreeMail;
use App\Mail\StoreDayFourthMail;
use App\Mail\StoreDayFivthMail;
use App\Mail\StoreDaySixthMail;
use App\Mail\StoreDaySeventhMail;
use App\Mail\StoreDayEightMail;
use App\Mail\StoreDayNineMail;
use App\Mail\StoreDayTenMail;
use App\Mail\StoreDayElevenMail;
use App\Mail\StoreDayTwelveMail;
use App\Mail\StoreDayThirteenMail;
use App\Mail\StoreDayFourteenMail;
use App\Mail\StoreDayFifteenMail;
use App\Mail\StoreDaySixteenMail;
use App\Mail\StoreDaySeventeenMail;
use App\Mail\StoreDayEighteenMail;
use App\Mail\StoreDayNineteenMail;
use App\Mail\StoreDayTwentyMail;
use App\Mail\StoreDayTwentyOneMail;
use App\Mail\StoreDayTwentyTwoMail;



use function GuzzleHttp\Promise\each;

class CronController extends Controller
{
    protected $sendMailToExpiredCustomer;
    protected $auto_plan_assign;

    public function makeExpireAbleCustomer()
    {
        $shops = Shop::where('status', 'active')->whereDate('will_expire', Carbon::now()->subDays(1))->whereHas('subscription', function ($q) {
            $q->where('status', 1);
        })
            ->with('domain', 'user', 'subscription')
            ->get();

        // $users=Subscription::where('status',1)->with('user_with_domain','plan_info')->where('will_expire','<=',date('Y-m-d'))->latest()->get();

        $option = Option::where('key', 'company_info')->first();
        $company_info = json_decode($option->value);
        $auto_plan = Plan::where('is_default', 1)->first();

        Subscription::where('status', 1)
            ->where('will_expire', '<=', Carbon::now()->subDays(1))
            ->update([
                'status' => 3
            ]);

        if (!empty($auto_plan) && $this->auto_plan_assign == true) {

            $meta['name'] = $auto_plan->name;
            $meta['product_limit'] = $auto_plan->product_limit;
            $meta['storage'] = $auto_plan->storage;
            $meta['customer_limit'] = $auto_plan->customer_limit;
            $meta['category_limit'] = $auto_plan->category_limit;
            $meta['location_limit'] = $auto_plan->location_limit;
            $meta['brand_limit'] = $auto_plan->brand_limit;
            $meta['variation_limit'] = $auto_plan->variation_limit;

            $active_has_order = Subscriptionmeta::with('activeorder')->get();
            foreach ($active_has_order as $key => $value) {

                if ($value->activeorder == null) {

                    Subscriptionmeta::where('id', $value->id)->update($meta);
                }
            }
        }

        if ($this->sendMailToExpiredCustomer == false) {
            foreach ($shops as $key => $shop) {
                $customer_email = $shop->user->email;
                $customer_name = $shop->user->fullname;
                $plan_name = $shop->subscription->plan->name;
                $plan_price = amount_admin_format($shop->subscription->plan->price, 'format');
                if (!empty($shop->domain->domain)) {
                    $checkoutUrl = $shop->domain->full_domain . '/seller/make-payment/' . $shop->subscription->plan->id;
                    $data['checkout_url'] = $checkoutUrl;
                    $data['expired_user'] = $customer_email;
                    $data['customer_name'] = $customer_name;
                    $data['plan_name'] = $plan_name;
                    $data['plan_price'] = $plan_price;
                    $data['purchased_at'] = $shop->subscription->created_at->format('Y-m-d');
                    $data['expiry_date'] = $shop->will_expire;
                    $data['order_id'] = $shop->subscription->order_no;
                    $data['company_info'] = $company_info;


                    if (env('QUEUE_MAIL') == 'on') {
                        dispatch(new \App\Jobs\SendInvoiceEmail($data));
                    } else {
                        Mail::to($customer_email)->send(new Planexpired($data));
                    }
                }
            }
        }
    }

    public function SendMailToWillExpirePlanWithInDay($days)
    {
        $expiry_date = Carbon::now()->addDays($days)->format('Y-m-d');
        $shops = Shop::where('status', 'active')->whereDate('will_expire', '<=', $expiry_date)->whereDate('will_expire', '>=', Carbon::now())
            ->whereHas('subscription', function ($q) {
                $q->where('status', 1);
            })->with('domain', 'user', 'subscription')->get();
        // $users= Subscription::where('status',1)->with('user_with_domain','plan_info')->where('will_expire','<=',$expiry_date)->latest()->get();

        $option = Option::where('key', 'company_info')->first();
        $company_info = json_decode($option->value);

        foreach ($shops as $key => $row) {
            $customer_email = $row->user->email;
            $customer_name = $row->user->fullname;
            $plan_name = $row->subscription->plan->name;
            $plan_price = amount_admin_format($row->subscription->plan->price, 'format');
            if (!empty($row->domain)) {
                $checkoutUrl = $row->domain->full_domain . '/seller/make-payment/' . $row->subscription->plan->id;
                $data['checkout_url'] = $checkoutUrl;
                $data['to_will_expire_user'] = $customer_email;
                $data['customer_name'] = $customer_name;
                $data['plan_name'] = $plan_name;
                $data['plan_price'] = $plan_price;
                $data['purchased_at'] = $row->subscription->created_at->format('Y-m-d');
                $data['expiry_date'] = $row->subscription->will_expire;
                $data['order_id'] = $row->subscription->order_no;
                $data['company_info'] = $company_info;



                if (env('QUEUE_MAIL') == 'on') {
                    dispatch(new \App\Jobs\SendInvoiceEmail($data));
                } else {
                    Mail::to($customer_email)->send(new Sendmailtowillexpire($data));
                }
            }
        }
    }

    public  function RunJob()
    {
        $cron_info = Option::where('key', 'cron_info')->first();
        $cron_info = json_decode($cron_info->value);
        // if ($cron_info->send_notification_expired_date == 'on') {
        //     $this->sendMailToExpiredCustomer = true;
        // }
        // if ($cron_info->send_notification_expired_date == 'yes') {
        //     $this->auto_plan_assign = true;
        // }

        $this->makeExpireAbleCustomer();
    }

    public function run_SendMailToWillExpirePlanWithInDay()
    {
        $cron_info = Option::where('key', 'cron_info')->first();
        $cron_info = json_decode($cron_info->value);
        $this->SendMailToWillExpirePlanWithInDay($cron_info->send_mail_to_will_expire_within_days);
    }

    public function reset_product_price()
    {
        $start = Price::where('starting_date', '<=', date('Y-m-d'))->where('special_price', '!=', null)->get();
        foreach ($start as $row) {

            if ($row->price_type == 1) {
                $price = $row->regular_price - $row->special_price;
            } else {
                $percent = $row->regular_price * ($row->special_price * 0.01);
                $price = $row->regular_price - $percent;
                $price = str_replace(',', '', number_format($price, 2));
            }

            $new_price = Price::find($row->id);
            $new_price->price = $price;
            $new_price->save();
        }
        $ending_date = Price::where('ending_date', '<=', date('Y-m-d'))->get();
        foreach ($ending_date as $row) {
            $price = Price::find($row->id);
            $price->price = $price->regular_price;
            $price->special_price = null;
            $price->price_type = 1;
            $price->starting_date = null;
            $price->ending_date = null;
            $price->save();
        }
        return response()->json('success');
    }


    public function index()
    {

        if (!Auth()->user()->can('cron_job.control')) {
            return abort(401);
        }
        $option = Option::where('key', 'cron_info')->first();
        $info = json_decode($option->value);

        return view('admin.cron.index', compact('info'));
    }

    public function make_expirable_user()
    {
        Artisan::call('make:make_expirable_user');
        return "done";
    }

    public function send_mail_to_will_expire_plan_soon()
    {
        //before expired how many days left
        $option = Option::where('key', 'cron_option')->first();
        $cron_option = json_decode($option->value);

        $date = Carbon::now()->addDays($cron_option->days)->format('Y-m-d');

        $tenants = Tenant::where([['status', 1], ['will_expire', '<=', $date], ['auto_renew', 0], ['will_expire', '!=', Carbon::now()->format('Y-m-d')]])->with('orderwithplan', 'user')->get();


        $expireable_tenants = [];

        foreach ($tenants as $row) {
            $plan = $row->orderwithplan->plan;

            if (!empty($plan)) {
                if ($row->orderwithplan->plan->is_trial == 0) {
                    $order_info['email'] = $row->user->email;
                    $order_info['name'] = $row->user->fullname;
                    $order_info['plan_name'] = $plan->name;
                    $order_info['tenant_id'] = $row->id;
                    $order_info['will_expire'] = $row->will_expire;
                    $order_info['amount'] = $plan->price;
                    $order_info['plan_name'] = $plan->name;
                    array_push($expireable_tenants, $order_info);
                }
            }
        }


        $this->expireSoon($expireable_tenants, $cron_option->alert_message);

        return "success";
    }


    public function store(Request $request)
    {
        $option = Option::where('key', 'cron_info')->first();
        $data = json_decode($option->value);
        $info['send_mail_to_will_expire_within_days'] = $request->send_mail_to_will_expire_within_days;
        $info['expire_message'] = $request->expire_message;
        $info['trial_expired_message'] = $request->trial_expired_message;
        $info['alert_message'] = $request->alert_message;
        $info['auto_approve'] = $request->auto_approve;
        $option->value = json_encode($info);
        $option->save();

        return response()->json(['Job Updated !!']);
    }

    public function partner_settlements()
    {
        if (Subscription::where('user_id', Auth::id())->whereNull('settlement_id')->count() == 0) {
            return back()->with(['message' => 'No new subscriptions found.']);
        }
        $first = Subscription::where('user_id', Auth::id())->whereNull('settlement_id')->first();
        $last = Subscription::where('user_id', Auth::id())->whereNull('settlement_id')->latest()->first();

        $partners = Partner::get();

        foreach ($partners as $partner) {
            $total = Subscription::where('user_id', $partner->id)
                ->whereNull('settlement_id')
                ->whereMonth('created_at', now()->month)
                ->sum('amount');
        }

        $total = Subscription::whereNull('settlement_id')->whereYear('created_at', date('Y', strtotime('-1 year')))->sum('amount');

        $amount = Subscription::where('user_id', Auth::id())->whereNull('settlement_id')->sum('commission');

        $total = Subscription::where('user_id', Auth::id())->whereNull('settlement_id')->sum('amount');

        $commission = $this->getCommission($total = 0);

        $default_commission = Option::where('key', 'default_commission')->first();

        $tax = Option::where('key', 'partner_commission')->first();
        $commission_tax = $amount * (($tax->value ?? 0) * 0.01);
        $settlement = new Settlement();
        $settlement->amount = $amount;
        $settlement->tax = $commission_tax;
        $settlement->total_amount = $amount + $commission_tax;
        $settlement->start_date = $first->created_at;
        $settlement->end_date = $last->created_at;
        $settlement->status = 'unpaid';
        $settlement->user_id = Auth::id();
        $settlement->save();

        Subscription::where('user_id', Auth::id())->whereNull('settlement_id')->update(['settlement_id' => $settlement->id]);



        return back()->with(['message' => trans('Settlement created Successfully')]);
    }

    public function getCommission($amount)
    {
        $partner_commissions = Option::where('key', 'partner_commissions')->first();
        $commission = 0;
        $commissions = json_decode($partner_commissions->value ?? null, true);

        foreach ($commissions as $commission) {
            if (($commission['form'] <= $amount) && ($commission['form'] <= $amount)) {
                $commission = $commission['commission'];
            }
        }

        return $commission;
    }

    //store academy mail function
    public function store_academy_mail()
    {

        $get_shop = Shop::All();
        foreach ($get_shop as $key => $shop) {
            $created = $shop->created_at->addDays(2)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['name'] = $shop->name;
                Mail::to($shop->email)->send(new StoreAcademyMail($data));
            }
        }
    }

    //store academy cron function
    public function StoreAcademyJob()
    {
        $this->store_academy_mail();
    }

    //partner academy mail function
    public function partner_academy_mail()
    {

        $get_partner = Partner::with(['Partner'])->get();
        foreach ($get_partner as $key => $partner) {
            $created = $partner->created_at->addDays(2)->format('Y-m-d');
            $partner_email = $partner->Partner->email;
            if ($created == Carbon::today()->toDateString()) {
                $data['name'] = $partner->Partner->first_name;
                Mail::to($partner_email)->send(new PartnerAcademyMail($data));
            }
        }
    }

    //partner academy cron function
    public function PartnerAcademyJob()
    {
        $this->partner_academy_mail();
    }


    //free trail fourth day mail function
    public function free_trail_fourth()
    {
        $shops = Shop::where('status', 'active')->whereHas('subscription', function ($q) {
            $q->where('status', 1);
        })
            ->with('domain', 'user', 'subscription')
            ->get();


        foreach ($shops as $key => $shop) {
            if ($shop->subscription->plan_id == 5) {
                $shop_email = $shop->user->email;
                $created = $shop->subscription->created_at->addDays(3)->format('Y-m-d');
                if ($created == Carbon::today()->toDateString()) {
                    $data['shop_name'] = $shop->name;
                    $data['full_domain'] = $shop->domain->full_domain;

                    Mail::to($shop_email)->send(new FreeTrailFourthMail($data));
                }
            }
        }
    }

    //free trail fourth day cron function
    public function FreeTrailFourthJob()
    {
        $this->free_trail_fourth();
    }

    //free trail thirteen day mail function
    public function free_trail_thirteen()
    {
        $shops = Shop::where('status', 'active')->whereHas('subscription', function ($q) {
            $q->where('status', 1);
        })
            ->with('domain', 'user', 'subscription')
            ->get();


        foreach ($shops as $key => $shop) {
            if ($shop->subscription->plan_id == 5) {
                $shop_email = $shop->user->email;
                $created = $shop->subscription->created_at->addDays(12)->format('Y-m-d');
                if ($created == Carbon::today()->toDateString()) {
                    $data['shop_name'] = $shop->name;
                    $data['full_domain'] = $shop->domain->full_domain;

                    Mail::to($shop_email)->send(new FreeTrailThirteenMail($data));
                }
            }
        }
    }

    //free trail thirteen day cron function
    public function FreeTrailThirteenJob()
    {
        $this->free_trail_thirteen();
    }

    //free trail tenth day mail function
    public function free_trail_tenth()
    {
        $shops = Shop::where('status', 'active')->whereHas('subscription', function ($q) {
            $q->where('status', 1);
        })
            ->with('domain', 'user', 'subscription')
            ->get();


        foreach ($shops as $key => $shop) {
            if ($shop->subscription->plan_id == 5) {
                $shop_email = $shop->user->email;
                $created = $shop->subscription->created_at->addDays(9)->format('Y-m-d');
                if ($created == Carbon::today()->toDateString()) {
                    $data['name'] = $shop->user->first_name;
                    $data['shop_name'] = $shop->name;
                    $data['full_domain'] = $shop->domain->full_domain;

                    Mail::to($shop_email)->send(new FreeTrailTenthMail($data));
                }
            }
        }
    }
    //free trail tenth day cron function
    public function FreeTrailTenthJob()
    {
        $this->free_trail_tenth();
    }

    //free trail get over
    public function free_trail_over()
    {
        $shops = Shop::where('status', 'active')->whereHas('subscription', function ($q) {
            $q->where('status', 1);
        })
            ->with('domain', 'user', 'subscription')
            ->get();


        foreach ($shops as $key => $shop) {
            if ($shop->subscription->plan_id == 5) {
                $shop_email = $shop->user->email;
                $created = $shop->subscription->created_at->addDays(14)->format('Y-m-d');
                if ($created == Carbon::today()->toDateString()) {
                    $data['shop_name'] = $shop->name;
                    $data['full_domain'] = $shop->domain->full_domain;

                    Mail::to($shop_email)->send(new FreeTrailOverMail($data));
                }
            }
        }
    }

    //free trail get over cron function
    public function FreeTrailOverJob()
    {
        $this->free_trail_over();
    }

    public function free_trail_testing_mail()
    {

        $shops = Shop::where('status', 'active')->where('id', 464)->whereHas('subscription', function ($q) {
            $q->where('status', 1);
        })
            ->with('domain', 'user', 'subscription')
            ->get();

        $shop_name = [];
        foreach ($shops as $key => $shop) {
            if ($shop->subscription->plan_id == 5) {
                $shop_email = $shop->user->email;
                $shop_name[] = $shop->name;

                $data['name'] = $shop->user->first_name;
                $data['shop_name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;

                Mail::to($shop_email)->send(new FreeTrailFourthMail($data));
                Mail::to($shop_email)->send(new FreeTrailTenthMail($data));
                Mail::to($shop_email)->send(new FreeTrailThirteenMail($data));
                Mail::to($shop_email)->send(new FreeTrailOverMail($data));
            }
        }
        return response()->json($shop_name ?? "no data");
    }

    public function store_academy_testing_mail()
    {

        $get_shop = Shop::where('id', 20)->get();
        $shop_name = [];

        foreach ($get_shop as $key => $shop) {
            $shop_name[] = $shop->name;
            $data['name'] = $shop->name;
            Mail::to($shop->email)->send(new StoreAcademyMail($data));
        }
        return response()->json($shop_name ?? "no data");
    }
    public function partner_academy_testing_mail()
    {
        $get_user = User::where('id', 10)->get();
        $partner_name = [];
        foreach ($get_user as $key => $partner) {
            $partner_name[] = $partner->first_name;
            $partner_email = $partner->email;

            $data['name'] = $partner->first_name;
            Mail::to($partner_email)->send(new PartnerAcademyMail($data));
        }
        return response()->json($partner_name ?? "no data");
    }

    public function ShopModeDuration()
    {
        $this->shop_mode_duration();
    }

    public function shop_mode_duration()
    {
        $shop_mode_duration_info = ShopOption::where('value', '<', Carbon::now())->where('key', 'shop_mode_duration')->get();
        foreach ($shop_mode_duration_info as $row) {
            $shop_mode = ShopOption::where('key', 'shop_mode')->where('shop_id', $row->shop_id)->update(["value" => 'online']);
            $shop_mode_duration = ShopOption::where('shop_id', $row->shop_id)->where('key', 'shop_mode_duration')->update(["value" => NULL]);
        }
    }

    ###### store day2 cron function #######
    public function StoreDayTwoJob()
    {
        $this->store_day_two_mail();
    }


    ###### store day2 email function ######
    public function store_day_two_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(1)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayTwoMail($data));
            }
        }
    }

    ###### store day3 cron function #######
    public function StoreDayThreeJob()
    {
        $this->store_day_three_mail();
    }

    ###### store day3 email function ######
    public function store_day_three_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(2)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayThreeMail($data));
            }
        }
    }

    ###### store day4 cron function #######
    public function StoreDayFourJob()
    {
        $this->store_day_fourth_mail();
    }

    ###### store day4 email function ######
    public function store_day_fourth_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(3)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayFourthMail($data));
            }
        }
    }


    ###### store day5 cron function #######
    public function StoreDayFiveJob()
    {
        $this->store_day_fivth_mail();
    }

    ###### store day5 email function ######
    public function store_day_fivth_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(4)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayFivthMail($data));
            }
        }
    }


    ###### store day6 cron function #######
    public function StoreDaySixJob()
    {
        $this->store_day_sixth_mail();
    }

    ###### store day6 email function ######
    public function store_day_sixth_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(5)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDaySixthMail($data));
            }
        }
    }


    ###### store day7 cron function #######
    public function StoreDaySevenJob()
    {
        $this->store_day_seventh_mail();
    }

    ###### store day7 email function ######
    public function store_day_seventh_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(6)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDaySeventhMail($data));
            }
        }
    }


    ###### store day8 cron function #######
    public function StoreDayEightJob()
    {
        $this->store_day_eight_mail();
    }

    ###### store day8 email function ######
    public function store_day_eight_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(7)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayEightMail($data));
            }
        }
    }

    ###### store day9 cron function #######
    public function StoreDayNineJob()
    {
        $this->store_day_nine_mail();
    }

    ###### store day9 email function ######
    public function store_day_nine_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(8)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayNineMail($data));
            }
        }
    }

    ###### store day10 cron function #######
    public function StoreDayTenJob()
    {
        $this->store_day_ten_mail();
    }

    ###### store day10 email function ######
    public function store_day_ten_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain', 'user')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(9)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['user_name'] = $shop->user->first_name;
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayTenMail($data));
            }
        }
    }


    ###### store day11 cron function #######
    public function StoreDayElevenJob()
    {
        $this->store_day_eleven_mail();
    }

    ###### store day11 email function ######
    public function store_day_eleven_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain', 'user')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(10)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['user_name'] = $shop->user->first_name;
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayElevenMail($data));
            }
        }
    }


    ###### store day12 cron function #######
    public function StoreDayTwelveJob()
    {
        $this->store_day_twelve_mail();
    }

    ###### store day12 email function ######
    public function store_day_twelve_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain', 'user')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(11)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['user_name'] = $shop->user->first_name;
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayTwelveMail($data));
            }
        }
    }


    ###### store day13 cron function #######
    public function StoreDayThirteenJob()
    {
        $this->store_day_thirteen_mail();
    }

    ###### store day13 email function ######
    public function store_day_thirteen_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain', 'user')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(12)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['user_name'] = $shop->user->first_name;
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayThirteenMail($data));
            }
        }
    }

    ###### store day14 cron function #######
    public function StoreDayFourteenJob()
    {
        $this->store_day_fourteen_mail();
    }

    ###### store day14 email function ######
    public function store_day_fourteen_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain', 'user')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(13)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['user_name'] = $shop->user->first_name;
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayFourteenMail($data));
            }
        }
    }

    ###### store day15 cron function #######
    public function StoreDayFifteenJob()
    {
        $this->store_day_fifteen_mail();
    }

    ###### store day15 email function ######
    public function store_day_fifteen_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain', 'user')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(14)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['user_name'] = $shop->user->first_name;
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayFifteenMail($data));
            }
        }
    }

    ###### store day16 cron function #######
    public function StoreDaySixteenJob()
    {
        $this->store_day_sixteen_mail();
    }

    ###### store day16 email function ######
    public function store_day_sixteen_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain', 'user')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(15)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['user_name'] = $shop->user->first_name;
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDaySixteenMail($data));
            }
        }
    }



    ###### store day17 cron function #######
    public function StoreDaySeventeenJob()
    {
        $this->store_day_seventeen_mail();
    }

    ###### store day17 email function ######
    public function store_day_seventeen_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain', 'user')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(16)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['user_name'] = $shop->user->first_name;
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDaySeventeenMail($data));
            }
        }
    }


    ###### store day18 cron function #######
    public function StoreDayEighteenJob()
    {
        $this->store_day_eighteen_mail();
    }

    ###### store day18 email function ######
    public function store_day_eighteen_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(17)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayEighteenMail($data));
            }
        }
    }

    ###### store day19 cron function #######
    public function StoreDayNineteenJob()
    {
        $this->store_day_nineteen_mail();
    }

    ###### store day19 email function ######
    public function store_day_nineteen_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(18)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayNineteenMail($data));
            }
        }
    }

    ###### store day20 cron function #######
    public function StoreDayTwentyJob()
    {
        $this->store_day_twenty_mail();
    }

    ###### store day20 email function ######
    public function store_day_twenty_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(19)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayTwentyMail($data));
            }
        }
    }

    ###### store day21 cron function #######
    public function StoreDayTwentyOneJob()
    {
        $this->store_day_twenty_one_mail();
    }

    ###### store day21 email function ######
    public function store_day_twenty_one_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(20)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayTwentyOneMail($data));
            }
        }
    }

    ###### store day22 cron function #######
    public function StoreDayTwentyTwoJob()
    {
        $this->store_day_twenty_two_mail();
    }

    ###### store day22 email function ######
    public function store_day_twenty_two_mail()
    {
        $shops = Shop::where('status', 'active')->where('shop_type','seller')
            ->with('domain')
            ->get();

        foreach ($shops as $key => $shop) {
            $created = $shop->created_at->addDays(21)->format('Y-m-d');
            if ($created == Carbon::today()->toDateString()) {
                $data['name'] = $shop->name;
                $data['full_domain'] = $shop->domain->full_domain;
                Mail::to($shop->email)->send(new StoreDayTwentyTwoMail($data));
            }
        }
    }





    public function manual_schedule_run($type)
    {
        //supplier settlement
        if ($type == "supplier_settlement") {
            Artisan::call('settlement:supplier');
        }

        if ($type == "product-price-update") {
            Artisan::call('make:product-price-update');
        }
        if ($type == "make_expirable_user") {
            Artisan::call('make:make_expirable_user');
        }
        if ($type == "send_mail_to_will_expire_plan_soon") {
            Artisan::call('make:send_mail_to_will_expire_plan_soon');
        }
        if ($type == "store_academy_mail") {
            Artisan::call('make:store_academy_mail');
        }
        if ($type == "partner_academy_mail") {
            Artisan::call('make:partner_academy_mail');
        }
        if ($type == "free_trail_fourth_mail") {
            Artisan::call('make:free_trail_fourth_mail');
        }

        if ($type == "free_trail_thirteen_mail") {
            Artisan::call('make:free_trail_thirteen_mail');
        }

        if ($type == "free_trail_tenth_mail") {
            Artisan::call('make:free_trail_tenth_mail');
        }

        if ($type == "free_trail_over_mail") {
            Artisan::call('make:free_trail_over_mail');
        }

        //store second day mail
        if ($type == "store_day_two") {
            Artisan::call('make:store_day_two');
        }

        //store three day mail
        if ($type == "store_day_three") {
            Artisan::call('make:store_day_three');
        }

        //store fourth day mail
        if ($type == "store_day_four") {
            Artisan::call('make:store_day_four');
        }

        //store five day mail
        if ($type == "store_day_five") {
            Artisan::call('make:store_day_five');
        }

        //store six day mail
        if ($type == "store_day_six") {
            Artisan::call('make:store_day_six');
        }

        //store seventh day mail
        if ($type == "store_day_seven") {
            Artisan::call('make:store_day_seven');
        }

        //store eight day mail
        if ($type == "store_day_eight") {
            Artisan::call('make:store_day_eight');
        }

        //store nine day mail
        if ($type == "store_day_nine") {
            Artisan::call('make:store_day_nine');
        }

        //store ten day mail
        if ($type == "store_day_ten") {
            Artisan::call('make:store_day_ten');
        }

        //store eleven day mail
        if ($type == "store_day_eleven") {
            Artisan::call('make:store_day_eleven');
        }
        //store twelve day mail
        if ($type == "store_day_twelve") {
            Artisan::call('make:store_day_twelve');
        }
        //store thirteen day mail
        if ($type == "store_day_thirteen") {
            Artisan::call('make:store_day_thirteen');
        }
        //store fourteen day mail
        if ($type == "store_day_fourteen") {
            Artisan::call('make:store_day_fourteen');
        }
        //store fifteen day mail
        if ($type == "store_day_fifteen") {
            Artisan::call('make:store_day_fifteen');
        }
        //store sixteen day mail
        if ($type == "store_day_sixteen") {
            Artisan::call('make:store_day_sixteen');
        }
        //store seventeen day mail
        if ($type == "store_day_seventeen") {
            Artisan::call('make:store_day_seventeen');
        }

        //store eighteen day mail
        if ($type == "store_day_eighteen") {
            Artisan::call('make:store_day_eighteen');
        }

        //store nineteen day mail
        if ($type == "store_day_nineteen") {
            Artisan::call('make:store_day_nineteen');
        }

        //store twenty day mail
        if ($type == "store_day_twenty") {
            Artisan::call('make:store_day_twenty');
        }

        //store twenty one day mail
        if ($type == "store_day_twenty_one") {
            Artisan::call('make:store_day_twenty_one');
        }

        //store twenty two day mail
        if ($type == "store_day_twenty_two") {
            Artisan::call('make:store_day_twenty_two');
        } else {
            Artisan::call('schedule:run');
        }

        return "Done";
    }

    ##### 10 Email Template Manuval run #####
    public function manual_email_template_run()
    {

        $shops = Shop::where('id', 783)->where('shop_type','seller')->where('status', 'active')
            ->with('domain', 'user')
            ->get();

        foreach ($shops as $key => $shop) {
            $data['user_name'] = $shop->user->first_name;
            $data['name'] = $shop->name;
            $data['full_domain'] = $shop->domain->full_domain;

            Mail::to($shop->email)->send(new StoreDayTwoMail($data));
            Mail::to($shop->email)->send(new StoreDayThreeMail($data));
            Mail::to($shop->email)->send(new StoreDayFourthMail($data));
            Mail::to($shop->email)->send(new StoreDayFivthMail($data));
            Mail::to($shop->email)->send(new StoreDaySixthMail($data));
            Mail::to($shop->email)->send(new StoreDaySeventhMail($data));
            Mail::to($shop->email)->send(new StoreDayEightMail($data));
            Mail::to($shop->email)->send(new StoreDayNineMail($data));
            Mail::to($shop->email)->send(new StoreDayTenMail($data));
            Mail::to($shop->email)->send(new StoreDayElevenMail($data));
        }
        return response()->json("Check Your Mail" ?? "no data");
    }

    ##### 11 Email Template Manuval run #####
    public function manual_email_template_run_pending()
    {
        $shops = Shop::where('id', 783)->where('shop_type','seller')->where('status', 'active')
            ->with('domain', 'user')
            ->get();

        foreach ($shops as $key => $shop) {
            $data['user_name'] = $shop->user->first_name;
            $data['name'] = $shop->name;
            $data['full_domain'] = $shop->domain->full_domain;


            Mail::to($shop->email)->send(new StoreDayTwelveMail($data));
            Mail::to($shop->email)->send(new StoreDayThirteenMail($data));
            Mail::to($shop->email)->send(new StoreDayFourteenMail($data));
            Mail::to($shop->email)->send(new StoreDayFifteenMail($data));
            Mail::to($shop->email)->send(new StoreDaySixteenMail($data));
            Mail::to($shop->email)->send(new StoreDaySeventeenMail($data));
            Mail::to($shop->email)->send(new StoreDayEighteenMail($data));
            Mail::to($shop->email)->send(new StoreDayNineteenMail($data));
            Mail::to($shop->email)->send(new StoreDayTwentyMail($data));
            Mail::to($shop->email)->send(new StoreDayTwentyOneMail($data));
            Mail::to($shop->email)->send(new StoreDayTwentyTwoMail($data));
        }
        return response()->json("Check Your Mail" ?? "no data");
    }
}
