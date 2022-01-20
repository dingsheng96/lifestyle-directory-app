<?php

use App\Mail\ContactUsEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

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
    return view('web.index');
})->name('home');

Route::get('term-condition', function () {
    return view('web.tnc');
})->name('term-condition');

Route::get('privacy-policy', function () {
    return view('web.policy');
})->name('privacy-policy');

Route::post('contact-us', [ContactController::class, 'sendEmail'])
    ->name('contact_us');
