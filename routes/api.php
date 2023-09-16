<?php

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/users/register', 'API\UserController@register')->name('user.register')->middleware('permission:user_edit');
    Route::get('/users/me', 'API\UserController@me')->name('user.me')->middleware('permission:user_show');
    Route::get('/connections/me', 'API\ConnectionController@me')->name('connections.me')->middleware('permission:user_show');
});