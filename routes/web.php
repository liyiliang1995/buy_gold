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
        $router->post('send_msg','MemberController@sendMsg')->name('sendMsg');
        $router->get('my_partner','MemberController@myPartner')->name('myPartner');
        $router->post('agent_register','MemberController@agentRegister')->name('agentRegister');
        $router->post('set_user','MemberController@setUser')->name('setUser');
        $router->get('goods/detail/{id}','GoodsController@goodsDetail')->name('goodsDetail');
        $router->get('confirm/order/{goodsId}','GoodsController@confirmOrder')->name('confirmOrder');
        $router->post('order/save/{goodsId}', 'GoodsController@postOrderSave')->name('order_save');
        $router->get('address/edit','GoodsController@getEditAddress')->name('getEditAddress');
        $router->post('address/edit','GoodsController@postEditAddress')->name('postEditAddress');
        $router->get('trade_center', 'TradeController@index')->name('trade_center');
        $router->post('buy_gold', 'TradeController@buyGold')->name('buy_gold');
        $router->get('sell_gold/{id}', 'TradeController@sellGold')->name('sell_gold');
        $router->get('sell_gold_order/{id}', 'TradeController@sellGoldOrder')->name('sell_gold_order');
        $router->get('trade/record','TradeController@tradeRecord')->name('trade_record');
        $router->get('/', 'HomeController@index')->name('home');
    });
});
Auth::routes();

