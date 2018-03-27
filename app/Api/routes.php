<?php

Route::group(['prefix' => 'api/v1', 'namespace' => 'App\Api\Controllers'], function () {
    //
    Route::resource('orders', 'OrderController');
    Route::resource('books', 'BookController');
    Route::resource('books_cover', 'BookController@saveImage');
//    Route::resource('book_copies', 'BookCopyController');
    Route::resource('pricings', 'PricingController');
    Route::resource('address', 'AddressController');
    Route::resource('transaction', 'TransactionController');
    Route::resource('login', 'UserController');
    Route::resource('transaction', 'TransactionController');
    Route::resource('message', 'MessageController');
    Route::resource('change_password', 'UserController');
    Route::resource('chat', 'ChatController');

});
