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

Route::middleware(['auth:' . User::USER_TYPE_ADMIN])->group(function () {

    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::resource('profile', ProfileController::class)->only(['index', 'store']);

    Route::resource('applications', ApplicationController::class)->except(['store', 'create']);

    Route::resource('banners', BannerController::class);

    Route::resource('careers', CareerController::class);

    Route::resource('categories', CategoryController::class);

    Route::resource('admins', AdminController::class);

    Route::resource('members', MemberController::class);

    Route::resource('merchants', MerchantController::class);

    Route::resource('merchants.branches', BranchController::class);

    Route::resource('roles', RoleController::class);

    Route::group(['prefix' => 'locales', 'as' => 'locales.'], function () {
        Route::resource('country-states', CountryStateController::class);
        Route::resource('country-states.cities', CityController::class);

        Route::resource('languages', LanguageController::class);
        Route::post('languages/{language}/translations/import', [TranslationController::class, 'import'])->name('languages.translations.import');
        Route::get('languages/{language}/translations/export', [TranslationController::class, 'export'])->name('languages.translations.export');
    });

    Route::delete('media/{media}', [MediaController::class, '__invoke'])->name('media.destroy');
});
