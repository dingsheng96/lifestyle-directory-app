<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\RatingController;
use App\Http\Controllers\Api\v1\AccountController;
use App\Http\Controllers\Api\v1\WishlistController;

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

// Routes required login
Route::middleware(['auth:api', 'scope:' . User::USER_TYPE_MEMBER])->group(function () {

    require 'general.php';

    Route::post('profile', [AccountController::class, 'profile']);

    Route::post('ratings', [RatingController::class, 'index']);
    Route::post('ratings/store', [RatingController::class, 'store']);

    Route::post('wishlist', [WishlistController::class, 'index']);
    Route::post('wishlist/update', [WishlistController::class, 'update']);

    Route::post('profile/update', [AccountController::class, 'updateProfile']);

    Route::post('password/change', [AccountController::class, 'changePassword']);

    Route::post('logout', [AuthController::class, 'logout']);
});

require 'general.php';
