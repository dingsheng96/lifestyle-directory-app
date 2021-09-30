<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ResetPasswordController;

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
    return 'Home page';
});

Route::get('password/reset/success', [ResetPasswordController::class, 'resetPasswordSuccess'])->name('password.reset.success');

Auth::routes(['register' => false, 'login' => 'false', 'verify' => false]);
