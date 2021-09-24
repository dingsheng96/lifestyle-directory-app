<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\TacController;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\BannerController;
use App\Http\Controllers\Api\v1\CareerController;
use App\Http\Controllers\Api\v1\DeviceController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\LanguageController;
use App\Http\Controllers\Api\v1\MerchantController;
use App\Http\Controllers\Api\v1\NotificationController;


Route::post('login', [AuthController::class, 'login']);

Route::post('register', [AuthController::class, 'register']);

Route::post('password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('password/reset', [AuthController::class, 'resetPassword']);

Route::post('tac/verify', [TacController::class, 'verify']);

Route::post('languages', [LanguageController::class, 'languages']);
Route::post('languages/translations', [LanguageController::class, 'translations']);

Route::post('dashboard', [HomeController::class, 'index']);

Route::post('device/settings', [DeviceController::class, 'setup']);

Route::post('banners/show', [BannerController::class, 'show'])->name('banners.show');

Route::post('categories', [CategoryController::class, 'index']);
Route::post('categories/popular', [CategoryController::class, 'popular']);

Route::post('careers', [CareerController::class, 'index']);
Route::post('careers/show', [CareerController::class, 'show']);

Route::post('merchants', [MerchantController::class, 'index']);
Route::post('merchants/show', [MerchantController::class, 'show']);
Route::post('merchants/reviews', [MerchantController::class, 'reviews']);
Route::post('merchants/search', [MerchantController::class, 'search']);
Route::post('merchants/popular', [MerchantController::class, 'popular']);

Route::post('notifications', [NotificationController::class, 'index']);
Route::post('notifications/show', [NotificationController::class, 'show'])->name('notifications.show');
