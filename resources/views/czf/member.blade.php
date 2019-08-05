@extends('czf.base',['header'=>'会员中心',
'css' => [
        '//at.alicdn.com/t/font_1300674_bwcd8riknaj.css',
        'css/weui.min.css',
        'css/jquery-weui.css',
        'css/demos.css'
    ],
'js' => [
        'js/jquery-weui.js',
        'js/fastclick.js',
    ],
   'script'=> [
   '$("#submit").bind("click",function () {
        $.toast("领取成功");
    });',
    ],
])
@section('content')
    <style>
        .weui-btn_plain-primary{
            color: #07C160;
            border: 1px solid #07C160;
            height: 40px;
            line-height: 40px;
            font-size: 14px;

        }
        .weui-cell__bd p{
            font-size: 14px;
            color: #666;
        }
        .weui-cell__hd i{
            padding: 10px;
        }
        #member_but p{
            color: red;
        }
        #member_but i{
            color: red;
        }
    </style>
    <body>
    <!--个人中心-->
    <div class="user_bg">
        <div class="weui-flex">
            <div class="weui-flex__item" id="user_tx">
                <div class="weui-flex">
                    <div class="weui-flex__item"><img src="./img/logo.png" alt=""></div>
                    <div class="weui-flex__item" style="width: 200px;"><p>{{$member->name}}</p></div>
                </div>
            </div>
            <div class="weui-flex__item">
                <a href="{{route('userset')}}">
                    <i style="float: right;margin: 5%;color: #fff" class="iconfont icon-setting"></i>
                </a>
            </div>
        </div>

        <div class="weui-flex" id="user_value">
            <div class="weui-flex__item"><p>{{$member->gold}}</p>
                <p>当前金币</p></div>
            <div class="weui-flex__item"><p>{{$member->integral}}</p>
                <p>当前积分</p></div>
            <div class="weui-flex__item"><p>{{$member->energy}}</p>
                <p>能量值</p></div>
        </div>
    </div>

    <div class="weui-flex" id="user_bg">
        <div class="weui-flex__item" id="user_gold">
            <p>当前金币总数：99999999</p>
            <p>其中币池剩余：99999</p>
            <p>本次可领取：<span>200</span></p>
            <p>距下次领取：2分40秒</p>
        </div>
        <div class="weui-flex__item" id="user_but">
            <a href="javascript:;" id="submit" class="weui-btn weui-btn_plain-primary">手动领取</a>
            <div class="weui-cell__ft" style="margin-top: 20px;  font-size: 14px;  font-weight: bold;">
                <p>自动领取</p><input class="weui-switch" type="checkbox">
            </div>
        </div>
    </div>
    </div>

    <div class="weui-cells">
        <a class="weui-cell weui-cell_access" href="{{route('order_list')}}">
            <div class="weui-cell__hd"><i class="iconfont icon-setting"></i></div>
            <div class="weui-cell__bd">
                <p>我的订单</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
    </div>

    <div class="weui-cells">
        <a class="weui-cell weui-cell_access" href="{{route('trade_center')}}">
            <div class="weui-cell__hd"><i class="iconfont icon-transaction"></i></div>
            <div class="weui-cell__bd">
                <p>交易中心</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
        <a class="weui-cell weui-cell_access" href="{{route('trade_record')}}">
            <div class="weui-cell__hd"><i class="iconfont icon-ico-jnljxse"></i></div>
            <div class="weui-cell__bd">
                <p>我的交易</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
        <a class="weui-cell weui-cell_access" href="{{route('phone_center')}}">
            <div class="weui-cell__hd"><i class="iconfont icon-Dollar"></i></div>
            <div class="weui-cell__bd">
                <p>手机充值</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
        <a class="weui-cell weui-cell_access" href="{{route('phone_center')}}">
            <div class="weui-cell__hd"><i class="iconfont icon-Dollar"></i></div>
            <div class="weui-cell__bd">
                <p>充值记录</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
    </div>

    <div class="weui-cells" style="margin-bottom: 80px;">
        <a class="weui-cell weui-cell_access" href="{{route('myPartner')}}">
            <div class="weui-cell__hd"><i class="iconfont icon-adduser"></i></div>
            <div class="weui-cell__bd">
                <p>我的伙伴</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
        <a class="weui-cell weui-cell_access" href="{{route('help_center')}}">
            <div class="weui-cell__hd"><i class="iconfont icon-bulb"></i></div>
            <div class="weui-cell__bd">
                <p>帮助中心</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
        <a class="weui-cell weui-cell_access" href="{{route('notification_list')}}">
            <div class="weui-cell__hd"><i class="iconfont icon-sound"></i></div>
            <div class="weui-cell__bd">
                <p>通知公告</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
        <a class="weui-cell weui-cell_access" href="javascript:;">
            <div class="weui-cell__hd"><i class="iconfont icon-mail"></i></div>
            <div class="weui-cell__bd">
                <p>问题建议</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
    </div>

    <!--footer-->
    <div class="weui-tab" id="weui-tab">
        <div class="weui-tabbar">

            <a href="{{route('home')}}" class="weui-tabbar__item">
                <div class="weui-tabbar__icon">
                    <i class="iconfont icon-home-fill"></i>
                </div>
                <p class="weui-tabbar__label">首页</p>
            </a>
            <a href="{{route('member_index')}}" class="weui-tabbar__item" id="member_but">
                <div class="weui-tabbar__icon">
                    <i class="iconfont icon-user-fill"></i>
                </div>
                <p class="weui-tabbar__label">个人中心</p>
            </a>
        </div>
    </div>

    </body>

@endsection
