@extends('czf.base',['header'=>'登录 - 翠竹坊',
'css' => [
        'http://at.alicdn.com/t/font_1300674_bwcd8riknaj.css',
        'css/weui.min.css',
        'css/jquery-weui.min.css',
        'css/swiper.min.css',
        'css/style.css'
    ],
'js'=>[
        'js/jquery-weui.min.js',
        'js/swiper.min.js',
        'js/city-picker.min.js'
    ]
])
@section('content')
    <style>
        .weui-cell__bd{
            padding-left: 10px;
            font-size: 14px;
        }
        #login{
            display: block;
            margin: 0 auto;
        }
        html{
            height: 100%;
        }
        body{
            height: 100%;
        }
    </style>
    <body class="login-page" style="background: none">
    <img src="/images/bolang.png" style="position: fixed; width: 100%; bottom: 0;height: auto;">
    <div class="weui-flex">
        <div class="weui-flex__item">
            <div class="weui-login">
                <div class="weui-logo-area"><img src="images/logo-.png" width="150" /></div>
            </div>
        </div>
    </div>
    <div class="weui-flex">
        <div><div class="placeholder">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></div>
        <form action="{{ route("login")}}" method="post" id="login" >
        <div class="weui-flex__item">
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><i class="iconfont icon-mobile"></i></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="number" name="phone" value="{{ old('phone') }}" pattern="[0-9]*" placeholder="请输入您的手机号">
                    </div>
                </div>


                <div class="weui-cell">
                    <div class="weui-cell__hd"><i class="iconfont icon-lock"></i></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="password" name="password" pattern="[0-9]*" placeholder="请输入您的登录密码">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><i class="iconfont icon-safetycertificate"></i></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="number" name="captcha" pattern="[0-9]*" placeholder="请输入验证码">
                    </div>
                    <div class="weui-cell__ft">
                        <img src="{{captcha_src()}}" style="cursor: pointer" onclick="this.src='{{captcha_src()}}'+Math.random()">
                    </div>
                </div>
                @csrf
            </div>
            <div class="weui-btn-area">
                <a class="weui-btn weui-btn_primary" href="javascript:" id="showTooltips">登录</a>
            </div>
            <a href="{{ route('password.request') }}" class="weui-agree weui-agree__text">忘记密码？  </a>
        </div>
        </form>
        <div><div class="placeholder">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></div>
    </div>

    <script>
        var login = {
            isPhoneNo:function (phone) {
                var pattern = /^1[349578]\d{9}$/;
                return pattern.test(phone);
            },
            flag:0
        }
        $(function () {
            @if($errors->has('phone'))
                @foreach($errors->get('phone') as $message)
                    $.toast("{{$message}}", 'text');
                @endforeach
            @endif

            @if($errors->has('password'))
                @foreach($errors->get('password') as $message)
                    $.toast("{{$message}}", 'text');
                @endforeach
            @endif
            @if($errors->has('captcha'))
                @foreach($errors->get('captcha') as $message)
                    $.toast("{{$message}}", 'text');
                @endforeach
            @endif


            $("#showTooltips").on('click',function () {
                var phone = $('input[name="phone"]').val().trim();
                if (login.isPhoneNo(phone)) {
                    $("#login").submit();
                } else {
                    $.toast("手机号码格式不正确！", 'text');
                }
            })
        })
    </script>
    </body>
@endsection
