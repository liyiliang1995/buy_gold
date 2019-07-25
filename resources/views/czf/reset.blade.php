@extends('czf.base',['header'=>'用户 - 完善信息',
'css' => [
        'http://at.alicdn.com/t/font_1300674_bwcd8riknaj.css',
        'https://cdn.bootcss.com/weui/2.0.1/style/weui.min.css',
        'https://cdn.bootcss.com/jquery-weui/1.2.1/css/jquery-weui.min.css',
        'css/style.css'
    ],
'js' => [
        'https://cdn.bootcss.com/jquery/1.11.0/jquery.min.js',
        'https://cdn.bootcss.com/jquery-weui/1.2.1/js/jquery-weui.min.js',
        'https://cdn.bootcss.com/jquery-weui/1.2.1/js/swiper.min.js',
        'https://cdn.bootcss.com/jquery-weui/1.2.1/js/city-picker.min.js'
    ]
])
@section('content')
    <body class="password-page">
    <div class="weui-flex" style="margin-top: 2rem;">
        <div><div class="placeholder">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></div>
        <div class="weui-flex__item">
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><i class="iconfont icon-mobile" style="font-size: 1rem"></i></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="number" pattern="[0-9]*" placeholder="请输入您的手机号">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><i class="iconfont icon-lock" style="font-size: 1rem"></i></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="number" pattern="[0-9]*" placeholder="请输入新密码">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><i class="iconfont icon-lock" style="font-size: 1rem"></i></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="number" pattern="[0-9]*" placeholder="请再次输入新密码">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><i class="iconfont icon-safetycertificate" style="font-size: 1rem"></i></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="number" pattern="[0-9]*" placeholder="请输入验证码">
                    </div>
                    <div class="weui-cell__ft">
                        <button class="weui-vcode-btn">获取验证码</button>
                    </div>
                </div>
            </div>
            <div class="weui-btn-area">
                <a class="weui-btn weui-btn_primary" href="javascript:" id="showTooltips" style="width: 100%;background-color: #176d43;">提交</a>
            </div>
        </div>
        <div><div class="placeholder">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></div>
    </div>
    </body>
@endsection
