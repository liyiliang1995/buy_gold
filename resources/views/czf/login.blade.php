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
                        <input class="weui-input" type="number" name="code" pattern="[0-9]*" placeholder="请输入验证码">
                    </div>
                    <div class="weui-cell__ft">
                        <a href="javascript:void(0)" class="weui-vcode-btn code">获取验证码</a>
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
            @if($errors->has('code'))
                @foreach($errors->get('code') as $message)
                    $.toast("{{$message}}", 'text');
                @endforeach
            @endif

            $(".code").on('click', function () {
                if(login.flag == 1)
                    return;
                var phone = $('input[name="phone"]').val().trim();
                if (phone) {
                    if (login.isPhoneNo(phone)) {
                        ajaxGetCode(phone);
                    } else {
                        $.toast("手机号码格式不正确！", 'text');
                    }
                } else {
                    $.toast("手机号码不能为空", 'text');
                }
            })
            var countdown = 60;
            function settime(obj) {
                if (countdown == 0) {
                    obj.html("获取验证码");
                    obj.css('background','rgb(255, 255, 255)');
                    login.flag = 0;
                    countdown = 60;
                    return;
                } else {
                    obj.css('background','#fff');
                    obj.html("重新发送(" + countdown + ")");
                    login.flag = 1;
                    countdown--;
                }
                setTimeout(function(){settime(obj)},1000)
            }

            /**
             * @see 获取验证码
             * @param phone
             */
            function ajaxGetCode(phone) {
                $.ajax({
                    url: "{{route('sendMsg')}}",
                    type:'post',
                    dataType: "json",
                    data:{ phone:phone,_method:'post' ,_token:"{{csrf_token()}}"},
                    error:function(data){
                        $.toast("服务器繁忙, 请联系管理员！",'text');
                        return;
                    },
                    success:function(result){
                        if(result.code == 200){
                            settime($(".code"));
                        } else {
                            $.toast(result.message,'text')
                        }
                    },
                })
            }
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
