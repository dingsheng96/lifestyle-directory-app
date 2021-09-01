<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\RatingController;
use App\Http\Controllers\Api\v1\AccountController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\LanguageController;
use App\Http\Controllers\Api\v1\MerchantController;

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


Route::prefix('v1')->namespace('v1')->group(function () {

    Route::post('languages', [LanguageController::class, 'languages']);

    Route::post('pre-register', [AuthController::class, 'preRegister']);

    Route::post('login', [AuthController::class, 'login']);

    Route::middleware(['auth:api'])->group(function () {

        Route::post('languages/translations', [LanguageController::class, 'translations']);

        Route::post('register', [AuthController::class, 'register']);

        Route::post('profile', [AccountController::class, 'profile']);
        Route::post('profile/update', [AccountController::class, 'updateProfile']);
        Route::post('device/settings', [AccountController::class, 'deviceSettings']);

        Route::post('home', [HomeController::class, 'index']);

        Route::post('categories', [CategoryController::class, 'index']);
        Route::post('categories/populars', [CategoryController::class, 'popular']);

        Route::post('merchants', [MerchantController::class, 'index']);
        Route::post('merchants/show', [MerchantController::class, 'show']);
        Route::post('merchants/ratings', [MerchantController::class, 'ratings']);
        Route::post('merchants/ratings/store', [MerchantController::class, 'storeRatings']);

        Route::post('logout', [AuthController::class, 'logout']);
    });
});
