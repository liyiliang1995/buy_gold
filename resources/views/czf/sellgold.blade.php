@extends('czf.base',['header'=>'出售详情',
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
    <div class="weui-flex" id="sell">
        <div class="weui-flex__item">金币数量</div>
        <div class="weui-flex__item" id="sell_num">{{$oBuyGoldDetail->gold}}</div>
    </div>
    <div class="weui-flex" id="sell">
        <div class="weui-flex__item">金币单价</div>
        <div class="weui-flex__item" id="sell_num">{{$oBuyGoldDetail->price}}</div>
    </div>
    <div class="weui-flex" id="sell">
        <div class="weui-flex__item">出售总额</div>
        <div class="weui-flex__item" id="sell_num" style="color: red">{{$oBuyGoldDetail->sum_price}}</div>
    </div>
    <div class="weui-flex" id="sell">
        <div class="weui-flex__item">燃烧金币</div>
        <div class="weui-flex__item" id="sell_num">{{$oBuyGoldDetail->burn_gold}}</div>
    </div>
    <div class="weui-flex" id="sell">
        <div class="weui-flex__item">消耗积分</div>
        <div class="weui-flex__item" id="sell_num">{{$oBuyGoldDetail->consume_integral}}</div>
    </div>

    <div class="weui-row" id="sell_but">
        <div class="weui-col-50" style="width: 60%;text-align: center;    line-height: 50px;">合计：<b style="color: red;">{{$oBuyGoldDetail->sum_gold}}金币</b>（消耗{{$oBuyGoldDetail->consume_integral}}积分）</div>
        <div class="weui-col-50" style="width: 40%"><a  href="{{route('sell_gold_order',['id'=>$oBuyGoldDetail->id])}}" class="weui-btn weui-btn_primary" style="height: 50px;border-radius: 0;">提交订单</a></div>
    </div>
    </body>
    <script>
        function checkout() {
            $.toast("操作成功");
        }

        $(function () {
            @if($errors->has('gold'))
                $.toast("{{$errors->get('gold')[0]}}", 'text');
            @endif
        })
    </script>
@endsection
