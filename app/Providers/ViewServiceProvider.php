<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    protected $composers = [
        \App\Http\View\Composers\DefaultPreviewComposer::class => [
            'merchant.*', 'category.*', 'member.*'
        ],
        \App\Http\View\Composers\CategoryComposer::class => [
            'merchant.*'
        ],
        \App\Http\View\Composers\CountryStateComposer::class => [
            'merchant.*'
        ],
        \App\Http\View\Composers\ActiveStatusComposer::class => [
            'merchant.*', 'admin.*', 'member.*'
        ],
        // \App\Http\View\Composers\MerchantComposer::class => [
        //     '*'
        // ],
        \App\Http\View\Composers\PermissionModuleComposer::class => [
            'role.*'
        ],
    ];

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
        foreach ($this->composers as $composer => $views) {
            view()->composer($views, $composer);
        }
    }
}
