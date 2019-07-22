<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resource('members', MemberController::class);
    $router->resource('configs', ConfigController::class);
    $router->resource('goods', GoodController::class);
    $router->get('getKvbyTypeId/{id}',"ConfigController@getKvbyTypeId")->name('admin.getKvbyTypeId');
    $router->post('postKvbyTypeId/{id}',"ConfigController@postKvbyTypeId")->name("admin.postKvbyTypeId");

});
