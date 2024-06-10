<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'partner.'], function () {

    Route::get('/login', 'LoginController@login')->name('login');
    Route::post('/login', 'LoginController@authenticate')->name('auth');
    Route::get('/register', 'LoginController@register_form')->name('registerform');
    Route::post('/register', 'LoginController@register')->name('register');
    Route::get('/otp', 'LoginController@otp')->name('otp');
    Route::post('/otp', 'LoginController@otp_verify')->name('otp_verify');
    Route::get('/resend_partner_otp/{id}', 'LoginController@resend_partner_otp')->name('resend_partner_otp');
    Route::get('/otp_page', 'LoginController@resend_error')->name('resend_error');

    Route::group(['middleware' => [ 'auth' ,'partner']], function () {

        Route::get('/profile', 'FrontendController@settings')->name('profile.settings');

        Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');
        Route::get('/dashboard/static', 'DashboardController@staticData')->name('dashboard.static');

        // Route::get('/shops', 'ShopController@index')->name('shop.index');
        // Route::get('/shops/create', 'ShopController@create')->name('shop.create');
        // Route::post('/shops', 'ShopController@store')->name('shop.store');
        // Route::get('/shops/{id}/edit', 'ShopController@store')->name('shop.edit');
        // Route::post('/shops/{id}', 'ShopController@store')->name('shop.update');

        Route::resource('shop', 'ShopController');
        Route::get('shop/plan/{id}', 'ShopController@planview')->name('shop.planedit');
        Route::post('shop/send-mail', 'ShopController@sendmail')->name('email.store');
        // Route::put('shop/planupdate/{id}', 'ShopController@updateplaninfo')->name('shop.updateplaninfo');
        Route::post('shops/destroy', 'ShopController@destroy')->name('shops.destroy');

        Route::resource('order', 'OrderController');
        Route::get('order/show/{id}', 'OrderController@show_old')->name('order.show_old');
        Route::get('order/invoice/{id}', 'OrderController@invoice')->name('order.invoice');

        // Route::get('/settlements', 'SettlementController@index')->name('settlements.index');

		Route::get('/make-payment/{id}', 'PlanController@make_payment')->name('make_payment');
		Route::get('plan-renew', 'PlanController@renew');
		Route::post('/make-charge/{id}', 'PlanController@make_charge')->name('make_payment_charge');


        Route::get('settlements', 'SettlementController@index')->name('settlement.index');
        Route::get('settlements/{id}', 'SettlementController@show')->name('settlement.show');
        Route::put('settlements/{id}', 'SettlementController@update')->name('settlement.update');
        Route::get('settlement', 'SettlementController@create')->name('settlement.create');

        Route::get('licenses', 'SubscriptionController@plans')->name('license.plans');
        Route::get('license', 'SubscriptionController@create_license')->name('license.create');
        Route::post('license/getprice', 'SubscriptionController@get_license_price')->name('license.getprice');
        Route::post('licence/make-charge', 'SubscriptionController@make_charge')->name('license.make_charge');
        Route::get('licence/payment-success', 'SubscriptionController@payment_success')->name('license.payment_success');
        Route::get('licence/store-shop/{id}', 'SubscriptionController@create_shop')->name('license.create_shop');
        Route::post('licence/store-shop', 'SubscriptionController@store_shop')->name('license.store_shop');

        Route::get('subscriptions', 'SubscriptionController@index')->name('subscription.index');
        Route::get('subscription/{id}', 'SubscriptionController@create')->name('subscription.create');
        Route::post('subscriptions/subscribe', 'SubscriptionController@subscribe')->name('subscription.subscribe');

        //payment methods
		Route::get('/payment-success', 'PlanController@success')->name('payment.success');
		Route::get('/payment-fail', 'PlanController@fail')->name('payment.fail');
		Route::get('/instamojo', '\App\Helper\Subscription\Instamojo@status')->name('instamojo.fallback');
		Route::get('/paypal', '\App\Helper\Subscription\Paypal@status')->name('paypal.fallback');
		Route::get('/toyyibpay', '\App\Helper\Subscription\Toyyibpay@status')->name('toyyibpay.fallback');
		Route::get('/payment-with/razorpay', '\App\Helper\Subscription\Razorpay@razorpay_view');
		Route::get('/payment_with_mollie', '\App\Helper\Subscription\Mollie@status');
		Route::post('/razorpay/status', '\App\Helper\Subscription\Razorpay@status')->name('razorpay.status');
		Route::post('/paystack/status', '\App\Helper\Subscription\Paystack@status');
		Route::get('/payment_with_mercado', '\App\Helper\Subscription\Mercado@status');

    });
});