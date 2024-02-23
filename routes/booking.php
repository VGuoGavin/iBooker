<?php

Route::get('/', 'App\Http\Controllers\BookingController@index')->name('bookings.index');

Route::post('/', 'App\Http\Controllers\BookingController@store')->name('bookings.store');

Route::get('/b/{id}', 'App\Http\Controllers\BookingController@show')->name('bookings.show');

Route::get('/b/{id}/edit', function () {

});

Route::put('/b/{id}', function () {

});

Route::delete('/b/{id}', function () {

});


