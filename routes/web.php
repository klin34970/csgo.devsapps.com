<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('setlocale/{locale}', function ($locale) {
        if (in_array($locale, \Config::get('app.locales'))) {
            Session::put('locale', $locale);
        }
        return redirect()->back();
    });

    Route::get('/', function () {
        Redis::connection()->del('queues:log');
        //event(new App\Events\UserLogin(auth()->user(), '111', 'web'));
        return view('frontend.index');
    });
    Route::get('login', 'SteamController@login')->name('login');

    //BACKEND
    Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {

        //USERS
        Route::get('/users',                             'API\SteamController@index'    )->name('user.index'    )->middleware('permission:user_index');
        Route::get('/users/create',                      'API\SteamController@create'   )->name('user.create'   )->middleware('permission:user_create');
        Route::post('/users',                            'API\SteamController@store'    )->name('user.store'    )->middleware('permission:user_create');
        Route::get('/users/{user}',                      'API\SteamController@show'     )->name('user.show'     )->middleware('permission:user_show');
        Route::get('/users/{user}/edit',                 'API\SteamController@edit'     )->name('user.edit'     )->middleware('permission:user_edit');
        Route::match(['put', 'patch'], '/users/{user}',  'API\SteamController@update'   )->name('user.update'   )->middleware('permission:user_edit');
        Route::delete('/users/{user}',                   'API\SteamController@destroy'  )->name('user.destroy'  )->middleware('permission:user_destroy');

    });


});












Route::get('/callback', function (Illuminate\Http\Request $request) {
    $response = (new GuzzleHttp\Client)->post('http://csgo-preprod.devsapps.com//oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => 1,
            'client_secret' => 'cOEWl6vIsTSk8QchK4htDTh6VKOrn1pHb5n7rsvQ',
            'redirect_uri' => 'http://csgo-preprod.devsapps.com/callback',
            'code' => $request->code,
        ]
    ]);

    return json_decode((string) $response->getBody(), true);
});
