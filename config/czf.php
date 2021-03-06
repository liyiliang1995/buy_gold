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
        'tpl_id' => '179073',                           // 短信模版id
    ],

    'redis_key'  => [
        's1' => "str:send_msg:phone:",  // 电话 短信发送次数
        's2' => "str:send_msg:ip:",     // 短信发送ip记录次数
        's3' => "str:send_msg:code:",   // 短信验证码
        's4' => "str:send_msg:log",     // 短信异常日志
        's5' => 'str:gold_pool_num',    // 金币池总价格
        's6' => 'str:auto_gold_time',
        's7' => "str:unified_time",     // 领取统一时间
        'h1' => 'hash:auto_gold_members',//自动领取金币的人数
        'set1' => "set:freeze_members",  // 用户冻结人数
    ],
    'stockholders_rate' => 0.4,         // 购买商品给股东分成比列

    'stockholders_rate2' => 0.2,         // 积分兑换商品给股东分成比列

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
        13 => "购物分红",
        14 => '15天未登录流向金币池',
        15 => '积分兑换扣除金币',
        16 => '积分兑换金币流向金币池',
        17 => '兑换分红',
        18 => '挂单扣除金币',
        19 => '抢单获取金币',
        20 => '撤销订单返回金币',
        21 => '推荐奖',
    ],
    'integral_show_type' => [
        1 => "消费获取积分",
        2 => "出售金币消耗积分",
        3 => "积分兑换获得积分"
    ],

    "energy_show_type" => [
        1 => '自动领取金币消耗能量值',
        2 => '求购金币获得能量值'
    ],
    "flow_show_type" => [
        1 => '金币流水',
        2 => '积分流水',
        3 => '能量值流水'
    ]

];
