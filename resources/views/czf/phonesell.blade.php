@extends('czf.base',['header'=>'充值详情',
'css' => [
        'css/weui.min.css',
        'css/jquery-weui.css',
        'css/demos.css'
    ],
'js' => [
        'js/fastclick.js',
        'js/jquery-weui.js',
    ]
])
@section('content')
    <style>
        .weui-btn_primary{
            background: #07c160 !important;
        }
    </style>
    <body>
    <!--头部-->
    <div class="weui-flex" id="header_top">
        <a href="javascript:history.back(-1)"><img src="{{route('home')}}/img/fh.png" alt=""></a>
        <div class="weui-flex__item">充值详情</div>
    </div>

    <div class="weui-flex" id="sell">
        <div class="weui-flex__item">充值金额</div>
        <div class="weui-flex__item" id="sell_num">{{$oPhoneBuyGold->sum_price}}元</div>
    </div>
    <div class="weui-flex" id="sell">
        <div class="weui-flex__item">金币价值</div>
        <div class="weui-flex__item" id="sell_num">{{$oPhoneBuyGold->price}}</div>
    </div>
    <div class="weui-flex" id="sell">
        <div class="weui-flex__item">金币总数</div>
        <div class="weui-flex__item" id="sell_num" style="color: red">{{$oPhoneBuyGold->gold}}</div>
    </div>

    <div class="weui-row" id="sell_but">
        <div class="weui-col-50" style="width: 70%;text-align: center;    line-height: 50px;">合计：<b style="color: red;">{{$oPhoneBuyGold->gold}}金币</b></div>
        <div class="weui-col-50" style="width: 30%"><a  href="{{route('phone_grab_order',['id'=>$oPhoneBuyGold->id])}}" class="weui-btn weui-btn_primary" style="height: 50px;border-radius: 0;">确认抢单</a></div>
    </div>
    </body>

@endsection
