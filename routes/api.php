<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'as' => 'v1', 'namespace' => 'v1'], function () {

    Route::post('login', 'AuthController@login');

    Route::post('register', 'AuthController@register');

    Route::post('languages', 'LanguageController@languages');

    Route::post('languages/translations', 'LanguageController@translations');

    Route::post('home', 'HomeController@index');

    Route::post('categories', 'CategoryController@index');

    Route::post('merchants', 'MerchantController@index');

    Route::post('merchants/show', 'MerchantController@show');

    Route::group(['middleware' => ['auth:api', 'scope:member']], function () {

        Route::post('logout', 'AuthController@logout');
    });
});
