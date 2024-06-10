<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        Validator::extend('email_address', function ($attribute, $value, $parameters, $validator) {
            return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $value)) ? FALSE : TRUE;
        });

        $this->app['view']->addNamespace('electrobag', base_path() . '/Themes/Electrobag/views');
        $this->app['view']->addNamespace('singlebag', base_path() . '/Themes/Singlebag/views');
        // $this->app['view']->addNamespace('bigbag', base_path() . '/Themes/bigbag');
        $this->app['view']->addNamespace('multibag', base_path() . '/Themes/Multibag/views');
        $this->app['view']->addNamespace('fashionbag', base_path() . '/Themes/Fashionbag/views');

        Collection::macro('flattenTree', function ($childrenField) {
            $result = collect();

            foreach ($this->items as $item) {
                $result->push($item);

                if ($item->$childrenField instanceof Collection) {
                    $result = $result->merge($item->$childrenField->flattenTree($childrenField));
                }
            }

            return $result;
        });
    }
}
