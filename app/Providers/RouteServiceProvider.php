<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
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
    public const HOME = '/dashboard';

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
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace . '\\Api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });

        $this->routeBindings();
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

    protected function routeBindings()
    {
        Route::bind('admin', function ($value) {
            return User::where('id', $value)
                ->whereHas('roles', function ($query) {
                    $query->where('name', Role::ROLE_SUPER_ADMIN);
                })
                ->firstOrFail();
        });

        Route::bind('merchant', function ($value) {
            return User::where('id', $value)
                ->whereHas('roles', function ($query) {
                    $query->where('name', Role::ROLE_MERCHANT_1)
                        ->orWhere('name', Role::ROLE_MERCHANT_2);
                })
                ->firstOrFail();
        });

        Route::bind('member', function ($value) {
            return User::where('id', $value)
                ->whereHas('roles', function ($query) {
                    $query->where('name', Role::ROLE_MEMBER);
                })
                ->firstOrFail();
        });
    }
}
