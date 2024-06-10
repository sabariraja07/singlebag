<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Channel;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Location;
use App\Models\OrderItem;
use Illuminate\Support\Arr;
use App\Models\Transaction;
use App\Observers\OrderObserver;
use App\Managers\PaymentManager;
use App\Managers\DiscountManager;
use App\Observers\AddressObserver;
use App\Observers\ChannelObserver;
use App\Base\CartSessionInterface;
use App\Observers\CurrencyObserver;
use App\Observers\LanguageObserver;
use App\Observers\LocationObserver;
use App\Observers\CartItemObserver;
use App\Observers\OrderItemObserver;
use App\Managers\CartSessionManager;
use App\Base\PaymentManagerInterface;
use App\Base\DiscountManagerInterface;
use App\Observers\TransactionObserver;
use Illuminate\Support\ServiceProvider;
use App\Base\StorefrontSessionInterface;
use App\Managers\StorefrontSessionManager;

class SinglebagServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton(PaymentManagerInterface::class, function ($app) {
            return $app->make(PaymentManager::class);
        });

        // $this->app->bind('test', function () {
        //     return new \App\Test\TestFacades;
        // });

        $this->app->singleton(StorefrontSessionInterface::class, function ($app) {
            return $app->make(StorefrontSessionManager::class);
        });

        $this->app->singleton(CartSessionInterface::class, function ($app) {
            return $app->make(CartSessionManager::class);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'adminhub');

        Arr::macro('permutate', [\App\Utils\Arr::class, 'permutate']);
    }

    /**
     * Register the observers used in Singlebag.
     */
    protected function registerObservers(): void
    {
        Channel::observe(ChannelObserver::class);
        Language::observe(LanguageObserver::class);
        Location::observe(LocationObserver::class);
        Currency::observe(CurrencyObserver::class);
        CartItem::observe(CartItemObserver::class);
        Order::observe(OrderObserver::class);
        OrderItem::observe(OrderItemObserver::class);
        Address::observe(AddressObserver::class);
        Transaction::observe(TransactionObserver::class);
    }
}
