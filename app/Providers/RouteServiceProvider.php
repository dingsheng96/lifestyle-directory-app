<?php

namespace App\Providers;

use App\Models\User;
use App\Helpers\Domain;
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

            ['web' => $web, 'api' => $api, 'prefix' => $prefix] = (new Domain())->getConfig();

            if ($prefix) {
                $this->prefixApiRoutes($web, $api);
            } else {
                $this->domainRoutes($web, $api);
            }

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

    protected function prefixApiRoutes(array $web, array $api)
    {
        foreach ($web as $value) {

            Route::prefix($value['prefix'])
                ->middleware('web')
                ->namespace($this->namespace . '\\' . $value['namespace'])
                ->name($value['route']['name'])
                ->group(base_path('routes/web/' . $value['route']['file']));
        }

        foreach ($api as $value) {

            Route::prefix($value['prefix'] . '/' . $value['version'])
                ->middleware('api')
                ->namespace($this->namespace . '\\' . $value['namespace'] . '\\' . $value['version'])
                ->name($value['route']['name'] . '.' . $value['version'] . '.')
                ->group(base_path('routes/api/' . $value['version'] . '/' . $value['route']['file']));
        }
    }

    protected function domainRoutes(array $web, array $api)
    {
        foreach ($web as $value) {

            Route::domain($value['url'])
                ->middleware('web')
                ->namespace($this->namespace . '\\' . $value['namespace'])
                ->name($value['route']['name'] . '.')
                ->group(base_path('routes/web/' . $value['route']['file']));
        }

        foreach ($api as $value) {

            Route::domain($value['url'])
                ->prefix($value['version'])
                ->middleware('api')
                ->namespace($this->namespace . '\\' . $value['namespace'] . '\\' . $value['version'])
                ->name($value['route']['name'] . '.' . $value['version'] . '.')
                ->group(base_path('routes/api/' . $value['version'] . '/' . $value['route']['file']));
        }
    }

    protected function routeBindings()
    {
        Route::bind('admin', function ($value) {

            return User::where('id', $value)->admin()->firstOrFail();
        });

        Route::bind('merchant', function ($value) {

            return User::where('id', $value)->merchant()
                ->mainMerchant()->approvedApplication()->firstOrFail();
        });

        Route::bind('branch', function ($value) {

            return User::where('id', $value)->merchant()
                ->subMerchant()->approvedApplication()->firstOrFail();
        });

        Route::bind('member', function ($value) {

            return User::where('id', $value)->member()->firstOrFail();
        });

        Route::bind('application', function ($value) {

            return User::where('id', $value)->merchant()
                ->mainMerchant()->pendingApplication()
                ->orWhere(function ($query) {
                    $query->rejectedApplication();
                })->first();
        });
    }
}
