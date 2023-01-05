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

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function ($router) {
    Route::post('logout', 'AuthController@logout')->middleware('jwt')->name('auth_logout_v1');
    Route::post('me', 'AuthController@me')->middleware('jwt')->name('auth_me_v1');
    Route::post('login', 'AuthController@login')->name('auth_login_v1');
    Route::post('register', 'AuthController@registration')->name('auth_registration_v1');
    Route::post('refresh', 'AuthController@refresh')->name('auth_refresh_token_v1');
});
