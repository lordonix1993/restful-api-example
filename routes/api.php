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

Route::group(['middleware' => 'api'], function ($router) {
    Route::group(['prefix' => 'auth', 'namespace' => 'App\Http\Controllers\Api\Auth'], function ($router) {
        Route::post('logout', 'AuthController@logout')->middleware('jwt')->name('auth_logout');
        Route::post('me', 'AuthController@me')->middleware('jwt')->name('auth_me');;
        Route::post('login', 'AuthController@login')->name('auth_login');
        Route::post('register', 'AuthController@registration')->name('auth_registration');;
        Route::post('refresh', 'AuthController@refresh')->name('auth_refresh_token');
    });
});
