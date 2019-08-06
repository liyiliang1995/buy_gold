<?php
/**
 * Created by PhpStorm.
 * User: youxingxiang
 * Date: 2019/7/24
 * Time: 1:40 PM
 */
return [
    // 短信配置
    'short_message' => [
        'key'    => 'facf957222f2638085762e47bd7303c1', // 短信app_key
        'tpl_id' => '164158',                           // 短信模版id
    ],

    'redis_key'  => [
        's1' => "str:send_msg:phone:",  // 电话 短信发送次数
        's2' => "str:send_msg:ip:",     // 短信发送ip记录次数
        's3' => "str:send_msg:code:",   // 短信验证码
        's4' => "str:send_msg:log",     // 短信异常日志
        's5' => 'str:gold_pool_num',    // 金币池总价格
        's6' => 'str:auto_gold_time',
        'h1' => 'hash:auto_gold_members',//自动领取金币的人数
    ],
    'stockholders_rate' => 0.5,         // 购买商品给股东分成比列

    'default_auto_gold' => 1800,          // 默认自动领取金币的时间 1800 1800+30

    'gold_show_type'  => [
        1 => "购物消费",
        2 => "金币出售",
        3 => "金币求购",
        4 => "领取金币",
        5 => "扣点返回金币池",
        6 => "代理注册扣除",
        7 => "代理注册增加",
        8 => "15天未登陆扣除",
        9 => "后台充值增加",
        10 => "后台充值减少",
        11 => "金币燃烧",
        12 => "购物金币流向金币池",
        13 => "购物消耗金币流向股东",
    ],
];