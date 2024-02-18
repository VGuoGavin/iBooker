<?php

Route::get('/', 'AnnouncementController@index')->name('announcements.index');

Route::post('/', 'AnnouncementController@store')->name('announcements.store');

Route::get('/new', 'AnnouncementController@create')->name('announcements.create');

Route::get('/a/{id}', 'AnnouncementController@show')->name('announcements.show');

Route::get('/a/{id}/edit', 'AnnouncementController@edit')->name('announcements.edit');

Route::put('/a/{id}', 'AnnouncementController@update')->name('announcements.update');

Route::delete('/a/{id}', 'AnnouncementController@destroy')->name('announcements.delete');

Route::put('/a/{id}/post', 'AnnouncementController@post')->name('announcements.post');
