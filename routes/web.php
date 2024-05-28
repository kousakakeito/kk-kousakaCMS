<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ログイン、登録関連
|--------------------------------------------------------------------------
*/

// ログイン画面
Route::group([
    'namespace' => 'App\Http\Controllers\Auth',
    'middleware' => ['App\Http\Middleware\RedirectIfAuthenticated:web','Illuminate\Routing\Middleware\ThrottleRequests:login','throttle.login'],
], function () {
    Route::post('/login', 'AuthenticatedSessionController@store');
});

// ログアウト
Route::group([
    'namespace' => 'App\Http\Controllers\Auth',
], function () {
    Route::post('/logout', 'AuthenticatedSessionController@destroy')->name('logout');
});

// ユーザー登録画面
Route::group([
    'namespace' => 'App\Http\Controllers\Auth',
], function () {
    Route::get('/register', 'RegisteredUserController@create')->name('register');
    Route::post('/register', 'RegisteredUserController@store');
});

/*
|--------------------------------------------------------------------------
| 管理画面
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| フロントページ
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});
