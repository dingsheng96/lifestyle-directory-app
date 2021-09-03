<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\BannerController;
use App\Http\Controllers\Api\v1\CareerController;
use App\Http\Controllers\Api\v1\RatingController;
use App\Http\Controllers\Api\v1\AccountController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\LanguageController;
use App\Http\Controllers\Api\v1\MerchantController;
use App\Http\Controllers\Api\v1\WishlistController;
use App\Http\Controllers\Api\v1\HomeController;
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

Route::post('languages', [LanguageController::class, 'languages']);

Route::post('pre-register', [AuthController::class, 'preRegister']);

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:api', 'scope:' . User::USER_TYPE_GUEST])->group(function () {

    Route::post('register', [AuthController::class, 'register']);

    Route::post('dashboard', [HomeController::class, 'index']);

    Route::post('languages/translations', [LanguageController::class, 'translations']);

    Route::post('device/settings', [AccountController::class, 'deviceSettings']);

    Route::post('profile', [AccountController::class, 'profile']);

    Route::post('banners/show', [BannerController::class, 'show'])->name('banners.show');

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

    Route::post('careers', [CareerController::class, 'index']);
    Route::post('careers/show', [CareerController::class, 'show']);
});
