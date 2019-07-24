@extends('czf.base',['header'=>'登录 - 翠竹坊',
'css' => [
        'http://at.alicdn.com/t/font_1300674_bwcd8riknaj.css',
        'https://cdn.bootcss.com/weui/2.0.1/style/weui.min.css',
        'https://cdn.bootcss.com/jquery-weui/1.2.1/css/jquery-weui.min.css',
        'css/swiper.min.css',
        'css/style.css'
    ],
'js'=>[
        'https://cdn.bootcss.com/jquery-weui/1.2.1/js/jquery-weui.min.js',
        'https://cdn.bootcss.com/jquery-weui/1.2.1/js/swiper.min.js',
        'https://cdn.bootcss.com/jquery-weui/1.2.1/js/city-picker.min.js'
    ]
])
<style>
    .weui-cell__bd{
        padding-left: 10px;
        font-size: 14px;
    }
</style>
@section('content')
    <body class="login-page">

    <div class="weui-flex">
        <div class="weui-flex__item">
            <div class="weui-login">
                <div class="weui-logo-area"><img src="images/logo-.png" width="150" /></div>
            </div>
        </div>
    </div>
    <div class="weui-flex">
        <div><div class="placeholder">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></div>
        <div class="weui-flex__item">
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><i class="iconfont icon-mobile"></i></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="number" pattern="[0-9]*" placeholder="请输入您的手机号">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><i class="iconfont icon-lock"></i></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="number" pattern="[0-9]*" placeholder="请输入您的登录密码">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><i class="iconfont icon-safetycertificate"></i></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="number" pattern="[0-9]*" placeholder="请输入验证码">
                    </div>
                    <div class="weui-cell__ft">
                        <button class="weui-vcode-btn">获取验证码</button>
                    </div>
                </div>
            </div>
            <div class="weui-btn-area">
                <a class="weui-btn weui-btn_primary" href="javascript:" id="showTooltips">登录</a>
            </div>
            <label for="weuiAgree" class="weui-agree">
            <span class="weui-agree__text"><a href="javascript:void(0);">忘记密码？</a>
      </span>
            </label>
        </div>
        <div><div class="placeholder">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></div>
    </div>
@endsection
