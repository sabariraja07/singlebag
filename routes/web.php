<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;

Route::post('logout', function () {
	if (Cache::has(request()->getHost() . '.admin')) {
		Cache::forget(request()->getHost() . '.admin');
		Cache::forget(request()->getHost() . '_current_shop_' . auth()->id());
		Cache::forget(request()->getHost() . '_current_shop_' . domain_info('shop_id'));
	}
	Auth::logout();
	if (Auth::guard('customer')->check()) {
		$usertype = Auth::guard('customer')->user()->user_type;
		Auth::guard('customer')->logout();
		return Redirect::to('user/login');
		// if($usertype == 'agent'){
		// 	Auth::guard('customer')->logout();
		// 	return Redirect::to('/login');
		// }
		// else{
		// Auth::guard('customer')->logout();
		// return Redirect::to('user/login');
		// }

	}

	return Redirect::to('/login');
})->name('logout');

Route::post('agent_logout', function () {
	if (Cache::has(request()->getHost() . '.admin')) {
		Cache::forget(request()->getHost() . '.admin');
		Cache::forget(request()->getHost() . '_current_shop_' . auth()->id());
		Cache::forget(request()->getHost() . '_current_shop_' . domain_info('shop_id'));
	}
	Auth::logout();
	if (Auth::guard('customer')->check()) {
		$usertype = Auth::guard('customer')->user()->user_type;
		if ($usertype == 'agent') {
			Auth::guard('customer')->logout();
			return Redirect::to('/login');
		} else {
			Auth::guard('customer')->logout();
			return Redirect::to('user/login');
		}
	}

	return Redirect::to('/login');
})->name('agent_logout');

// Auth::routes();
Auth::routes(['register' => false, 'logout' => false]);

Route::get('language/{locale}', function ($locale) {
	app()->setLocale($locale);
	session()->put('locale', $locale);
	return redirect()->back();
});

