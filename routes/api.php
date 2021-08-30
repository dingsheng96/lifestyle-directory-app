<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\RatingController;
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

    Route::post('login', [AuthController::class, 'login']);

    Route::post('register', [AuthController::class, 'register']);

    Route::post('languages', [LanguageController::class, 'languages']);
    Route::post('languages/translations', [LanguageController::class, 'translations']);

    Route::middleware(['auth:api', 'scope:member'])->group(function () {

        Route::post('home', [HomeController::class, 'index']);

        Route::post('categories', [CategoryController::class, 'index']);

        Route::post('merchants', [MerchantController::class, 'index']);
        Route::post('merchants/show', [MerchantController::class, 'show']);
        Route::post('merchants/ratings', [MerchantController::class, 'ratings']);
        Route::post('merchants/ratings/store', [MerchantController::class, 'storeRatings']);

        Route::post('logout', [AuthController::class, 'logout']);
    });
});
