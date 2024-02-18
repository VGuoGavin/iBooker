

<?php

use App\Http\Controllers\DashboardController;


Route::get('/test', 'App\Http\Controllers\DashboardController@index')->name('dashboard.index');

//Route::get('/account', 'App\Http\Controllers\ProfileController')->name('pprofile');

Route::get('/sign', 'App\Http\Controllers\MakeSignature')->name('make.signature');