Route::get('/check', 'FrontendController@check');
Route::get('/pwa', function () {
	return redirect('/');
});
// Match my own domain
Route::group(['domain' => env('APP_URL')], function ($domain) {
	if (env('APP_WELCOME_URL', null) != null) {
		Route::get('/', function () {
			return redirect(env('APP_WELCOME_URL'));
		});
	} else {
		Route::get('/', 'FrontendController@welcome');
	}
	Route::get('/page/{slug}', 'FrontendController@page');
	Route::get('/about', 'FrontendController@about');
	Route::get('/service', 'FrontendController@service');
	Route::get('/pricing', 'FrontendController@pricing');
	Route::get('/make-translate', 'FrontendController@translate')->name('translate');
	Route::get('/contact', 'FrontendController@contact');
	Route::post('/sent-mail', 'FrontendController@send_mail')->name('send_mail');
	Route::get('merchant/register/{id?}', 'FrontendController@register_view')->name('merchant.form');
	Route::get('store/register', 'FrontendController@signup')->name('seller.signup');
	Route::get('supplier/register', 'FrontendController@signup')->name('supplier.signup');
	Route::get('reseller/register', 'FrontendController@signup')->name('reseller.signup');
	Route::post('seller-register/{id}', 'FrontendController@register')->name('merchant.register');
	Route::post('store-name-availability', 'FrontendController@check_store_availability');
	Route::get('/otp', 'FrontendController@otp')->name('otp');
	Route::post('/otp', 'FrontendController@otp_verify')->name('otp_verify');
	Route::get('/resend_store_otp/{id}', 'FrontendController@resend_store_otp')->name('resend_store_otp');
	Route::get('/otp_error_page', 'FrontendController@resend_error')->name('resend_error');
	Route::get('/email_not_verified', 'FrontendController@email_not_verified')->name('email_not_verified');
	Route::get('/account_error_page', 'FrontendController@account_not_active')->name('account_not_active');

	Route::post('/store_test_mail', 'FrontendController@store_test_mail')->name('store_test_mail');

	Route::post('store/register', 'FrontendController@register')->name('seller.register');
	Route::post('store/category', 'FrontendController@shopcategory')->name('seller.shopcategory');
	Route::post('store/description', 'FrontendController@shopdescription')->name('seller.shopdescription');
	Route::post('store/address', 'FrontendController@shopaddress')->name('seller.shopaddress');
	Route::post('store/bankdetails', 'FrontendController@shopbankdetails')->name('seller.shopbankdetails');
	Route::post('store/language', 'FrontendController@shoplanguage')->name('seller.shoplanguage');

	Route::get('/verify-store', 'FrontendController@verify_store_view')->name('verify.store_view');
	Route::post('/verify-store', 'FrontendController@verify_store_name')->name('verify.store_name');

	Route::get('/store-unavailable', 'FrontendController@custom_page')->name('store-unavailable');
	Route::get('/store-maintenance', 'FrontendController@store_maintenance')->name('store-maintenance');


	Route::get('/partner', 'FrontendController@partner');
	Route::get('/partner/faq', 'FrontendController@partner_faq');


	//Business Tools 

	Route::get('privacy-policy-generator/show/{id}', 'Frontend\PrivacyPolicyGeneratorController@show');
	Route::resource('privacy-policy-generator', 'Frontend\PrivacyPolicyGeneratorController');
	Route::get('terms-generator/show/{id}', 'Frontend\TermsGeneratorController@show');
	Route::resource('terms-generator', 'Frontend\TermsGeneratorController');
	Route::get('refund-generator/show/{id}', 'Frontend\RefundGeneratorController@show');
	Route::resource('refund-generator', 'Frontend\RefundGeneratorController');
	Route::get('qr-generator/show/{id}', 'Frontend\QRGeneratorController@show');
	Route::resource('qr-generator', 'Frontend\QRGeneratorController');
	Route::get('invoice-generator/show/{id}', 'Frontend\InvoiceGeneratorController@show');
	Route::resource('invoice-generator', 'Frontend\InvoiceGeneratorController');

	Route::group(['prefix' => 'cron'], function () {
		Route::get('/make-expire-order', 'CronController@makeExpireAbleCustomer');
		Route::get('/make-alert-before-expire-plan', 'CronController@send_mail_to_will_expire_plan_soon');
		Route::get('/reset_product_price', 'CronController@reset_product_price');
		Route::get('/store_academymail', 'CronController@store_academy_mail');
		Route::get('/partner_academymail', 'CronController@partner_academy_mail');
		Route::get('/free_trail_fourth', 'CronController@free_trail_fourth');
		Route::get('/free_trail_thirteen', 'CronController@free_trail_thirteen');
		Route::get('/free_trail_tenth', 'CronController@free_trail_tenth');
		Route::get('/free_trail_over', 'CronController@free_trail_over');
		Route::get('/shop_mode_duration', 'CronController@shop_mode_duration');
	});


	Route::group(['as' => 'merchant.', 'prefix' => 'merchant', 'middleware' => ['auth']], function () {
		Route::get('/dashboard', 'FrontendController@dashboard')->name('dashboard');
		Route::get('/make-payment/{id}', 'FrontendController@make_payment')->name('make_payment');
		Route::get('/plan', 'FrontendController@plans')->name('plan');
		Route::get('/profile', 'FrontendController@settings')->name('profile.settings');

		Route::post('/make-charge/{id}', 'FrontendController@make_charge')->name('make_payment_charge');
		Route::get('/payment-success', 'FrontendController@success')->name('payment.success');
		Route::get('/payment-fail', 'FrontendController@fail')->name('payment.fail');
		Route::get('/instamojo', '\App\Helper\Subscription\Instamojo@status')->name('instamojo.fallback');
		Route::get('/paypal', '\App\Helper\Subscription\Paypal@status')->name('paypal.fallback');
		Route::get('/toyyibpay', '\App\Helper\Subscription\Toyyibpay@status')->name('toyyibpay.fallback');
		Route::get('/payment-with/razorpay', '\App\Helper\Subscription\Razorpay@razorpay_view');
		Route::get('/payment/mollie', '\App\Helper\Subscription\Mollie@status');
		Route::get('/payment/mercado', '\App\Helper\Subscription\Mercado@status');
		Route::post('/razorpay/status', '\App\Helper\Subscription\Razorpay@status');
		Route::post('/paystack/status', '\App\Helper\Subscription\Paystack@status');
	});


	Route::get('/sitemap.xml', function () {
		return response(file_get_contents(base_path('sitemap.xml')), 200, [
			'Content-Type' => 'application/xml'
		]);
	});
});

