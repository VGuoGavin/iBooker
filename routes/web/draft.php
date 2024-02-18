<?php

Route::get('/', function () {
    return response('Booking route', 200);
})->name('drafts.index');

Route::post('/', 'BookingDraftController@store')->name('drafts.store');

Route::get('/new', 'BookingDraftController@create')->name('drafts.create.empty');

Route::post('/new', 'BookingDraftController@create')->name('drafts.create.filled');

Route::get('/d/{id}', 'BookingDraftController@show')->name('drafts.show');

Route::put('/d/{id}/commit', 'BookingDraftController@commit')->name('drafts.commit');

Route::get('/d/{id}/edit', 'BookingDraftController@edit')->name('drafts.edit');

Route::put('/d/{id}', 'BookingDraftController@update')->name('drafts.update');

Route::delete('/d/{id}', 'BookingDraftController@destroy')->name('drafts.delete');


