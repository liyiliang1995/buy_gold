@extends('czf.base',['header'=>'手机充值',
'css' => [
        'css/weui.min.css',
        'css/jquery-weui.css',
        'css/demos.css'
    ],
'js' => [
        'js/fastclick.js',
        'js/jquery-weui.js',
        'js/Chart.min.js',
        'js/index.js'
    ],
'script'=> [
    '$("#job").select({
        title: "选择充值金额",
        items: ["100", "200", "300", "500"]
    });',
    '$("#submit").on("click",function () {
        buy_gold.unit_price_val = $("#pre").val().trim();
        buy_gold.sum_val = $("#job").val();
        buy_gold.submit_buy();
    });',
    '$("#pre").on("blur",function(){
        var item = $("#job").val();
        var price = $(this).val().trim();
        var sum = buy_gold.intToFloat(price*item);
        $("#intext>b").empty().html("￥"+sum);
    })',
    ],
])
@section('content')
    <style>
        .weui-btn_primary {
            background: #07c160;
        }

        .weui-icon-success {
            color: #07c160;
        }

        #trading_va {
            font-size: 14px;
        }

        .weui-flex__item {
            font-size: 14px;
        }

        .weui-cell {
            font-size: 14px;
        }

    </style>
    <body>


    <div class="weui-cells weui-cells_form" style="margin-top: 0;padding-top: 15px;">
        <form action="{{ route('buy_gold')}}" method="post" id="submit_buy">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label for="name" class="weui-label"
                                                  style="    width: 100%;">选择充值金额</label></div>
                <div class="weui-cell__bd">
                    <i class="weui-icon-success" style="position: absolute;"></i> <input class="weui-input" name="gold"
                                                                                         id="job" type="text"
                                                                                         value="100"
                                                                                         style="text-align: right;color: red;">
                </div>
            </div>

        </form>
    </div>
    <div class="weui-flex" style="padding: 15px;background: #fff;padding-bottom: 0;">
        <div class="weui-flex__item">需支付金币：<B>240</B>(价值￥120)</div>
    </div>
    <div class="weui-flex" style="padding: 30px;background: #fff;">
        <div class="weui-flex__item"><a href="javascript:;" id="submit" class="weui-btn weui-btn_primary"
                                        style="background: #FF2634;">挂单</a></div>
    </div>


    <div class="weui-flex" id="trading_list" style=" margin-top: 10px;">
        <div class="weui-flex__item">充值金额</div>
        <div class="weui-flex__item">金币数</div>

        <div class="weui-flex__item">操作</div>
    </div>

    <div class="weui-flex" id="trading_list">
        <div class="weui-flex__item">100</div>
        <div class="weui-flex__item">240</div>
        <div class="weui-flex__item">
            <a href="javascript:void(0)" class="weui-btn weui-btn_primary"
               style="margin-top:5px; width: 60px; height: 30px;  padding: 0 10px;font-size: 12px;">抢单</a>
        </div>
    </div>
    <div class="weui-flex" id="trading_list">
        <div class="weui-flex__item">100</div>
        <div class="weui-flex__item">240</div>
        <div class="weui-flex__item">
            <a href="javascript:void(0)" class="weui-btn weui-btn_primary"
               style="margin-top:5px; width: 60px; height: 30px;  padding: 0 10px;font-size: 12px;">抢单</a>
        </div>
    </div>
    <div class="weui-flex" id="trading_list">
        <div class="weui-flex__item">100</div>
        <div class="weui-flex__item">240</div>
        <div class="weui-flex__item">
            <a href="javascript:void(0)" class="weui-btn weui-btn_primary"
               style="margin-top:5px; width: 60px; height: 30px;  padding: 0 10px;font-size: 12px;">抢单</a>
        </div>
    </div>
    </body>


@endsection
