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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('register', 'api\JWTAuthController@register');
    Route::post('login', 'api\JWTAuthController@login');
    Route::post('logout', 'api\JWTAuthController@logout');
    Route::post('refresh', 'api\JWTAuthController@refresh');
    Route::get('profile', 'api\JWTAuthController@profile');

});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'products'

], function ($router) {
	
    Route::post('/', 'api\ProductController@create');
    Route::get('/', 'api\ProductController@index');

});
