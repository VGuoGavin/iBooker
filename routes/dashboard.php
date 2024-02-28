

<?php

use App\Http\Controllers\DashboardController;


Route::get('/', 'App\Http\Controllers\DashboardController@index')->middleware(['auth', 'verified'])->name('dashboard.index');
//Route::get('/sign', 'App\Http\Controllers\MakeSignature')->name('make.signature');
Route::get('/sign', 'App\Http\Controllers\MakeSignature')->name('make.signature');