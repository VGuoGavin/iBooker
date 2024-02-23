<?php

Route::get('/', function () {
    return response('User route', 200);
});

Route::get('/u/{id}', function () {

});

Route::post('/u/{id}/verify', function () {

});

Route::delete('/u/{id}', function () {

});
