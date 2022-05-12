<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        // force http to https when not in local
        if ($this->app->environment() != 'local') {
            URL::forceScheme('https');
        }

        // set default string length
        Schema::defaultStringLength(255);

        Password::defaults(function () {
            return Password::min(8);
        });
    }
}
