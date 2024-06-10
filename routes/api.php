<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::domain('{subdomain}.' . env('APP_PROTOCOLESS_URL'))->group(function () {
    Route::group(['middleware' => ['subdomain'], 'namespace' => 'V1', 'prefix' => 'v1'], function () {
        Route::post('/verify-store-name', 'HomeController@verify_store_name');

        // Route::group(['middleware' => ['subdomain'], 'namespace' => 'POS', 'prefix' => 'pos'], function () {
        //     // Route::post('/auth/register', 'LoginController@register');

        //     Route::post('/auth/login', 'LoginController@login');

        //     Route::group(['middleware' => ['auth:sanctum']], function () {

        //         Route::get('/notifications', 'NotificationController@notifications');
        //         Route::post('/notifications', 'NotificationController@masrkAsRead');

        //         Route::post('/auth/logout', 'LoginController@logout');

        //         Route::get('/me', 'ProfileController@me');
        //         Route::get('/shop', 'ProfileController@shop');

        //         Route::get('categories', 'CategoryController@index');

        //         Route::get('/cart', 'CartController@index');
        //         Route::post('/add-to-cart/{id}', 'CartController@add_to_cart');
        //         Route::post('/add-cart', 'CartController@cart_add');
        //         Route::post('/apply-coupon', 'CartController@apply_coupon');
        //         Route::delete('/remove-cart', 'CartController@remove_cart');
        //         Route::delete('/clear-cart', 'CartController@cart_clear');

        //         Route::post('/checkout', 'OrderController@store');
        //         Route::get('/checkout', 'OrderController@checkout');
        //         Route::get('/shipping-methods', 'OrderController@shipping_methods');

        //         Route::get('/statistics', 'OrderController@statistics');

        //         Route::get('/customers', 'CustomerController@index');
        //         Route::get('/customer/{id}', 'CustomerController@fetch');
        //         Route::post('/customer', 'ProdCustomerroller@store');
        //         Route::post('/customer/{id}', 'CustomerController@update');

        //         Route::get('/products', 'ProductController@index');
        //         Route::get('/product/{id}', 'ProductController@fetch');

        //         Route::get('/orders', 'OrderController@index');
        //         Route::get('/order/{id}', 'OrderController@show');
        //         Route::post('/order/{id}', 'OrderController@update');
        //         Route::get('/order/invoice/{id}', 'OrderController@invoice');
        //     });
        // });

        Route::group(['namespace' => 'Auth', 'prefix' => 'admin'], function () {
            Route::post('/auth/register', 'LoginController@register');

            Route::post('/auth/login', 'LoginController@login');
            Route::post('/auth', 'LoginController@auth');
        });


        Route::group(['middleware' => ['auth:sanctum'], 'namespace' => 'Seller', 'prefix' => 'admin'], function () {

            Route::get('/notifications', 'NotificationController@notifications');
            Route::post('/notifications', 'NotificationController@masrkAsRead');

            Route::post('/auth/logout', 'LoginController@logout');

            Route::get('/me', 'ProfileController@me');
            Route::get('/shop', 'ProfileController@shop');
            Route::get('/update-password', 'ProfileController@update_password');
            Route::get('/update-profile', 'ProfileController@update_profile');

            Route::post('/settings', 'SettingController@store');
            Route::get('/settings/{type}', 'SettingController@show');
            Route::get('/timezones', 'SettingController@timezones');

            Route::get('channels', 'HomeController@channels');
            Route::get('get-categories', 'HomeController@categories');
            Route::get('get-brands', 'HomeController@brands');
            Route::get('get-attributes', 'HomeController@attributes');
            Route::get('get-cities', 'HomeController@cities');
            Route::get('get-states', 'HomeController@states');
            Route::get('get-countries', 'HomeController@countries');
            Route::get('/currencies', 'HomeController@currencies');

            Route::get('attributes', 'AttributeController@index');
            Route::post('attribute', 'AttributeController@store');
            Route::get('attribute/{id}', 'AttributeController@show');
            Route::post('attribute/{id}', 'AttributeController@update');
            Route::delete('attribute/{id}', 'AttributeController@destroy');

            Route::get('brands', 'BrandController@index');
            Route::post('brand', 'BrandController@store');
            Route::post('brand/{id}', 'BrandController@update');
            Route::get('brand/{id}', 'BrandController@show');
            Route::delete('brand/{id}', 'BrandController@destroy');

            Route::get('categories', 'CategoryController@index');
            Route::post('category', 'CategoryController@store');
            Route::post('category/{id}', 'CategoryController@update');
            Route::get('category/{id}', 'CategoryController@show');
            Route::delete('category/{id}', 'CategoryController@destroy');

            Route::get('/order-statics/{month}', 'DashboardController@order_statics');
            Route::get('/static-data', 'DashboardController@staticData');
            Route::get('/perfomance/{days}', 'DashboardController@perfomance');

            Route::post('attribute-term', 'ChildAttributeController@store');
            Route::post('attribute-term/{id}', 'ChildAttributeController@update');
            Route::get('attribute-term/{id}', 'ChildAttributeController@show');

            Route::get('product-options', 'ProductOptionController@index');
            Route::post('product-option', 'ProductOptionController@store');
            Route::post('product-option/{id}', 'ProductOptionController@update');
            Route::get('product-option/{id}', 'ProductOptionController@show');
            // Route::delete('product-option/{id}', 'ProductOptionController@destroy');

            // Route::get('product-values', 'ProductOptionController@index');
            Route::post('product-value', 'ProductOptionController@store_value');
            Route::post('product-value/{id}', 'ProductOptionController@update_value');
            Route::get('product-value/{id}', 'ProductOptionController@show_value');
            // Route::delete('product-value/{id}', 'ProductOptionController@destroy');

            Route::get('/products', 'ProductController@index');
            Route::post('/product', 'ProductController@store');
            Route::get('/product/{id}', 'ProductController@fetch');
            Route::get('/product/{id}/{type}', 'ProductController@show');
            Route::post('/product/{id}', 'ProductController@update');
            Route::delete('/product/{id}', 'ProductController@destory');
            Route::post('/product/{id}/associate', 'ProductController@associate');
            Route::post('/product/{id}/dissociate', 'ProductController@dissociate');
            Route::post('/product/{id}/price', 'ProductController@price');
            Route::post('/product/{id}/stock', 'ProductController@stock');
            Route::get('/product-variant/{id}', 'ProductController@fetch_variant');
            Route::post('/product-variant/{id}', 'ProductController@update_variant');

            // Route::post('products/seo/{id}', 'ProductController@seo');
            // Route::post('products/import', 'ProductController@import');
            // Route::put('products/price/{id}', 'ProductController@price');
            // Route::post('products/variation/{id}', 'ProductController@variation');
            // Route::get('product/{product_id}/variation/{id}', 'ProductController@variation_delete');
            // Route::post('products/stock/{id}', 'ProductController@stock');
            // Route::post('products/stock-update/{id}', 'ProductController@stock_update');
            // Route::post('products/add-option/{id}', 'ProductController@store_option');
            // Route::post('products/option-update/{id}', 'ProductController@update_option');
            // Route::post('products/option-delete', 'ProductController@option_delete');
            // Route::post('products/add-row', 'ProductController@add_row');
            // Route::post('products/row-update', 'ProductController@row_update');
            // Route::post('products/row-delete', 'ProductController@row_delete');

            // Route::get('medias', 'ProductMediaController@index');
            // Route::post('medias', 'ProductMediaController@store');
            // Route::post('medias/destroy', 'ProductMediaController@destroy')->name('medias.destroy');

            // Route::post('files', 'FileController@store');
            // Route::post('files/update', 'FileController@update')->name('files.update');
            // Route::post('files/destroy', 'FileController@destroy')->name('files.destroy');

            // Route::get('/products/inventories', 'InventoryController@index');
            // Route::post('/products/inventory/{id}', 'InventoryController@update');

            Route::get('/orders', 'OrderController@index');
            Route::get('/order/{id}', 'OrderController@show');
            Route::post('/order/{id}', 'OrderController@update');
            Route::get('/order/invoice/{id}', 'OrderController@invoice');
        });

        Route::group(['namespace' => 'Delivery', 'prefix' => 'delivery'], function () {
            Route::post('/auth/register', 'LoginController@register');

            Route::post('/auth/login', 'LoginController@login');

            Route::group(['middleware' => ['auth:sanctum']], function () {

                Route::get('/notifications', 'NotificationController@notifications');
                Route::post('/notifications', 'NotificationController@masrkAsRead');

                Route::post('/auth/logout', 'LoginController@logout');

                Route::get('/me', 'ProfileController@me');
                Route::get('/shop', 'ProfileController@shop');

                Route::get('/static-data', 'DashboardController@staticData');

                Route::get('/orders', 'OrderController@index');
                Route::get('/order/{id}', 'OrderController@show');
                Route::post('/order/{id}', 'OrderController@update');
                // Route::get('/order/invoice/{id}', 'OrderController@invoice');
            });
        });
    });
});
// Route::group(['namespace' => 'V1', 'prefix' => 'v1'], function () {
//     Route::post('/verify-store-name', 'HomeController@verify_store_name');

