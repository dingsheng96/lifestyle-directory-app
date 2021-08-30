<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        $this->app['request']->server
            ->set('HTTPS', $this->app->environment() != 'local');

        // set default string length
        Schema::defaultStringLength(255);

        $this->observers();
    }

    protected function observers()
    {
        User::observe(UserObserver::class);
    }
}
