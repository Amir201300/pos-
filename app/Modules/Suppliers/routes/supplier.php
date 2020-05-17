<?php

/*
 * Name space of suppliers to make route default
 */

Route::namespace('Suppliers\Http\Controllers\Api')->group(function(){
    Route::prefix(config('route.prefix.user'))->group(function(){
        Route::get('/get_user','UserController@index');

    });

});

