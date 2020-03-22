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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/services', 'frontend\HomeController@showServices');
Route::get('/services_all', 'frontend\HomeController@allServices');
Route::get('/services/{id}', 'frontend\HomeController@showServiceSingle');
Route::post('/service_booking', 'frontend\HomeController@bookService');
Route::get('/cancel_booking/{id}', 'frontend\HomeController@cancelBooking');