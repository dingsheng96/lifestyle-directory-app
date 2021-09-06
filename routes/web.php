<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;

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

Route::group(['prefix' => 'data', 'as' => 'data.'], function () {

    Route::post('country-states/{country_state}/cities', [DataController::class, 'getCityFromCountryState'])->name('country-states.cities');

    Route::post('geocoding', [DataController::class, 'getLocationCoordinates'])->name('geocoding');
});