// Match a subdomain of my domain
Route::group(['domain' => '{domain}', 'middleware' => ['domain']], function () {

	// Route::get('/login', function(){
	// 	return redirect(env('APP_URL') . '/login');
	// });

	Route::group(['namespace' => 'Frontend'], function () {
		Route::get('/', 'FrontendController@index');
		Route::get('product/{slug}/{id}', 'FrontendController@detail')->name('product.view');
		Route::get('product/{id}', 'FrontendController@product');
		Route::get('/shop', 'FrontendController@shop');
		Route::get('/cart', 'FrontendController@cart');
		Route::get('/wishlist', 'FrontendController@wishlist');
		Route::get('/wishlist/remove/{id}', 'CartController@wishlist_remove');
		Route::get('/category/{slug}/{id}', 'FrontendController@category');
		Route::get('/brand/{slug}/{id}', 'FrontendController@brand');
		Route::get('/trending', 'FrontendController@trending');
		Route::get('/best-sales', 'FrontendController@best_seles');
		Route::get('/get_cart', 'CartController@cart');
		Route::post('/add-to-cart', 'CartController@add_to_cart');
		Route::get('/add_to_wishlist/{id}', 'CartController@add_to_wishlist');
		Route::get('/remove_cart', 'CartController@remove_cart');
		Route::get('/cart_remove/{id}', 'CartController@cart_remove');
		Route::get('/cart-clear', 'CartController@cart_clear');
		Route::post('apply_coupon', 'CartController@apply_coupon')->name('apply_coupon');
		Route::get('/location-shipping-methods', 'FrontendController@shipping_methods');
		Route::get('/get-locations', 'FrontendController@get_locations');
		Route::get('/order_track', 'OrderTrackController@order_track')->name('order_status');
		Route::post('/order_track', 'OrderTrackController@order_track')->name('order_track');

		Route::get('/get_home_page_products', 'FrontendController@home_page_products');
		Route::post('make_order_validation', 'OrderController@store_validation');
		Route::post('make_order', 'OrderController@store');
		Route::post('make-reseller-order', 'OrderController@make_reseller_order');
		Route::get('/express', 'CartController@express');
		Route::get('/get_ralated_product_with_latest_post', 'FrontendController@get_ralated_product_with_latest_post');
		Route::get('/get_category_with_product/{limit}', 'FrontendController@get_category_with_product');
		Route::get('/get_brand_with_product/{limit}', 'FrontendController@get_brand_with_product');
		Route::get('/get_featured_category', 'FrontendController@get_featured_category');
		Route::get('/get_featured_brand', 'FrontendController@get_featured_brand');
		Route::get('/get_category', 'FrontendController@get_category');
		Route::get('/get_brand', 'FrontendController@get_brand');
		Route::get('/get_products', 'FrontendController@get_products');
		Route::get('/get_latest_products', 'FrontendController@get_latest_products');
		Route::get('/get_shop_products', 'FrontendController@get_shop_products');
		Route::get('/get_slider', 'FrontendController@get_slider');
		Route::get('/get_bump_adds', 'FrontendController@get_bump_adds');
		Route::get('/get_banner_adds', 'FrontendController@get_banner_adds');
		Route::get('/get_menu_category', 'FrontendController@get_menu_category');
		Route::get('/get_trending_products', 'FrontendController@get_trending_products');
		Route::get('/get_best_selling_product', 'FrontendController@get_best_selling_product');
		Route::get('/get_ralated_products', 'FrontendController@get_ralated_products');
		Route::get('/get_offerable_products', 'FrontendController@get_offerable_products');
		Route::get('/product_search', 'FrontendController@product_search');
		Route::get('/get_featured_attributes', 'FrontendController@get_featured_attributes');
		Route::get('/get_random_products/{limit}', 'FrontendController@get_random_products');
		Route::get('/get_shop_attributes', 'FrontendController@get_shop_attributes');
		Route::get('/checkout', 'FrontendController@checkout');
		Route::post('/make-review/{id}', 'ReviewController@store')->middleware('throttle:3,1');
		Route::get('/product-reviews/{id}', 'ReviewController@list');
		Route::get('/thanks', 'FrontendController@thanks');
		Route::get('/make_local', 'FrontendController@make_local');
		Route::get('/sitemap.xml', 'FrontendController@sitemap');
		Route::get('/page/{slug}/{id}', 'FrontendController@page');

		Route::group(['prefix' => 'user', 'as' => 'account.'], function () {
			Route::get('/login', 'UserController@login')->name('login')->middleware('guest');
			Route::get('/register', 'UserController@register')->name('register')->middleware('guest');
			Route::post('/register-user', 'UserController@register_user')->name('signup')->middleware('guest');
			Route::get('/dashboard', 'UserController@dashboard')->name('dashboard')->middleware('customer');
			Route::get('/orders', 'UserController@orders')->name('order')->middleware('customer');
			Route::get('/order/view/{id}', 'UserController@order_view')->name('order.view')->middleware('customer');
			Route::get('/download', 'UserController@download')->name('order.download')->middleware('customer');
			Route::get('/settings', 'UserController@settings')->name('setting')->middleware('customer');
			Route::post('/settings/update', 'UserController@settings_update')->name('setting.update')->middleware('customer');
			Route::get('/agent/dashboard', 'UserController@agent_dashboard')->name('agent_dashboard')->middleware('customer');
			Route::get('/agent/orders', 'UserController@agent_order')->name('agent_orders')->middleware('customer');
		});


		//payment gateway routes only
		Route::get('/payment/payment-success', 'OrderController@payment_success');
		Route::get('/payment/payment-fail', 'OrderController@payment_fail');
		Route::get('/payment/paypal', '\App\Helper\Order\Paypal@status');
		Route::get('/payment/instamojo', '\App\Helper\Order\Instamojo@status');
		Route::get('/payment/toyyibpay', '\App\Helper\Order\Toyyibpay@status');
		Route::get('/payment/mercado', '\App\Helper\Order\Mercado@status');
		Route::get('/payment/mollie', '\App\Helper\Order\Mollie@status');
		Route::get('/payment-with-stripe', '\App\Helper\Order\Stripe@view');
		Route::post('/payement/stripe', '\App\Helper\Order\Stripe@status');
		Route::get('/payment-with-razorpay', '\App\Helper\Order\Razorpay@view');
		Route::post('/payement/razorpay', '\App\Helper\Order\Razorpay@status');

		Route::get('/payment-with-paystack', '\App\Helper\Order\Paystack@view');
		Route::post('/payement/paystack', '\App\Helper\Order\Paystack@status');
	});
});

