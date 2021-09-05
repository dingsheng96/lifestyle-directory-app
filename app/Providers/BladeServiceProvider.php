<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('admin', function () {
            return Auth::user()->is_admin;
        });

        Blade::if('mainBranch', function () {

            $user = Auth::user()->load(['mainBranch', 'subBranches']);

            return $user->is_main_branch;
        });

        Blade::if('subBranch', function () {

            $user = Auth::user()->load(['mainBranch', 'subBranches']);

            return $user->is_sub_branch;
        });
    }
}
