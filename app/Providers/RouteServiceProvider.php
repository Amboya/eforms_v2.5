<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
//    public const HOME = '/home';
      public const HOME = '/main/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            //API ROUTES
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            //MAIN ROUTES
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            //SUBSISTENCE ROUTES
            Route::middleware(['web','auth'])
                ->name('subsistence.')
                ->prefix('subsistence')
                ->namespace($this->namespace)
                ->group(base_path('routes/subsistence.php'));

            //TRIP ROUTES
            Route::middleware(['web','auth'])
//                ->name('trip.')
                ->prefix('trip')
                ->namespace($this->namespace)
                ->group(base_path('routes/trips.php'));

            //PETTY-CASH ROUTES
            Route::middleware(['web','auth'])
//                ->name('petty.cash.')
                ->prefix('petty/cash')
                ->namespace($this->namespace)
                ->group(base_path('routes/petty_cash.php'));


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
