@extends('czf.base',['header'=>'用户 - 完善信息',
'css' => [
        'https://cdn.bootcss.com/weui/2.0.1/style/weui.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/weui/2.0.1/style/weui.css',
        'css/demos.css',
    ],
'js' => [
        'js/fastclick.js',
        'https://cdnjs.cloudflare.com/ajax/libs/jquery-weui/1.2.1/js/jquery-weui.js'
    ]
])
@section('content')
    <style>
        .weui-btn_primary{
            background:#07C160 ;
        }
        #set_value input{
            color: #666;
        }
        .weui-btn{
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
                <img  style="width: 40px;float: right;border-radius: 50%" src="./img/logo.png" alt="">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">昵称</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text"  placeholder="请输入昵称" value="昵称">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">真实姓名</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text"  placeholder="请输入真实姓名" value="真实姓名">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">我的手机号</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="tel" placeholder="请输入手机号"  value="手机号">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">联系微信</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" placeholder="请输入微信号">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">联系手机</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="tel"  placeholder="请输入手机号" value="手机号">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">修改密码</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="password"  placeholder="请输入密码">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">确认密码</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="password"  placeholder="请输入密码">
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
        <div class="weui-flex__item"><a href="javascript:;" class="weui-btn weui-btn_primary">修改</a></div>
    </div>
    </body>
    <script>
        $(function () {
            FastClick.attach(document.body);
        });
    </script>
@endsection