//     Route::group(['domain' => '{domain}', 'middleware' => ['domain']], function () {

//         Route::get('/category/{slug}/{id}', 'HomeController@category');
//         Route::get('/brand/{slug}/{id}', 'HomeController@brand');
//         Route::get('/location-shipping-methods', 'HomeController@shipping_methods');
//         Route::get('/get-locations', 'HomeController@get_locations');


//         Route::get('/cart', 'CartController@cart');
//         Route::get('/get_cart', 'CartController@cart');
//         Route::get('/add_to_cart/{id}', 'CartController@add_to_cart');
//         Route::post('/addtocart', 'CartController@cart_add');
//         Route::get('/remove_cart', 'CartController@remove_cart');
//         Route::get('/cart_remove/{id}', 'CartController@cart_remove');
//         Route::get('/cart-clear', 'CartController@cart_clear');
//         Route::post('apply_coupon', 'CartController@apply_coupon')->name('apply_coupon');


//         Route::get('/wishlist', 'WishlistController@wishlist');
//         Route::get('/get-wishlist', 'WishlistController@get_wishlist');
//         Route::get('/wishlist-count', 'WishlistController@wishlist_count');
//         Route::get('/wishlist/remove/{id}', 'CartController@wishlist_remove');
//         Route::get('/add_to_wishlist/{id}', 'CartController@add_to_wishlist');


