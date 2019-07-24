<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group([],function($router){
    $router->namespace('\\App\\Http\\Controllers\\Czf')->group(function ($router) {
        $router->get('login','MemberController@getLogin')->name('login');
        $router->get('userset','MemberController@getUserSet')->name('userset');
        $router->get('sendMsg','MemberController@sendMsg')->name('sendMsg');
    });
});
