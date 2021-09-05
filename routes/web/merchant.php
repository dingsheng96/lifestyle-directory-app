<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\Merchant\HomeController;

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

Route::middleware(['auth:' . User::USER_TYPE_MERCHANT])->group(function () {

    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::resource('profile', ProfileController::class)->only(['index', 'store']);

    Route::resource('careers', CareerController::class);

    Route::resource('branches', BranchController::class);

    Route::resource('media', MediaController::class);

    Route::resource('operations', OperationHourController::class);

    Route::group(['prefix' => 'data', 'as' => 'data.'], function () {

        Route::post('country-states/{country_state}/cities', [DataController::class, 'getCityFromCountryState'])->name('country-states.cities');
    });
});
