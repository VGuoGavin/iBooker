<?php

Route::get('/', 'App\Http\Controllers\AnnouncementController@index')->name('announcements.index');

Route::post('/', 'App\Http\Controllers\AnnouncementController@store')->name('announcements.store');

Route::get('/new', 'App\Http\Controllers\AnnouncementController@create')->name('announcements.create');

Route::get('/a/{id}', 'App\Http\Controllers\AnnouncementController@show')->name('announcements.show');

Route::get('/a/{id}/edit', 'App\Http\Controllers\AnnouncementController@edit')->name('announcements.edit');

Route::put('/a/{id}', 'App\Http\Controllers\AnnouncementController@update')->name('announcements.update');

Route::delete('/a/{id}', 'App\Http\Controllers\AnnouncementController@destroy')->name('announcements.delete');

Route::put('/a/{id}/post', 'App\Http\Controllers\AnnouncementController@post')->name('announcements.post');
