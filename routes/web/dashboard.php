

<?php

use App\Http\Controllers\DashboardController;


Route::get('/', 'DashboardController@index')->name('dashboard.index');

Route::get('/account', 'ProfileController')->name('profile');

Route::get('/sign', 'MakeSignature')->name('make.signature');
