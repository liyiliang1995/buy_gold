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



Route::group([],function($router){
    $router->namespace('\\App\\Http\\Controllers\\Czf')->group(function ($router) {
        $router->get('userset','MemberController@getUserSet')->name('userset');
        $router->post('sendMsg','MemberController@sendMsg')->name('sendMsg');
        $router->get('myPartner','MemberController@myPartner')->name('myPartner');
        $router->post('agentRegister','MemberController@agentRegister')->name('agentRegister');
        $router->post('setUser','MemberController@setUser')->name('setUser');
        $router->get('goods/detail/{id}','GoodsController@goodsDetail')->name('goodsDetail');
        $router->get('confirm/order/{goodsId}','GoodsController@confirmOrder')->name('confirmOrder');
        $router->get('address/edit','GoodsController@getEditAddress')->name('getEditAddress');
        $router->post('address/edit','GoodsController@postEditAddress')->name('postEditAddress');
        $router->get('/', 'HomeController@index')->name('home');
    });
});
Auth::routes();