Route::group(['as' => 'author.', 'prefix' => 'author', 'namespace' => 'Admin', 'middleware' => ['auth', 'author']], function () {
	Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');
});

Route::post('user_profile_update', 'Seller\SettingController@profile_update')->name('my.profile.update');

Route::get('/', 'FrontendController@welcome');
Route::get('/page/{slug}', 'FrontendController@page');
Route::get('/about', 'FrontendController@about');
Route::get('/service', 'FrontendController@service');
Route::get('/pricing', 'FrontendController@pricing');
Route::get('/make-translate', 'FrontendController@translate')->name('translate');
Route::get('/contact', 'FrontendController@contact');
Route::post('/sent-mail', 'FrontendController@send_mail')->name('send_mail');
Route::get('merchant/register/{id}', 'FrontendController@register_view')->name('merchant.form');
Route::group(['prefix' => 'cron_job', 'namespace' => 'Admin'], function () {
	Route::get('/make_expirable_user', 'CronController@make_expirable_user');
	Route::get('/send_mail_to_will_expire_plan_soon', 'CronController@send_mail_to_will_expire_plan_soon');
	Route::get('/reset_product_price', 'CronController@reset_product_price');
	Route::get('/store_academymail', 'CronController@store_academy_mail');
	Route::get('/partner_academymail', 'CronController@partner_academy_mail');
	Route::get('/free_trail_fourth', 'CronController@free_trail_fourth');
	Route::get('/free_trail_thirteen', 'CronController@free_trail_thirteen');
	Route::get('/free_trail_tenth', 'CronController@free_trail_tenth');
	Route::get('/free_trail_over', 'CronController@free_trail_over');
	Route::get('/shop_mode_duration', 'CronController@shop_mode_duration');
});


