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
        's4' => "str:send_msg:log",      // 短信异常日志
    ],
];