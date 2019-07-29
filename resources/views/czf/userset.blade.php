@extends('czf.base',['header'=>'用户 - 完善信息',
'css' => [
       'http://at.alicdn.com/t/font_1300674_bwcd8riknaj.css',
        'https://cdn.bootcss.com/weui/2.0.1/style/weui.min.css',
        'https://cdn.bootcss.com/jquery-weui/1.2.1/css/jquery-weui.min.css',
        'css/demos.css',
    ],
'js' => [
        'js/fastclick.js',
         'https://cdn.bootcss.com/jquery-weui/1.2.1/js/jquery-weui.min.js',
        'https://cdn.bootcss.com/jquery-weui/1.2.1/js/swiper.min.js',
        'https://cdn.bootcss.com/jquery-weui/1.2.1/js/city-picker.min.js'
    ]
])
@section('content')
    <style>
        .weui-btn_primary {
            background: #07C160;
        }

        #set_value input {
            color: #666;
        }

        .weui-btn {
            width: 90% !important;
        }
    </style>
    <body>
    <!--头部-->
    <div class="weui-flex" id="header_top">
        <a href="javascript:history.back(-1)"><img src="./img/fh.png" alt=""></a>
        <div class="weui-flex__item">设置</div>
    </div>

    <!--设置-->
    <div class="weui-cells weui-cells_form" id="set_value">
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">头像</label></div>
            <div class="weui-cell__bd">
                <img style="width: 40px;float: right;border-radius: 50%" src="./img/logo.png" alt="">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">真实姓名</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" name="name" id="name" @if(!empty($member['name'])) value="{{$member['name']}}" @endif placeholder="请输入真实姓名">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">我的手机号</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" name="phone" id="phone" @if(!empty($member['phone'])) value="{{$member['phone']}}" @endif type="tel" placeholder="请输入手机号">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">联系微信</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" name="wechat" id="wechat" @if(!empty($member['wechat'])) value="{{$member['wechat']}}" @endif type="text" placeholder="请输入微信号">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">联系手机</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" name="phone2" id="phone2" @if(!empty($member['phone2'])) value="{{$member['phone2']}}" @endif type="tel" placeholder="请输入手机号">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">修改密码</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" name="password1" id="password1"  type="password" placeholder="不填默认原始密码">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">确认密码</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" name="password2" id="password2" type="password" placeholder="请输入密码">
            </div>
        </div>
        <div class="weui-cells">
            <a class="weui-cell weui-cell_access" href="{{route('getEditAddress',['url'=>url()->full()])}}">
                <div class="weui-cell__bd">
                    <p>收货地址</p>
                </div>
                <div class="weui-cell__ft">
                </div>
            </a>
        </div>

    </div>
    <div class="weui-flex" style="margin-top: 20px;">
        <div class="weui-flex__item"><a href="javascript:;" id="sub_but" class="weui-btn weui-btn_primary">修改</a></div>
    </div>

    <script>
        $(function () {

            $('#sub_but').click(function () {
                // 验证姓名
                var name = $('#name').val();
                var phone = $('#phone').val();
                var phone2 = $('#phone2').val();
                var wechat = $('#wechat').val();
                var pw1 = $('#password1').val();
                var pw2 = $('#password2').val();
                var reg = new RegExp(/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/);
                if (name == '') {
                    $.toast("真实姓名不能为空", "text");
                    return false;
                } else if (!(/^1[3456789]\d{9}$/.test(phone))) {// 验证手机号
                    $.toast("我的手机号有误", "text");
                    return false;
                } else if (!(/^1[3456789]\d{9}$/.test(phone2))) {
                    $.toast("联系手机有误", "text");
                    return false;
                } else if (wechat == '') { // 验证联系微信
                    $.toast("联系微信不能为空", "text");
                    return false;
                } else if (pw1.length < 6 && pw1 != '') {
                    $.toast("密码必须6位以上", "text");
                    return false;
                } else if (!reg.test(pw1) && pw1 != '') {
                    $.toast("密码必须包含数字和字母", "text");
                    return false;
                } else if (pw1 != pw2) {
                    $.toast("输入密码不一致", "text");
                    return false;
                }
                // 递交数据
                $.ajax({
                    url: "{{route('setUser')}}",
                    type: 'post',
                    dataType: "json",
                    data: {
                        name: name,
                        phone: phone,
                        phone2: phone2,
                        wechat: wechat,
                        pw1: pw1,
                        _method: 'post',
                        _token: "{{csrf_token()}}"
                    },
                    error: function (data) {
                        $.toast("服务器繁忙, 请联系管理员！", 'text');
                        return;
                    },
                    success: function (result) {

                        if (result == 1) {
                            $.toast("操作成功！", 'text');
                            return;
                        } else {
                            $.toast("操作失败！", 'text');
                            return;
                        }

                    },
                })


            });
        })
    </script>

    </body>
@endsection
