<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
     protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {

        Route::pattern('domain', '[a-z0-9.\-]+');

        parent::boot();

        $this->configureRateLimiting();

        $this->routes(function () {
            
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace . '\API')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::prefix('admin')
                ->middleware(['web', 'auth' , 'admin'])
                ->namespace($this->namespace . '\Admin')
                ->group(base_path('routes/admin.php'));

            Route::prefix('seller')
                ->middleware(['web'])
                ->namespace($this->namespace . '\Seller')
                ->group(base_path('routes/seller.php'));

            Route::prefix('partner')
                ->middleware('web')
                ->namespace($this->namespace . '\Partner')
                ->group(base_path('routes/partner.php'));

            // Route::middleware('web')
            //     ->namespace($this->namespace)
            //     ->group(base_path('routes/additional.php'));    
        });

    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