//         Route::get('product/{id}', 'ProductController@product');
//         Route::get('/shop', 'ProductController@shop');
//         Route::get('/trending', 'ProductController@trending');
//         Route::get('/best-sales', 'ProductController@best_seles');
//         Route::get('/get_home_page_products', 'ProductController@home_page_products');
//         Route::get('/express', 'CartController@express');
//         Route::get('/get_ralated_product_with_latest_post', 'ProductController@get_ralated_product_with_latest_post');
//         Route::get('/get_category_with_product/{limit}', 'ProductController@get_category_with_product');
//         Route::get('/get_brand_with_product/{limit}', 'ProductController@get_brand_with_product');
//         Route::get('/get_featured_category', 'ProductController@get_featured_category');
//         Route::get('/get_featured_brand', 'ProductController@get_featured_brand');
//         Route::get('/get_category', 'ProductController@get_category');
//         Route::get('/get_brand', 'ProductController@get_brand');
//         Route::get('/get_products', 'ProductController@get_products');
//         Route::get('/get_latest_products', 'ProductController@get_latest_products');
//         Route::get('/get_shop_products', 'ProductController@get_shop_products');
//         Route::get('/get_slider', 'ProductController@get_slider');
//         Route::get('/get_bump_adds', 'ProductController@get_bump_adds');
//         Route::get('/get_banner_adds', 'ProductController@get_banner_adds');
//         Route::get('/get_menu_category', 'ProductController@get_menu_category');
//         Route::get('/get_trending_products', 'ProductController@get_trending_products');
//         Route::get('/get_best_selling_product', 'ProductController@get_best_selling_product');
//         Route::get('/get_ralated_products', 'ProductController@get_ralated_products');
//         Route::get('/get_offerable_products', 'ProductController@get_offerable_products');
//         Route::get('/product_search', 'ProductController@product_search');
//         Route::get('/get_featured_attributes', 'ProductController@get_featured_attributes');
//         Route::get('/get_random_products/{limit}', 'ProductController@get_random_products');
//         Route::get('/get_shop_attributes', 'ProductController@get_shop_attributes');
//         Route::get('/checkout', 'ProductController@checkout');
//         Route::get('/thanks', 'ProductController@thanks');
//         Route::get('/make_local', 'ProductController@make_local');
//         Route::get('/sitemap.xml', 'ProductController@sitemap');
//         Route::get('/page/{slug}/{id}', 'ProductController@page');


//         Route::post('/make-review/{id}', 'ReviewController@store')->middleware('throttle:1,1');
//         Route::get('/product-reviews/{id}', 'ReviewController@list');

//         Route::post('make_order', 'OrderController@store');

//         Route::group(['prefix' => 'user', 'as' => 'account.'], function () {
//             Route::get('/login', 'UserController@login')->name('login')->middleware('guest');
//             Route::get('/register', 'UserController@register')->name('register')->middleware('guest');
//             Route::post('/register-user', 'UserController@register_user')->name('signup')->middleware('guest');
//             Route::get('/dashboard', 'UserController@dashboard')->name('dashboard')->middleware('customer');
//             Route::get('/orders', 'UserController@orders')->name('order')->middleware('customer');
//             Route::get('/order/view/{id}', 'UserController@order_view')->name('order.view')->middleware('customer');
//             Route::get('/download', 'UserController@download')->name('order.download')->middleware('customer');
//             Route::get('/settings', 'UserController@settings')->name('setting')->middleware('customer');
//             Route::post('/settings/update', 'UserController@settings_update')->name('setting.update')->middleware('customer');
//         });
//     });
// });
