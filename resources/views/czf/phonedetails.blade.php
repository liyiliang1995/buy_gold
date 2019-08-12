@extends('czf.base',['header'=>'充值详情',
    'css' => [
            'css/weui.min.css',
            'css/jquery-weui.css',
            'css/demos.css',
            '//at.alicdn.com/t/font_1300674_hkj5czu71b.css'
        ],
    'js' => [
            'js/fastclick.js',
            'js/jquery-weui.js',

        ],
])
@section('content')
    <body>
    <!--头部-->
    <div class="weui-flex" id="header_top">
        <a href="javascript:history.back(-1)"><img src="{{route('home')}}/img/fh.png" alt=""></a>
        <div class="weui-flex__item">充值详情</div>
    </div>

    <div style="background: #fff;overflow: hidden;">
        <div class="weui-flex">
            <div class="weui-flex__item"><i id="but_money" class="iconfont icon-icon_mobilephone"></i></div>
        </div>
        <div class="weui-flex" style="margin-top: 10px;text-align: center">
            <div class="weui-flex__item"><p>手机充值金额</p></div>
        </div>
        <div class="weui-flex" style="text-align: center">
            <div class="weui-flex__item"><h1>100</h1></div>
        </div>
        <div class="weui-loadmore weui-loadmore_line weui-loadmore_dot" style="margin-bottom: 0;">
            <span class="weui-loadmore__tips"></span>
        </div>
        <div class="buy_conter">
            <div class="weui-row">
                <div class="weui-col-50" style="width: 30%;color: #666">当前状态</div>
                <div class="weui-col-50" style="width: 70%">交易中</div>
            </div>
            <div class="weui-row">
                <div class="weui-col-50" style="width: 30%;color: #666">需支付金币</div>
                <div class="weui-col-50" style="width: 70%">240</div>
            </div>
            <div class="weui-row">
                <div class="weui-col-50" style="width: 30%;color: #666">挂单时间</div>
                <div class="weui-col-50" style="width: 70%">2019-08-05 15：33：12</div>
            </div>

                <div class="weui-row">
                    <div class="weui-col-50" style="width: 30%;color: #666">成交时间</div>
                    <div class="weui-col-50" style="width: 70%"></div>
                </div>

        </div>
    </div>

    <div style="background: #fff;overflow: hidden;margin-top: 10px;padding: 15px;">
        <div class="weui-flex">
            <div class="weui-flex__item" style="line-height: 30px;font-weight: bold">挂单人信息</div>
        </div>
        <div class="weui-row">
            <div class="weui-col-50" style="width: 30%;color: #666">对方姓名</div>
            <div class="weui-col-50" style="width: 70%">淡淡</div>
        </div>
        <div class="weui-row">
            <div class="weui-col-50" style="width: 30%;color: #666">联系电话</div>
            <div class="weui-col-50" style="width: 70%">13873653398</div>
        </div>
        <div class="weui-row">
            <div class="weui-col-50" style="width: 30%;color: #666">微信号</div>
            <div class="weui-col-50" style="width: 70%">wechat</div>
        </div>
    </div>
    </body>


@endsection
