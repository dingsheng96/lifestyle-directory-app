<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::prefix('artisan')
    ->group(function () {

        Route::get('migrate', function () {
            Artisan::call('migrate');
        });

        Route::get('db-seeder', function () {
            Artisan::call('db:seed');
        });
    });
