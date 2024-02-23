<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\APIController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/roomsInBuilding', 'App\Http\Controllers\API\APIController@roomsInBuilding');

Route::post('/roomDetail', 'App\Http\Controllers\API\APIController@roomDetail');

Route::post('/roomBookings', 'App\Http\Controllers\API\APIController@roomBookings');

//Route::post('/accessCode', 'App\Http\Controllers\API\APIController@generateAccessCode');
Route::post('/accessCode', [APIController::class, 'generateAccessCode']);

Route::post('/accessBooking', 'App\Http\Controllers\API\APIController@accessBooking');

Route::post('/sign', 'App\Http\Controllers\API\APIController@sign');

Route::post('/checkSignature', 'App\Http\Controllers\API\APIController@checkSignature');

Route::post('/accept', 'App\Http\Controllers\API\APIController@accept');

Route::post('/reject', 'App\Http\Controllers\API\APIController@reject');

Route::post('/contact', 'App\Http\Controllers\API\MailController');