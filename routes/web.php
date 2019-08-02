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
        $router->get('order/list','GoodsController@orderList')->name('order_list');
        $router->get('ajax/getorderlist/{is_send}','GoodsController@ajaxGetOrderList')->name('ajaxGetOrderList');
        $router->get('trade_center', 'TradeController@index')->name('trade_center');
        $router->post('buy_gold', 'TradeController@buyGold')->name('buy_gold');
        $router->get('sell_gold/{id}', 'TradeController@sellGold')->name('sell_gold');
        $router->get('sell_gold_order/{id}', 'TradeController@sellGoldOrder')->name('sell_gold_order');
        $router->get('trade/record','TradeController@tradeRecord')->name('trade_record');
        $router->get('apply/cancel_order/{id}','TradeController@applyCancelOrder')->name('apply_cancel_order');
        $router->get('confirm_order/{id}','TradeController@confirmOrder')->name('confirm_order');
        $router->get('trade/record','TradeController@tradeRecord')->name('trade_record');
        $router->get('ajax/getgoldflow/{type}','TradeController@ajaxGetGoldFlow')->name('ajaxGetGoldFlow');
        $router->get('ajax/getintegralflow/{type}','TradeController@ajaxGetIntegralFlow')->name('ajaxGetIntegralFlow');
        $router->get('ajax/getenergyflow/{type}','TradeController@ajaxGetEnergyFlow')->name('ajaxGetEnergyFlow');
        $router->get('ajax/getbuygoldType/{type}','TradeController@ajaxGetBuyGoldType')->name('ajaxGetBuyGoldType');
        $router->get('ajax/get_gold_pool','TradeController@ajaxGetGoldPool')->name('ajaxGetGoldPool');
        $router->get('/', 'HomeController@index')->name('home');
        $router->get('energy/record','TradeController@energyRecord')->name('energy_record');
        $router->get('integral/record','TradeController@integralRecord')->name('integral_record');
        $router->get('order_gold_detail/{id}','TradeController@orderGoldDetail')->name('order_gold_detail');
        $router->get('member_index','MemberController@memberIndex')->name('member_index');
        $router->get('help_center','MemberController@helpCenter')->name('help_center');
        $router->get('notification_list','MemberController@notificationList')->name('notification_list');
    });
});
Auth::routes();

