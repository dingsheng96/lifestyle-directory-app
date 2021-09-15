<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\BannerController;
use App\Http\Controllers\Api\v1\CareerController;
use App\Http\Controllers\Api\v1\DeviceController;
use App\Http\Controllers\Api\v1\RatingController;
use App\Http\Controllers\Api\v1\AccountController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\LanguageController;
use App\Http\Controllers\Api\v1\MerchantController;
use App\Http\Controllers\Api\v1\WishlistController;
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

Route::post('login', [AuthController::class, 'login']);

Route::post('register', [AuthController::class, 'register']);

Route::post('languages', [LanguageController::class, 'languages']);
Route::post('languages/translations', [LanguageController::class, 'translations']);

Route::post('dashboard', [HomeController::class, 'index']);

Route::post('device/settings', [DeviceController::class, 'setup']);

Route::post('banners/show', [BannerController::class, 'show'])->name('banners.show');

Route::post('categories', [CategoryController::class, 'index']);
Route::post('categories/popular', [CategoryController::class, 'popular']);

Route::post('merchants', [MerchantController::class, 'index']);
Route::post('merchants/show', [MerchantController::class, 'show']);
Route::post('merchants/reviews', [MerchantController::class, 'reviews']);
Route::post('merchants/search', [MerchantController::class, 'search']);
Route::post('merchants/popular', [MerchantController::class, 'popular']);

Route::post('notifications', [NotificationController::class, 'index']);
Route::post('notifications/show', [NotificationController::class, 'show'])->name('notifications.show');

Route::post('careers', [CareerController::class, 'index']);
Route::post('careers/show', [CareerController::class, 'show']);

// Routes required login
Route::middleware(['auth:api', 'scope:' . User::USER_TYPE_MEMBER])->group(function () {

    Route::post('profile', [AccountController::class, 'profile']);

    Route::post('ratings', [RatingController::class, 'index']);
    Route::post('ratings/store', [RatingController::class, 'store']);

    Route::post('wishlist', [WishlistController::class, 'index']);
    Route::post('wishlist/update', [WishlistController::class, 'update']);

    Route::post('profile/update', [AccountController::class, 'updateProfile']);

    Route::post('password/change', [AccountController::class, 'changePassword']);

    Route::post('logout', [AuthController::class, 'logout']);
});
