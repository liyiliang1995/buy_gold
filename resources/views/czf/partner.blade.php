@extends('czf.base',['header'=>'我的伙伴',
'css' => [
         'css/weui.min.css',
        '/css/weui.css',
        'css/demos.css',
    ],
'js' => [
        'js/fastclick.js',
        'js/jquery-weui.js'
    ],
'script' => [
        "FastClick.attach(document.body);"
    ]
])

@section('content')
    <style>
        #partner_list {
            font-size: 12px;
        }

        .weui-cells {
            margin-top: 0 !important;
        }

        .weui-input {
            font-size: 14px;
        }
    </style>
    <body>
    <!--头部-->
    <div class="weui-flex" id="header_top">
        <a href="{{route('member_index')}}"><img src="{{route('home')}}/img/fh.png" alt=""></a>
        <div class="weui-flex__item" style="font-size: 16px;">我的伙伴</div>
    </div>

    <div class="weui-flex">
        <div class="weui-flex__item" style="line-height: 35px;padding: 10px;font-weight: bold;font-size: 16px;">注册新用户
        </div>
    </div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <input class="weui-input" type="number" name="phone" value="{{ old('phone') }}" placeholder="请输入手机号">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" name="name" placeholder="真实姓名">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <input class="weui-input" type="password" name="password" placeholder="请输入密码(字母+数字  9-18位)">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><i class="iconfont icon-safetycertificate" style="font-size: 1rem"></i></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="number" name="code" pattern="[0-9]*" placeholder="请输入验证码">
            </div>
            <div class="weui-cell__ft">
                <a href="javascript:void(0)" class="weui-vcode-btn code">获取验证码</a>
            </div>
        </div>
    </div>
    <div class="weui-flex">
        <div class="weui-flex__item"><a id="rg" href="javascript:;" class="weui-btn weui-btn_primary"
                                        style="width: 90%;margin-top: 30px;">注册</a></div>
    </div>
    <div class="weui-flex">
        <div class="weui-flex__item" style="line-height: 35px;padding: 10px;font-weight: bold;font-size: 16px;">我的伙伴
        </div>
    </div>

    <div class="weui-flex" id="partner_list">
        <div class="weui-flex__item">持有金币总数：<span style="color: #F44336">{{$dSum}}</span></div>
    </div>


    @foreach($oPartner as $key=>$value)
        <div class="weui-flex" id="partner_list">
            <div class="weui-flex__item" id="partner_user">
                <p style="font-weight: bold">{{$value['name'] ?: $value['phone']}}</p>
                <p style="width:140px;">{{$value['created_at']}}</p>
            </div>
            <div class="weui-flex__item" style="text-align: center;">
                <p>金币数</p>
                <p>{{$value['gold']}}</p>
            </div>
            <div class="weui-flex__item" style="">
                {!! $value['stat'] !!}
            </div>
        </div>
    @endforeach

    </body>
    <script>
        var rg = {
            isPhoneNo: function (phone) {
                var pattern = /^1[349578]\d{9}$/;
                return pattern.test(phone);
            },
            isPassword: function (pwd) {
                var reg = new RegExp(/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/);
                return reg.test(pwd);
            },
            phone: function () {
                var phone = $('input[name="phone"]').val().trim();
                if (this.isPhoneNo(phone))
                    return phone;
                else {
                    $.toast("手机号码格式不正确！", 'text');
                    return;
                }

            },
            pwd: function () {
                var pwd = $('input[name="password"]').val().trim();
                if (pwd.length > 8) {
                    if (this.isPassword(pwd)) {
                        return pwd;
                    } else {
                        $.toast("密码必须包含数字和字母！", 'text');
                        return;
                    }
                } else {
                    $.toast("密码必须8位以上！", 'text');
                    return;
                }

            },
            name: function () {
                var name = $('input[name="name"]').val().trim();
                if (name)
                    return name;
                else {
                    $.toast("真实姓名不能为空！", 'text');
                    return;
                }
            },
            code: function () {
                var code = $('input[name="code"]').val().trim();
                if (code)
                    return code;
                else {
                    $.toast("验证码不能为空！", 'text');
                    return;
                }
            },
            register: function () {
                if (this.pwd() && this.phone() && this.name() && this.code()) {
                    $.ajax({
                        url: "{{route('agentRegister')}}",
                        type: 'post',
                        dataType: "json",
                        data: {
                            name: this.name(),
                            phone: this.phone(),
                            password: this.pwd(),
                            code: this.code(),
                            _method: 'post',
                            _token: "{{csrf_token()}}"
                        },
                        error: function (data) {
                            $.toast("服务器繁忙, 请联系管理员！", 'text');
                            return;
                        },
                        success: function (result) {
                            if (result.code == 200) {
                                $.toast('注册成功!', 'text');
                                window.location.reload();
                            } else {
                                $.toast(result.message, 'text')
                            }
                        },
                    })
                }
            },
            isPhoneNo: function (phone) {
                var pattern = /^1[349578]\d{9}$/;
                return pattern.test(phone);
            },
            flag: 0,
        }
        $(function () {
            @php
                $errors = session()->get('lock');
            @endphp
            @if($errors)
            $.toast("{{$errors}}", 'text');
            @endif

            $("#rg").on('click', function () {
                rg.register();
            })
            $(".code").on('click', function () {
                if (rg.flag == 1)
                    return;
                var phone = $('input[name="phone"]').val().trim();
                if (phone) {
                    if (rg.isPhoneNo(phone)) {
                        ajaxGetCode(phone);
                    } else {
                        $.toast("手机号码格式不正确！", 'text');
                    }
                } else {
                    $.toast("手机号码不能为空", 'text');
                }
            })
            /**
             * @see 计数器
             */
            var countdown = 60;

            function settime(obj) {
                if (countdown == 0) {
                    obj.html("获取验证码");
                    obj.css('background', 'rgb(255, 255, 255)');
                    rg.flag = 0;
                    countdown = 60;
                    return;
                } else {
                    obj.css('background', '#fff');
                    obj.html("重新发送(" + countdown + ")");
                    rg.flag = 1;
                    countdown--;
                }
                setTimeout(function () {
                    settime(obj)
                }, 1000)
            }

            /**
             * @param phone
             */
            function ajaxGetCode(phone) {
                $.ajax({
                    url: "{{route('sendMsg')}}",
                    type: 'post',
                    dataType: "json",
                    data: {phone: phone, _method: 'post', _token: "{{csrf_token()}}"},
                    error: function (data) {
                        $.toast("服务器繁忙, 请联系管理员！", 'text');
                        return;
                    },
                    success: function (result) {
                        if (result.code == 200) {
                            settime($(".code"));
                        } else {
                            $.toast(result.message, 'text')
                        }
                    },
                })
            }
        })
    </script>
@endsection
