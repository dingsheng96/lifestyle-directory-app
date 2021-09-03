<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    protected $composers = [
        \App\Http\View\Composers\DefaultPreviewComposer::class => [
            'admin.merchant.*', 'admin.category.*', 'admin.member.*', 'admin.banner.*', 'merchant.branch.*'
        ],
        \App\Http\View\Composers\CategoryComposer::class => [
            'admin.merchant.*'
        ],
        \App\Http\View\Composers\CountryStateComposer::class => [
            'admin.merchant.*', 'merchant.branch.*'
        ],
        \App\Http\View\Composers\StatusComposer::class => [
            'admin.merchant.*', 'admin.admin.*', 'admin.member.*', 'admin.banner.*', 'admin.category.*', 'admin.career.*',
            'merchant.career.*', 'merchant.branch.*'
        ],
        \App\Http\View\Composers\RoleComposer::class => [
            'admin.admin.*'
        ],
        \App\Http\View\Composers\PermissionModuleComposer::class => [
            'admin.role.*'
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
