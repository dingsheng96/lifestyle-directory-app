<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Merchant\HomeController;
use App\Http\Controllers\Merchant\MediaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    return redirect()->route('merchant.login');
});

Auth::routes(['verify' => true]);

require 'general.php';

Route::middleware(['auth:' . User::USER_TYPE_MERCHANT])->group(function () {

    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::resource('profile', ProfileController::class)->only(['index', 'store']);

    Route::resource('careers', CareerController::class);

    Route::resource('branches', BranchController::class);

    Route::post('media/reorder', [MediaController::class, 'reorder'])->name('media.reorder');

    Route::resource('media', 'MediaController')->except(['show', 'edit']);

    Route::get('reviews', [HomeController::class, 'reviewIndex'])->name('reviews.index');

    Route::get('visitors', [HomeController::class, 'visitorHistoryIndex'])->name('visitors.index');
});
