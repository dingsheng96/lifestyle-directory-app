<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\TranslationController;

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

    return redirect()->route('admin.login');
});

Auth::routes(['verify' => false, 'register' => false, 'reset_password' => false]);

require 'general.php';

Route::middleware(['auth:' . User::USER_TYPE_ADMIN])->group(function () {

    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::resource('profile', ProfileController::class)->only(['index', 'store']);

    Route::resource('applications', ApplicationController::class)->except(['store', 'create']);

    Route::resource('banners', BannerController::class);

    Route::resource('careers', CareerController::class);

    Route::resource('categories', CategoryController::class);

    Route::resource('admins', AdminController::class);

    Route::resource('members', MemberController::class);

    Route::post('merchants/{user}/verification/resend', 'MerchantController@resendVerificationEmail')
        ->name('merchants.verification.resend');

    Route::resource('merchants', MerchantController::class);

    Route::post('merchants/{merchant}/branches/import', 'BranchController@import')
        ->name('merchants.branches.import');

    Route::resource('merchants.branches', BranchController::class);

    Route::resource('roles', RoleController::class);

    Route::group(['prefix' => 'locales', 'as' => 'locales.'], function () {
        Route::resource('country-states', CountryStateController::class);
        Route::resource('country-states.cities', CityController::class);

        Route::resource('languages', LanguageController::class);
        Route::post('languages/{language}/translations/import', [TranslationController::class, 'import'])->name('languages.translations.import');
        Route::get('languages/{language}/translations/export', [TranslationController::class, 'export'])->name('languages.translations.export');
    });

    Route::post('media/reorder', [MediaController::class, 'reorder'])->name('media.reorder');
    Route::delete('media/{medium}', [MediaController::class, 'destroy'])->name('media.destroy');

    Route::resource('reviews', ReviewController::class)->only('index');

    Route::resource('configs', ConfigController::class)
        ->only('index', 'store');
});
