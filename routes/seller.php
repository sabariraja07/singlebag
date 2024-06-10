<?php

use Illuminate\Support\Facades\Route;

Route::group(['domain' => '{subdomain}.' . env('APP_PROTOCOLESS_URL'), 'middleware' => ['subdomain']], function () {

	Route::get('/login', function () {
		return redirect(env('APP_URL') . '/login');
	});

	Route::group(['as' => 'seller.', 'middleware' => ['auth']], function () {
		Route::get('/suspended', function () {
			return view('seller.suspended');
		})->name('suspended');

		Route::get('/make-payment/{id}', 'PlanController@make_payment')->name('make_payment');
		Route::get('plan-renew', 'PlanController@renew')->name('plan-renew');
		Route::get('plans', 'PlanController@index')->name('plan.index');

		Route::post('/make-charge/{id}', 'PlanController@make_charge')->name('make_payment_charge');

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


	Route::group(['as' => 'seller.', 'middleware' => ['auth', 'seller']], function () {
		Route::get('switch-store', 'DashboardController@switchStore')->name('switchstore');
		Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');
		Route::get('dashboard/static', 'DashboardController@staticData')->name('dashboard.static');
		Route::get('dashboard/perfomance/{period}', 'DashboardController@perfomance')->name('dashboard.perfomance');

		Route::get('dashboard/order_statics/{month}', 'DashboardController@order_statics');

		Route::resource('users', 'UserController');
		Route::get('user/term', 'UserController@term_index')->name('user.term');
		Route::post('user/term_store', 'UserController@term_store')->name('user.term_store');

		Route::get('dashboard/visitors/{day}', 'DashboardController@google_analytics');
		Route::resource('category', 'CategoryController');
		Route::post('categories/destroy', 'CategoryController@destroy')->name('category.destroy');
		Route::post('category/creation', 'CategoryController@category_creation')->name('category.new.create');

		Route::resource('brand', 'BrandController');
		Route::post('brands/destroy', 'BrandController@destroy')->name('brands.destroy');
		Route::post('brands/creation', 'BrandController@brand_creation')->name('brands.new.create');



		Route::resource('attribute', 'AttributeController');
		Route::post('attributes/destroy', 'AttributeController@destroy')->name('attributes.destroy');

		Route::resource('attribute-term', 'ChildAttributeController');
		Route::post('attributes-terms/destroy', 'ChildAttributeController@destroy')->name('attributes-terms.destroy');

		Route::resource('option', 'ProductOptionController');
		Route::post('options/destroy', 'ProductOptionController@destroy')->name('options.destroy');

		Route::resource('option-value', 'ProductOptionValueController');
		Route::post('options-value/destroy', 'ProductOptionValueController@destroy')->name('options-value.destroy');

		Route::resource('bump-ads', 'BumpAdController');
		Route::resource('banner-ads', 'BannerController');

		Route::resource('products', 'ProductController');
		// Route::get('products/create/new', 'ProductController@create_new')->name('products.create_new');
		Route::post('products/store/new', 'ProductController@store_new')->name('products.store_new');
		Route::get('products/edit/{id}', 'ProductController@edit_new')->name('products.edit_new');
		Route::get('suppliers', 'ResellerProductController@suppliers')->name('supplier.list');
		Route::get('reseller/add-product/{id}', 'ResellerProductController@add_product')->name('reseller.add_product');
		Route::get('supplier/search', 'ResellerProductController@search_supplier')->name('products.search_supplier');
		Route::get('supplier/products', 'ResellerProductController@search')->name('supplier.products');
		Route::get('supplier/products/autocomplete', 'ResellerProductController@autocomplete')->name('supplier_products.autocomplete');
		Route::get('product/{id}/{type}', 'ProductController@edit')->name('products.config');
		Route::get('product/{status}', 'ProductController@index')->name('products.list');
		Route::post('product/destroy', 'ProductController@destroy')->name('products.destroy');
		Route::post('product/seo/{id}', 'ProductController@seo')->name('products.seo');
		Route::post('product/import', 'ProductController@import')->name('products.import');
		Route::put('product/price/{id}', 'ProductController@price')->name('products.price');
		Route::post('product/variation/{id}', 'ProductController@variation')->name('products.variation');
		Route::post('product/store_group/{id}', 'ProductController@store_group')->name('products.store_group');
		Route::post('product/stock/{id}', 'ProductController@stock')->name('products.stock');
		Route::post('product/add_row', 'ProductController@add_row')->name('products.add_row');
		Route::post('product/option_update/{id}', 'ProductController@option_update')->name('products.option_update');
		Route::post('product/option_delete', 'ProductController@option_delete')->name('products.option_delete');
		Route::post('product/row_update', 'ProductController@row_update')->name('products.row_update');
		Route::post('product/stock_update/{id}', 'ProductController@stock_update')->name('products.stock_update');
		Route::get('pos', 'ProductController@pos_index')->name('products.pos');
		Route::get('supplier/products/{id}', 'ResellerProductController@show')->name('supplier.products.show');

		Route::resource('variant', 'VariantController');
		Route::post('variants/destroy', 'VariantController@destroy')->name('variants.destroy');

		Route::resource('media', 'ProductMediaController');
		Route::post('medias/destroy', 'ProductMediaController@destroy')->name('medias.destroy');

		Route::resource('file', 'FileController');
		Route::post('files/update', 'FileController@update')->name('files.update');
		Route::post('files/destroy', 'FileController@destroy')->name('files.destroy');

		Route::resource('inventory', 'InventoryController');

		Route::resource('location', 'LocationController');
		Route::post('locations/destroy', 'LocationController@destroy')->name('locations.destroy');

		Route::resource('shipping-methods', 'ShippingMethodController');
		Route::post('shipping-location', 'ShippingMethodController@update_location')->name('shipping-locations.store');
		Route::get('shipping-method/{id}/{type}', 'ShippingMethodController@remove_location')->name('shipping-locations.destroy');
		Route::post('shipping-method/destroy', 'ShippingMethodController@shippingmethod_destroy')->name('shipping-method.destroy');

		Route::resource('coupon', 'CouponController');
		Route::post('coupons/destroy', 'CouponController@destroy')->name('coupons.destroy');

		Route::resource('marketing', 'MarketingController');

		Route::resource('settings', 'SettingController');

		Route::resource('payment', 'GatewayController');

		Route::resource('transaction', 'TransactionController');


		Route::get('report', 'ReportController@index')->name('report.index');
		Route::get('revenue/perfomance/{period}', 'ReportController@revenue_perfomance')->name('revenue.perfomance');

		Route::resource('tax_classes', 'TaxController');
		Route::post('tax_classes/destroy', 'TaxController@action_destroy')->name('tax_classes.destroy');
        Route::get('tax_rate', 'TaxController@tax_index')->name('tax_rate.index');
		Route::get('tax_rate/create/{id}', 'TaxController@tax_create')->name('tax_rate.create');
		Route::post('tax_rate/store', 'TaxController@tax_store')->name('tax_rate.store');
		Route::post('tax_rate/destroy', 'TaxController@taxrate_destroy')->name('tax_rate.destroy');
		Route::get('tax_rate/edit/{id}', 'TaxController@taxrate_edit')->name('tax_rate.edit');
		Route::post('tax_rate/update/{id}', 'TaxController@taxrate_update')->name('tax_rate.update');

		Route::resource('shipping_location', 'ShippingLocationController');
		Route::post('shipping_location/destroy', 'ShippingLocationController@shipping_location_destroy')->name('shipping_location.destroy');
		Route::get('shipping_location/edit/{id}', 'ShippingLocationController@shipping_location_edit')->name('shipping_location.edit');
		Route::post('shipping_location/update/{id}', 'ShippingLocationController@shipping_location_update')->name('shipping_location.update');
		

		Route::group(['prefix' => 'setting'], function () {
			Route::resource('seo', 'SeoController');
			Route::get('privacy-policy-generator/show/{id}', 'PrivacyPolicyGeneratorController@show');
			Route::resource('privacy-policy-generator', 'PrivacyPolicyGeneratorController');
			Route::get('terms-generator/show/{id}', 'TermsGeneratorController@show');
			Route::resource('terms-generator', 'TermsGeneratorController');
			Route::get('refund-generator/show/{id}', 'RefundGeneratorController@show');
			Route::resource('refund-generator', 'RefundGeneratorController');
			Route::get('qr-generator/show/{id}', 'QRGeneratorController@show');
			Route::resource('qr-generator', 'QRGeneratorController');
			Route::get('invoice-generator/show/{id}', 'InvoiceGeneratorController@show');
			Route::resource('invoice-generator', 'InvoiceGeneratorController');

			Route::resource('theme', 'ThemeController');

			Route::resource('menu', 'MenuController');

			Route::resource('page', 'PageController');
			Route::post('pages/destroy', 'PageController@destroy')->name('pages.destroy');
			Route::resource('slider', 'SliderController');
		});

		Route::resource('customer', 'CustomerController');
		Route::get('customer/login/{id}', 'CustomerController@login')->name('customer.login');
		Route::post('customers/destroy', 'CustomerController@destroy')->name('customers.destroy');
		Route::get('user', 'CustomerController@user');



		Route::resource('delivery', 'DeliveryController');
		Route::get('/delivery/agent-view/{id}', 'DeliveryController@agent_view')->name('agent.view');
		Route::get('/delivery_export/{id}', 'DeliveryController@agent_report_download')->name('agent.export');
		Route::get('/delivery/agent/{id}', 'DeliveryController@login')->name('agent.login');
		Route::get('/agent/dashboard', 'DeliveryController@agent_dashboard')->name('agent_dashboard');
		Route::get('/agent/orders', 'DeliveryController@agent_order')->name('agent_orders');
		Route::post('agent/destroy', 'DeliveryController@destroy')->name('agent.destroy');

		Route::resource('order', 'OrderController');

		Route::resource('review', 'ReviewController');
		Route::post('reviews/destroy', 'ReviewController@destroy')->name('reviews.destroy');
		Route::get('order/cart/remove/{id}', 'OrderController@cartRemove')->name('cart.remove');
		Route::get('orders/{type}', 'OrderController@index')->name('orders.status');
		Route::get('orders/customer/checkout', 'OrderController@checkout')->name('checkout');
		Route::get('orders/invoice/{id}', 'OrderController@invoice')->name('invoice');
		Route::get('orders/invoice/receipt/{id}', 'OrderController@invoice_receipt')->name('invoice_receipt');
		Route::get('orders/delivert_agent/{id}', 'OrderController@delivery_agent')->name('agent');
		Route::post('orders/destroy', 'OrderController@destroy')->name('orders.destroy');
		Route::post('orders/apply_coupon', 'OrderController@apply_coupon')->name('orders.apply_coupon');
		Route::post('orders/make_order', 'OrderController@make_order')->name('orders.make_order');
		Route::post('orders/fulfillment', 'OrderController@destroy')->name('orders.method');
		Route::get('/delivery-agent-search', 'OrderController@search_delivery_agents')->name('orders.search-agent');
		Route::post('/delivery-agent-update/{id}', 'OrderController@delivery_agent_update')->name('orders.agent-update');
		Route::get('order/invoice/{id}', 'OrderController@plan_invoice')->name('order.invoice');

		Route::post('shop-mode', 'DomainController@shopmode')->name('shops.shopmode');
		Route::post('shop-mode-duration', 'DomainController@shopmodeduration')->name('shops.shopmodeduration');

		Route::get('/plan/{id}', 'PlanController@show')->name('plan.show');

		Route::get('/settings', 'SettingController@settings_view')->name('settings');
		Route::post('/profile_update', 'SettingController@profile_update')->name('profile.update');

		Route::get('/support', 'SettingController@support_view')->name('support');


		Route::get('setting/domain', 'DomainController@index')->name('domain.index');
		Route::post('domain/store', 'DomainController@store')->name('customdomain.store');
		Route::put('domain/update/{id}', 'DomainController@update')->name('customdomain.update');


		Route::get('shops', 'ShopController@index')->name('shops.index');
		Route::get('shops/create', 'ShopController@create')->name('shop.create');
		Route::post('shops', 'ShopController@store')->name('shop.store');

		Route::get('settlements', 'SettlementController@index')->name('settlements.index');
		Route::get('settlement', 'SettlementController@create')->name('settlements.create');
		Route::get('settlement/{id}', 'SettlementController@show')->name('settlements.show');
		Route::get('settlement/{id}/invoice', 'SettlementController@invoice')->name('settlements.invoice');
	});

	Route::get('user/orders/view', 'OrderController@agent_order')->name('agent.order.view');
});