Route::group(['as' => 'merchant.', 'prefix' => 'merchant', 'middleware' => ['auth']], function () {
	Route::get('/dashboard', 'FrontendController@dashboard')->name('dashboard');
	Route::get('/make-payment/{id}', 'FrontendController@make_payment')->name('make_payment');
	Route::get('/plan', 'FrontendController@plans')->name('plan');

	Route::post('/make-charge/{id}', 'FrontendController@make_charge')->name('make_payment_charge');
	Route::get('/payment-success', 'FrontendController@success')->name('payment.success');
	Route::get('/payment-fail', 'FrontendController@fail')->name('payment.fail');
	Route::get('/instamojo', '\App\Helper\Subscription\Instamojo@status')->name('instamojo.fallback');
	Route::get('/paypal', '\App\Helper\Subscription\Paypal@status')->name('paypal.fallback');
	Route::get('/toyyibpay', '\App\Helper\Subscription\Toyyibpay@status')->name('toyyibpay.fallback');
	Route::get('/payment-with/razorpay', '\App\Helper\Subscription\Razorpay@razorpay_view');
	Route::get('/payment/mollie', '\App\Helper\Subscription\Mollie@status');
	Route::post('/razorpay/status', '\App\Helper\Subscription\Razorpay@status');
	Route::post('/paystack/status', '\App\Helper\Subscription\Paystack@status');
	Route::get('/payment/mercado', '\App\Helper\Subscription\Mercado@status');
});

Route::group(['prefix' => 'profile', 'middleware' => ['auth']], function () {
	Route::get('/settings', 'FrontendController@settings')->name('profile.settings');
});


Route::post('/customers/attempt', 'Frontend\UserController@userLogin')->name('customer.login');
Route::post('/customer/login', 'Customer\LoginController@login')->middleware('guest');
Route::get('/user/password/reset', 'Customer\ForgotPasswordController@showLinkRequestForm')->middleware('guest');
Route::post('/user/password/email', 'Customer\ForgotPasswordController@sendResetOtp')->middleware('throttle:5,5');

//Reset Password Routes
Route::get('/user/password/otp', 'Customer\ResetPasswordController@otp')->middleware('guest');
Route::post('/user/password/reset', 'Customer\ResetPasswordController@resetPassword')->middleware('throttle:5,5');

//free trail testing Routes
Route::get('free_trail_testing_mail', 'Admin\CronController@free_trail_testing_mail');

//Academy Store Testing Routes
Route::get('store_academy_testing_mail', 'Admin\CronController@store_academy_testing_mail');

