<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\BannerController;
use App\Http\Controllers\Api\v1\RatingController;
use App\Http\Controllers\Api\v1\AccountController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\LanguageController;
use App\Http\Controllers\Api\v1\MerchantController;
use App\Http\Controllers\Api\v1\WishlistController;
use App\Http\Controllers\Api\v1\DashboardController;
use App\Http\Controllers\Api\v1\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('v1')->namespace('v1')->name('v1.')->group(function () {

    Route::post('languages', [LanguageController::class, 'languages']);

    Route::post('pre-register', [AuthController::class, 'preRegister']);

    Route::post('login', [AuthController::class, 'login']);

    Route::middleware(['auth:api'])->group(function () {

        // routes for either Member or Guest scopes
        Route::middleware(['scope:' . User::USER_TYPE_GUEST . ',' . User::USER_TYPE_MEMBER])->group(function () {

            Route::post('dashboard', [DashboardController::class, 'index']);

            Route::post('languages/translations', [LanguageController::class, 'translations']);

            Route::post('device/settings', [AccountController::class, 'deviceSettings']);

            Route::post('profile', [AccountController::class, 'profile']);

            Route::post('categories', [CategoryController::class, 'index']);
            Route::post('categories/populars', [CategoryController::class, 'popular']);

            Route::post('merchants', [MerchantController::class, 'index']);
            Route::post('merchants/show', [MerchantController::class, 'show']);

            Route::post('ratings', [RatingController::class, 'index']);
            Route::post('ratings/store', [RatingController::class, 'store']);

            Route::post('wishlist', [WishlistController::class, 'index']);
            Route::post('wishlist/update', [WishlistController::class, 'update']);

            Route::post('notifications', [NotificationController::class, 'index']);
            Route::post('notifications/show', [NotificationController::class, 'show'])->name('notifications.show');

            Route::post('banners/show', [BannerController::class, 'show'])->name('banners.show');
        });

        // routes for Guest scope only
        Route::middleware(['scope:' . User::USER_TYPE_GUEST])->group(function () {

            Route::post('register', [AuthController::class, 'register']);
        });

        // routes for Member scope only
        Route::middleware(['scope:' . User::USER_TYPE_MEMBER])->group(function () {

            Route::post('profile/update', [AccountController::class, 'updateProfile']);

            Route::post('password/change', [AccountController::class, 'changePassword']);

            Route::post('logout', [AuthController::class, 'logout']);
        });
    });
});
