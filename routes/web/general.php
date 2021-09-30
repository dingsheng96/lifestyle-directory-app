<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;

Route::group(['prefix' => 'data', 'as' => 'data.'], function () {

    Route::post('country-states/{country_state}/cities', [DataController::class, 'getCityFromCountryState'])->name('country-states.cities');

    Route::post('geocoding', [DataController::class, 'getLocationCoordinates'])->name('geocoding');
});
