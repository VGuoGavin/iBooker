<?php

Route::get('/', function () {
    return response('Booking route', 200);
})->name('drafts.index');

Route::post('/', 'App\Http\Controllers\BookingDraftController@store')->name('drafts.store');

Route::get('/new', 'App\Http\Controllers\BookingDraftController@create')->name('drafts.create.empty');

Route::post('/new', 'App\Http\Controllers\BookingDraftController@create')->name('drafts.create.filled');

Route::get('/d/{id}', 'App\Http\Controllers\BookingDraftController@show')->name('drafts.show');

Route::put('/d/{id}/commit', 'App\Http\Controllers\BookingDraftController@commit')->name('drafts.commit');

Route::get('/d/{id}/edit', 'App\Http\Controllers\BookingDraftController@edit')->name('drafts.edit');

Route::put('/d/{id}', 'App\Http\Controllers\BookingDraftController@update')->name('drafts.update');

Route::delete('/d/{id}', 'App\Http\Controllers\BookingDraftController@destroy')->name('drafts.delete');


