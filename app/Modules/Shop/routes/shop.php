<?php

/*
 * Name space of suppliers to make route default
 */
$basname = basename(dirname(__DIR__, 1));

Route::prefix($basname)->middleware('api')->group(function () use ($basname) {

    Route::namespace(gentNameSpace($basname, 'Api'))->group(function () use ($basname) {

        Route::post('login', 'LoginController@login')->name('shop.login');

        Route::middleware('auth:api-Shop')->group(function () use ($basname) {

            Route::prefix(config($basname . '.prefix.shop'))->group(function () {

                Route::get('get_info', 'LoginController@get_info')->name('shop.get_info');
                Route::post('edit_info', 'LoginController@edit_info')->name('shop.edit_info');

            });
        });

    });
});