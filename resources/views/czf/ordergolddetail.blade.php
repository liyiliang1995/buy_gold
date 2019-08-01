@extends('czf.base',['header'=>'求购详情',
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
        <a href="javascript:history.back(-1)"><img src="/img/fh.png" alt=""></a>
        <div class="weui-flex__item">求购详情</div>
    </div>

    <div style="background: #fff;overflow: hidden;">
        <div class="weui-flex">
            <div class="weui-flex__item"><i id="but_money" class="iconfont icon-ico-jnljxse"></i></div>
        </div>
        <div class="weui-flex" style="margin-top: 10px;text-align: center">
            <div class="weui-flex__item"><p>求购金币总额</p></div>
        </div>
        <div class="weui-flex" style="text-align: center">
            <div class="weui-flex__item"><h1>{{$oOrderGold->sum_price}}</h1></div>
        </div>
        <div class="weui-loadmore weui-loadmore_line weui-loadmore_dot" style="margin-bottom: 0;">
            <span class="weui-loadmore__tips"></span>
        </div>
        <div class="buy_conter">
            <div class="weui-row">
                <div class="weui-col-50" style="width: 30%;color: #666">当前状态</div>
                <div class="weui-col-50" style="width: 70%">@if($oOrderGold->status == 0 )进行中 @else 已完成 @endif </div>
            </div>
            <div class="weui-row">
                <div class="weui-col-50" style="width: 30%;color: #666">金币数量</div>
                <div class="weui-col-50" style="width: 70%">{{$oOrderGold->gold}}</div>
            </div>
            <div class="weui-row">
                <div class="weui-col-50" style="width: 30%;color: #666">求购价格</div>
                <div class="weui-col-50" style="width: 70%">{{$oOrderGold->price}}</div>
            </div>
            <div class="weui-row">
                <div class="weui-col-50" style="width: 30%;color: #666">求购时间</div>
                <div class="weui-col-50" style="width: 70%">{{$oOrderGold->created_at}}</div>
            </div>
            @if($oOrderGold->status == 1 )
                <div class="weui-row">
                    <div class="weui-col-50" style="width: 30%;color: #666">成交时间</div>
                    <div class="weui-col-50" style="width: 70%">{{$oOrderGold->updated_at}}</div>
                </div>
            @endif
        </div>
    </div>

    <div style="background: #fff;overflow: hidden;margin-top: 10px;padding: 15px;">
        <div class="weui-flex">
            <div class="weui-flex__item" style="line-height: 30px;font-weight: bold">出售人信息</div>
        </div>
        <div class="weui-row">
            <div class="weui-col-50" style="width: 30%;color: #666">对方姓名</div>
            <div class="weui-col-50" style="width: 70%">{{$oOrderGold->member->name}}</div>
        </div>
        <div class="weui-row">
            <div class="weui-col-50" style="width: 30%;color: #666">联系电话</div>
            <div class="weui-col-50" style="width: 70%">{{$oOrderGold->member->phone2}}</div>
        </div>
        <div class="weui-row">
            <div class="weui-col-50" style="width: 30%;color: #666">微信号</div>
            <div class="weui-col-50" style="width: 70%">{{$oOrderGold->member->wechat}}</div>
        </div>
    </div>
    </body>


@endsection
