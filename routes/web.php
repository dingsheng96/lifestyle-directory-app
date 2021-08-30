<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\CountryStateController;

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

Route::middleware(['auth:web', 'verified'])->group(function () {

    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::resource('banners', BannerController::class);

    Route::resource('careers', CareerController::class);

    Route::resource('categories', CategoryController::class);

    Route::resource('account', AccountController::class);

    Route::resource('admins', AdminController::class);

    Route::resource('members', MemberController::class);

    Route::resource('merchants', MerchantController::class);

    Route::resource('merchants.branches', BranchController::class);

    Route::resource('roles', RoleController::class);

    Route::group(['prefix' => 'locale', 'as' => 'locale.'], function () {
        Route::resource('country-states', CountryStateController::class);
        Route::resource('country-states.cities', CityController::class);

        Route::resource('languages', LanguageController::class);
        Route::post('languages/{language}/translations/import', [TranslationController::class, 'import'])->name('languages.translations.import');
        Route::get('languages/{language}/translations/export', [TranslationController::class, 'export'])->name('languages.translations.export');
    });

    Route::delete('media/{media}', [MediaController::class])->name('media.destroy');
});


Route::group(['prefix' => 'data', 'as' => 'data.'], function () {

    Route::post('country-states/{country_state}/cities', [DataController::class, 'getCityFromCountryState'])->name('country-states.cities');
});

Route::group(['prefix' => 'app'], function ($query) {
    //
});
