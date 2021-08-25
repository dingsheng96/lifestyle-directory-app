<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('login');
});

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth', 'verified']], function () {

    Route::get('dashboard', 'HomeController@index')->name('dashboard');

    Route::resource('account', 'AccountController');

    Route::resource('admins', 'AdminController');

    Route::resource('members', 'MemberController');

    Route::resource('merchants', 'MerchantController');

    Route::resource('merchants.branches', 'BranchController');

    Route::resource('roles', 'RoleController');

    Route::resource('categories', 'CategoryController');

    Route::group(['prefix' => 'locale', 'as' => 'locale.'], function () {
        Route::resource('country-states', 'CountryStateController');
        Route::resource('country-states.cities', 'CityController');

        Route::resource('languages', 'LanguageController');
        Route::post('languages/{language}/translations/import', 'TranslationController@import')->name('languages.translations.import');
        Route::get('languages/{language}/translations/export', 'TranslationController@export')->name('languages.translations.export');
    });

    Route::get('activity-logs', 'ActivityLogController')->name('activity-logs.index');

    Route::delete('media/{media}', 'MediaController')->name('media.destroy');
});


Route::group(['prefix' => 'data', 'as' => 'data.'], function () {

    Route::post('country-states/{country_state}/cities', 'DataController@getCityFromCountryState')->name('country-states.cities');
});
