@extends('czf.base',['header'=>'用户 - 完善信息',
'css' => [
        'https://cdn.bootcss.com/weui/2.0.1/style/weui.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/weui/2.0.1/style/weui.css',
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
    <body>
    <!--头部-->
    <div class="weui-flex" id="header_top">
        <a href="javascript:history.back(-1)"><img src="./img/fh.png" alt=""></a>
        <div class="weui-flex__item">我的伙伴</div>
    </div>

    <div class="weui-flex">
        <div class="weui-flex__item" style="line-height: 35px;padding: 10px;font-weight: bold">注册新用户</div>
    </div>

    <form action="">
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <input class="weui-input" type="tel" placeholder="请输入手机号">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="请输入密码">
                </div>
            </div>
        </div>
        <div class="weui-flex">
            <div class="weui-flex__item"><a href="javascript:;" class="weui-btn weui-btn_primary"
                                            style="width: 90%;margin-top: 30px;">注册</a></div>
        </div>
    </form>

    <div class="weui-flex">
        <div class="weui-flex__item" style="line-height: 35px;padding: 10px;font-weight: bold">我的伙伴</div>
    </div>

    <div class="weui-flex" id="partner_list">
        <div class="weui-flex__item">持有金币总数：<span style="color: #F44336">10000</span></div>
    </div>

    <div class="weui-flex" id="partner_list">
        <div class="weui-flex__item" id="partner_user">
            <p>张默默</p>
            <p>2019-05-05 加入</p>
        </div>
        <div class="weui-flex__item" style="line-height: 45px">
            金币数：<span>1203</span>
        </div>
        <div class="weui-flex__item">
            <a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_primary" style="background: #07C160">正常</a>
        </div>
    </div>

    <div class="weui-flex" id="partner_list">
        <div class="weui-flex__item" id="partner_user">
            <p>张默默</p>
            <p>2019-05-05 加入</p>
        </div>
        <div class="weui-flex__item" style="line-height: 45px">
            金币数：<span>1203</span>
        </div>
        <div class="weui-flex__item">
            <a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_primary" style="background: #9E9E9E">未注册</a>
        </div>
    </div>
    <div class="weui-flex" id="partner_list">
        <div class="weui-flex__item" id="partner_user">
            <p>张默默</p>
            <p>2019-05-05 加入</p>
        </div>
        <div class="weui-flex__item" style="line-height: 45px">
            金币数：<span>1203</span>
        </div>
        <div class="weui-flex__item">
            <a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_primary" style="background: #F44336">锁定</a>
        </div>
    </div>

    <div class="weui-flex" id="partner_list">
        <div class="weui-flex__item" id="partner_user">
            <p>张默默</p>
            <p>2019-05-05 加入</p>
        </div>
        <div class="weui-flex__item" style="line-height: 45px">
            金币数：<span>1203</span>
        </div>
        <div class="weui-flex__item">
            <a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_primary" style="background: #07C160">正常</a>
        </div>
    </div>
    </body>
@endsection