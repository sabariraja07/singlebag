<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'admin.'], function () {


    Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');
    Route::get('dashboard/static', 'AdminController@staticData')->name('dashboard.static');
    Route::get('dashboard/perfomance/{period}', 'AdminController@perfomance')->name('dashboard.perfomance');
    Route::get('dashboard/order_statics/{month}', 'AdminController@order_statics');
    Route::get('dashboard/visitors/{day}', 'AdminController@google_analytics');

    Route::resource('category', 'CategoryController');
    Route::post('categories/destroy', 'CategoryController@destroy')->name('category.destroy');

    Route::resource('shop-categories', 'ShopCategoryController');

    Route::get('license/commissions', 'PartnerController@view_commission')->name('license.commission.view');
    Route::get('partner/get-commission', 'PartnerController@get_commission')->name('partner.commission.show');
    Route::post('partner/update-commission', 'PartnerController@save_commission')->name('partner.commission.update');



    Route::get('/location/countries', 'CategoryController@countries')->name('country.index');
    Route::get('/location/countries/create', 'CategoryController@countryCreate')->name('country.create');
    Route::get('/location/cities', 'CategoryController@cities')->name('city.index');
    Route::get('/location/cities/create', 'CategoryController@cityCreate')->name('city.create');

    //role management
    //roles
    Route::resource('role', 'RoleController');
    Route::post('roles/destroy', 'RoleController@destroy')->name('roles.destroy');
    //users
    Route::resource('users', 'AdminController');
    Route::post('/userss/destroy', 'AdminController@destroy')->name('users.destroy');

    Route::resource('plan', 'PlanController');
    Route::get('plans/destroy/{id}', 'PlanController@destroy')->name('plans.destroy');

    Route::resource('domain', 'DomainController');
    Route::post('domains/destroy', 'DomainController@destroy')->name('domains.destroy');

    Route::resource('order', 'OrderController');
    Route::get('shops/settlements/order/show/{shop_id}/{id}', 'OrderController@shows')->name('orders.shows');
    Route::get('order/store/{id}/plans', 'OrderController@store_plans')->name('order.plans');
    Route::get('orders/licences', 'OrderController@licences')->name('order.licences');

    Route::get('store/{id}/plan-discount', 'OrderController@get_plan_discount');


    Route::post('orders/destroy', 'OrderController@destroy')->name('orders.destroy');
    Route::get('order/invoice/{id}', 'OrderController@invoice')->name('order.invoice');

    Route::resource('customer', 'CustomerController');
    Route::get('customer/plan/{id}', 'CustomerController@planview')->name('customer.planedit');
    Route::put('customer/planupdate/{id}', 'CustomerController@updateplaninfo')->name('customer.updateplaninfo');
    Route::post('customers/destroy', 'CustomerController@destroy')->name('customers.destroy');

    Route::resource('shop', 'ShopController');
    Route::get('shops/seller', 'ShopController@seller')->name('shop.seller');
    Route::get('shops/supplier', 'ShopController@supplier')->name('shop.supplier');
    Route::get('shops/reseller', 'ShopController@reseller')->name('shop.reseller');
    Route::get('shop/plan/{id}', 'ShopController@planview')->name('shop.planedit');
    Route::put('shop/planupdate/{id}', 'ShopController@updateplaninfo')->name('shop.updateplaninfo');
    Route::post('shops/destroy', 'ShopController@destroy')->name('shops.destroy');

    Route::get('shops/settlements', 'ShopSettlementController@index')->name('shop.settlements');
    Route::get('shops/settlements/{id}', 'ShopSettlementController@show')->name('shop.settlements.show');
    Route::put('shops/settlements/{id}', 'ShopSettlementController@update')->name('shop.settlement.update');

    Route::resource('partner', 'PartnerController');
    Route::put('partner/planupdate/{id}', 'PartnerController@updateplaninfo')->name('partner.updateplaninfo');
    Route::post('partner/destroy', 'PartnerController@destroy')->name('partner.destroy');
    Route::get('partner/otp/{id}', 'PartnerController@mobile_otp')->name('partner.otp');
    Route::get('partner/mobile_update/{id}', 'PartnerController@update_mobile')->name('partner.updatemobile');
    Route::get('/otp/{id}', 'PartnerController@partner_otp')->name('confirm.otp');
    Route::post('/otp/{id}', 'PartnerController@partner_otpverify')->name('otp_verify');


    Route::get('partners/settlements', 'SettlementController@index')->name('partners.settlements');
    Route::get('partners/settlements/{id}', 'SettlementController@show')->name('settlement.show');
    Route::put('partners/settlements/{id}', 'SettlementController@update')->name('settlement.update');


    Route::get('report', 'ReportController@index')->name('report');
    Route::resource('language', 'LanguageController');
    Route::get('languages/delete/{id}', 'LanguageController@destroy')->name('languages.delete');
    Route::post('languages/setActiveLanuguage', 'LanguageController@setActiveLanuguage')->name('languages.active');
    Route::post('languages/add_key', 'LanguageController@add_key')->name('language.add_key');

    Route::resource('payment-gateway', 'PaymentController');
    Route::resource('settings', 'SettingController');
    Route::resource('email', 'EmailController');

    Route::resource('emailtemplate', 'EmailtemplateController');

    Route::resource('marketing', 'MarketingController');

    Route::resource('template', 'TemplateController');
    Route::get('templates/delete/{id}', 'TemplateController@destroy')->name('templates.delete');


    Route::get('site-settings', 'SiteController@site_settings')->name('site.settings');
    Route::get('channel', 'SiteController@channel')->name('site.channel');
    Route::get('channel/create', 'SiteController@channel_create')->name('site.channel_create');
    Route::post('channel/store', 'SiteController@channel_store')->name('site.channel_store');
    Route::get('channel/edit/{id}', 'SiteController@channel_edit')->name('site.channel_edit');
    Route::post('channel/update/{id}', 'SiteController@channel_update')->name('site.channel_update');
    Route::post('channel/destroy/{id}', 'SiteController@channel_destroy')->name('site.channel_destroy');

    Route::get('language', 'SiteController@language')->name('site.language');
    Route::get('language/create', 'SiteController@language_create')->name('site.language_create');
    Route::post('language/store', 'SiteController@language_store')->name('site.language_store');
    Route::get('language/edit/{id}', 'SiteController@language_edit')->name('site.language_edit');
    Route::post('language/update/{id}', 'SiteController@language_update')->name('site.language_update');
    Route::post('language/destroy/{id}', 'SiteController@language_destroy')->name('site.language_destroy');

    Route::get('system-environment', 'SiteController@system_environment_view')->name('site.environment');
    Route::post('site_settings_update', 'SiteController@site_settings_update')->name('site_settings.update');
    Route::get('pwa_setting/request', 'SiteController@pwa_index')->name('pwa.index');
    Route::get('pwa_setting/edit/{id}', 'SiteController@pwa_edit')->name('pwa.edit');
    Route::post('pwa_setting/update/{id}', 'SiteController@pwa_update')->name('pwa.update');
    Route::get('pwa_setting/download/{id}', 'SiteController@pwa_download')->name('pwa.download');


    Route::post('env_update', 'SiteController@env_update')->name('env.update');

    Route::resource('cron', 'CronController');

    Route::get('/profile', 'AdminController@settings')->name('profile.settings');

    Route::get('custom-domain', 'CustomdomainController@index')->name('customdomain.index');
    Route::get('custom-domain/{id}', 'CustomdomainController@show')->name('customdomain.show');
    Route::get('custom-domain/edit/{id}', 'CustomdomainController@edit')->name('customdomain.edit');
    Route::post('custom-domain-delete', 'CustomdomainController@destroy')->name('customdomain.destroy');
    Route::put('custom-domain-update/{id}', 'CustomdomainController@update')->name('customdomain.update');

    Route::resource('menu', 'MenuController');
    Route::post('menus/delete', 'MenuController@destroy')->name('menues.destroy');
    Route::post('menus/MenuNodeStore', 'MenuController@MenuNodeStore')->name('menus.MenuNodeStore');

    Route::resource('cities', 'CitiesController');
    Route::resource('currencies', 'CurrencyController');

    Route::resource('shop_order', 'ShopOrderController');
    // Route::get('shops/seller/shop_orders', 'ShopOrderController@seller')->name('shoporder.seller');
    Route::get('shops/supplier/shop_orders', 'ShopOrderController@supplier')->name('shoporder.supplier');
    Route::get('shops/reseller/shop_orders', 'ShopOrderController@reseller')->name('shoporder.reseller');
});
