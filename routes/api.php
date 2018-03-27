<?php

use Illuminate\Http\Request;

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

Route::get('/', function(){
    return response()->json(['message'=> "On air."], 200);
});
Route::post('login', 'ApiLoginController@login');
Route::resource('profile', 'Api\ProfileController');

Route::get('book', 'Api\BookController@index');
Route::get('book/isbn/{isbn}', 'Api\BookController@isbn');
