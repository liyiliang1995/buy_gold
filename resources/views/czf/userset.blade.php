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
                <input class="weui-input" type="text" name="name" id="name" placeholder="请输入真实姓名">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">我的手机号</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" name="phone" id="phone" type="tel" placeholder="请输入手机号">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">联系微信</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" name="wechat" id="wechat" type="text" placeholder="请输入微信号">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">联系手机</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" name="phone2" id="phone2" type="tel" placeholder="请输入手机号">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">修改密码</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" name="password1" id="password1" type="password" placeholder="请输入密码">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">确认密码</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" name="password2" id="password2" type="password" placeholder="请输入密码">
            </div>
        </div>

        <div class="weui-cells">
            <a class="weui-cell weui-cell_access" href="add_edit.html">
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
        $('#sub_but').click(function () {
// 验证姓名
//             var name = $('#name').val();
//             if (name == '') {
//                 $.toast("真实姓名不能为空", "text");
//                 return false;
//             }
//
//             // 验证手机号
//             var phone = $('#phone').val();
//             if (!(/^1[3456789]\d{9}$/.test(phone))) {
//                 $.toast("我的手机号有误", "text");
//                 return false;
//             }
//
//             var phone2 = $('#phone2').val();
//             if (!(/^1[3456789]\d{9}$/.test(phone2))) {
//                 $.toast("联系手机有误", "text");
//                 return false;
//             }
//
//             // 验证联系微信
//             var wechat = $('#wechat').val();
//             if (wechat == '') {
//                 $.toast("联系微信不能为空", "text");
//                 return false;
//             }
            // 验证码密码
            var pw1 = $('#password1').val();
            var pw2 = $('#password2').val();
            var reg = new RegExp(/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/);

            if (pw1 == '' && pw2 == '') {
                return true;
            }
            if (pw1.length < 6) {
                $.toast("密码必须6位以上", "text");
                return false;
            }
            if (!reg.test(pw1)) {
                $.toast("密码必须包含数字和字母", "text");
                return false;
            }

            if (pw1 != pw2) {
                $.toast("输入密码不一致", "text");
                return false;
            }

        });
    </script>

    </body>
@endsection
