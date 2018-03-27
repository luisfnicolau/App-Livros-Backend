<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();

Route::get('/home', 'HomeController@index');


Route::group(['prefix' => 'admin'], function () {
  Route::get('', 'Admin\HomeController@showHome')->name('admin');

  // Authentication
  Route::get('/login', 'AdminAuth\LoginController@showLoginForm');
  Route::post('/login', 'AdminAuth\LoginController@login');
  Route::post('/logout', 'AdminAuth\LoginController@logout');

  // Recover Password
  Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset');
  Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm');
  Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');

  // Change Password
  Route::get('/change-password', 'Admin\HomeController@getChangePassword')
        ->name('admin.change-password');
  Route::post('/change-password', 'Admin\HomeController@postChangePassword');

  // Admin Resources
  Route::get('users', 'Admin\UsersController@index')
        ->name('admin.users');
  Route::get('user/edit/{id}', 'Admin\UsersController@edit')
        ->name('admin.user.edit');
  Route::put('user/edit/{id}', 'Admin\UsersController@update')
        ->name('admin.user.update');
  Route::delete('user/{id}', 'Admin\UsersController@destroy')
        ->name('admin.user.destroy');
  
  Route::resource('pricing', 'Admin\PricingController');
  Route::resource('book', 'Admin\BookController');

  Route::post('/book/crawl-api', 'Admin\BookController@crawlApi')
        ->name('book.crawl-api');

  Route::get('/orders', 'Admin\OrderController@index')
        ->name('order.index');
  Route::get('/orders/canceled', 'Admin\OrderController@canceled')
        ->name('order.canceled');
  Route::get('/reversal_requests', 'Admin\PaymentReversalRequestController@index')
        ->name('reversal.index');

  Route::get('/orders/times', 'Admin\OrderController@times')
        ->name('order.times');

  Route::get('/orders/places', 'Admin\OrderController@places')
    ->name('order.places');
});

Route::get('/login', 'Auth\LoginController@showLoginForm');
// Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');

Route::group(['prefix' => 'social'], function () {
  Route::get('/login/{driver?}', 'Auth\AuthController@redirectToProvider')
        ->name('social.login');
  Route::get(
             '/callback/{driver?}',
             'Auth\AuthController@handleProviderCallback'
             );
});

Route::get('/home', 'HomeController@index')->name('user.home');