//Academy Partner Testing Routes
Route::get('partner_academy_testing_mail', 'Admin\CronController@partner_academy_testing_mail');

//Cron Job Testing Routes
Route::get('schedule_run/{type}', 'Admin\CronController@manual_schedule_run');

//10 Mail Email Template Testing
Route::get('email_templates', 'Admin\CronController@manual_email_template_run');

//11 Mail Email Template Testing
Route::get('pending_email_templates', 'Admin\CronController@manual_email_template_run_pending');

//expired mail and expired soon Routes
// Route::get('makeExpireAbleCustomer', 'Admin\CronController@makeExpireAbleCustomer');
// Route::get('makeExpireAbleCustomer/{days}', 'Admin\CronController@SendMailToWillExpirePlanWithInDay');

//store Academy Routes
// Route::get('store_academymail', 'Admin\CronController@store_academy_mail');

//partner Academy Routes
// Route::get('partner_academymail', 'Admin\CronController@partner_academy_mail');

// //free trail fourth day Routes
// Route::get('free_trail_fourth', 'Admin\CronController@free_trail_fourth');

//free trail thirteen day Routes
// Route::get('free_trail_thirteen', 'Admin\CronController@free_trail_thirteen');

//free trail tenth day Routes
// Route::get('free_trail_tenth', 'Admin\CronController@free_trail_tenth');

//free trail get over Routes
// Route::get('free_trail_over', 'Admin\CronController@free_trail_over');

Route::post('search-cities', 'GeneralController@search_cities');
Route::group(['namespace' => 'Frontend'], function () {
	Route::get('supplier-shipping-prices/{id}', 'OrderController@supplier_shipping_prices');
	Route::get('supplier-product-availability/{id}', 'OrderController@supplier_product_availability');
	Route::get('process-group-order/{id}', 'OrderController@process_group_order');
});
Route::get('migration-shipping-method', 'UpdateMigrationController@shipping_method_migration');


###### Mail Preview Route Function ######

Route::get('store-day2', function () {
	$markdown = new \Illuminate\Mail\Markdown(view(), config('mail.markdown'));
	return $markdown->render("email.store_day_sixth");
});

Route::get('store-mail/{day}', function ($day) {
	$markdown = new \Illuminate\Mail\Markdown(view(), config('mail.markdown'));
	return $markdown->render("email.store_day_" . $day, ['full_domain' => ""]);
});

Route::get('/push-notificaiton', 'WebNotificationController@index')->name('push-notificaiton');
Route::post('/store-token', 'WebNotificationController@storeToken')->name('store.token');
Route::post('/send-web-notification', 'WebNotificationController@sendWebNotification')->name('send.web-notification');

Route::get('/permutate', 'HomeController@permutate');

Route::group(['domain' => '{domain}', 'middleware' => ['domain']], function () {
	Route::group(['namespace' => 'Storefront', 'prefix' => 'v2'], function () {

		Route::get('/shop', 'ProductController@shop');
		Route::get('/products', 'ProductController@index');
		Route::get('product/{slug}/{id}', 'ProductController@show')->name('product.view');

		Route::get('/cart', 'CartController@index');
		Route::post('/cart', 'CartController@store');
		Route::put('/cart/{id}', 'CartController@update_item');
		Route::delete('/cart/{id}', 'CartController@remove_item');
		Route::get('/cart-clear', 'CartController@clear');
		Route::post('apply-coupon', 'CartController@apply_coupon')->name('apply_coupon');
		Route::post('update-address', 'CartController@update_address')->name('update_address');

		Route::get('/addresses', 'AddressController@index');
		Route::get('/address', 'AddressController@create');
		Route::post('/address', 'AddressController@store');
		Route::get('/address/{id}', 'AddressController@edit');
		Route::put('/address/{id}', 'AddressController@update');
		Route::delete('/address/{id}', 'AddressController@destroy');
	});
});
