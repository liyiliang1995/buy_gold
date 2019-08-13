@extends('czf.base',['header'=>'订单提交 - 翠竹坊',
'css' => [
        'http://at.alicdn.com/t/font_1300674_bwcd8riknaj.css',
        'css/weui.min.css',
        'css/jquery-weui.min.css',
        'css/swiper.min.css',
        'css/style.css',
        'css/demos.css'
    ],
'js' => [
        'js/jquery-weui.min.js',
        'js/swiper.min.js',
        'js/city-picker.min.js'
    ],
'script' => [
        'add_delete();',
        'function add_delete () {
            var MAX = 99, MIN = 1;
            $(".weui-count__decrease").click(function (e) {
                var $input = $(e.currentTarget).parent().find(".weui-count__number");
                console.log($input.val());
                var number = parseInt($input.val() || "0") - 1
                if (number < MIN) number = MIN;
                $input.val(number);
                order.goods_unit_price=$("#goods_unit_price").html().trim();
                order.goods_num=number;
                order.show_price();
            });
            $(".weui-count__increase").click(function (e) {
                var $input = $(e.currentTarget).parent().find(".weui-count__number");
                var number = parseInt($input.val() || "0") + 1
                if (number > MAX) number = MAX;
                $input.val(number);
                order.goods_unit_price=$("#goods_unit_price").html().trim();
                order.goods_num=number;
                order.show_price();
            });
        }'
    ],
])
@section('content')
    <style>
        #order_up a{
            height: 50px !important;
            line-height: 50px !important;
            padding: 0 !important;
        }
        #order_up p{
            line-height: 50px;
        }
    </style>
    <body class="confirm-order">
    <!--头部-->
    <div class="weui-flex" id="header_top">
        <a href="javascript:history.back(-1)"><img src="{{route('home')}}/img/fh.png" alt=""></a>
        <div class="weui-flex__item">订单提交</div>
    </div>

    <div class="weui-panel order-address" style="margin-top: 0;">
        <div class="weui-panel__bd">
            <div class="weui-media-box weui-media-box_small-appmsg">
                <div class="weui-cells">
                    <a class="weui-cell weui-cell_access" href="{{route('getEditAddress',['url'=>url()->full()])}}">
                        <div class="weui-cell__hd">
                            <i class="iconfont icon-location"></i>
                        </div>
                        <div class="weui-cell__bd weui-cell_primary">
                            <div class="weui-flex">
                                <div class="weui-flex__item contact_name">收货人：{{$oUser->ship_address->name ?? ''}}</div>
                                <div class="weui-flex__item contact_tel">{{$oUser->ship_address->phone ?? ''}}</div>
                            </div>
                            <p class="contact_address">收货地址：{{$oUser->ship_address->ship_address ?? ''}}</p>
                        </div>
                        <span class="weui-cell__ft"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="weui-panel order-info weui-panel_access">
        <div class="weui-panel__bd">
            <div class="weui-row">
                <div class="weui-col-33">
                    <img class="weui-media-box__thumb" width="100%" src="{{czf_asset($oGoods->list_img)}}" alt="">
                </div>
                <div class="weui-col-66">
                    <h4 class="weui-media-box__title">{{$oGoods->name}}</h4>
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <em style="">¥</em>
                            <span style="" id="goods_unit_price">{{$oGoods->amount}}/{{$oGoods->unit}}</span>
                        </div>
                        <div class="weui-cell__ft">
                            <div class="weui-count">
                                <a class="weui-count__btn weui-count__decrease"></a>
                                <input class="weui-count__number" type="number" id="goods_num" value="1" />
                                <a class="weui-count__btn weui-count__increase"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="weui-panel__ft">
            <a href="javascript:void(0);" class="weui-cell weui-cell_access weui-cell_link">
                <div class="weui-cell__bd" style="color: #333333">配送方式</div>
                <span class="weui-cell__ft">快递</span>
            </a>
        </div>
    </div>
    <div class="weui-cells__title message">买家留言</div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <textarea class="weui-textarea" placeholder="请输入文本" id="other" rows="3"></textarea>
                <div class="weui-textarea-counter"><span>0</span>/200</div>
            </div>
        </div>
    </div>
    <!-- footer -->
    <div class="weui-footer_fixed-bottom">
        <div class="weui-tabbar" id="order_up">
            <a href="javascript:;" class="weui-tabbar__item">
                @php
                    $pay_gold = pay_gold($hour_avg_price,$oGoods->amount);
                @endphp
                <p class="weui-tabbar__label" id="show_price">合计:<span>{{$oGoods->amount}}元</span>（{{sum_gold($pay_gold,burn_gold($pay_gold))}}金币）</p>
            </a>
            <a href="javascript:void(0)" id="sbm" class="weui-tabbar__item weui-bar__item--on">
                <p class="weui-tabbar__label">提交订单</p>
            </a>
        </div>
    </div>
    </body>
    <script>
        var order = {
            gold_price:"{{$hour_avg_price}}",
            goods_unit_price:$("#goods_unit_price").html().trim(),
            goods_num:$("#goods_num").val().trim(),
            other:$("#other").val().trim(),
            // 保留两位数
            intToFloat:function(val) {
                var num = 2;
               // return new Number(val).toFixed(2);
                var a_type = typeof(val);
                if(a_type == "number"){
                    var aStr = val.toString();
                    var aArr = aStr.split('.');
                }else if(a_type == "string"){
                    var aArr = val.split('.');
                }

                if(aArr.length > 1) {
                    val = aArr[0] + "." + aArr[1].substr(0, num);
                }
                return val;
            },
            show_price:function () {
                var price = this.intToFloat(parseFloat(this.goods_unit_price) * parseFloat(this.goods_num));
                var gold = this.intToFloat(parseFloat(price)/parseFloat(this.gold_price));
                var burn_gold = this.intToFloat(parseFloat(gold)*0.05);
                var sum_gold = this.intToFloat(parseFloat(gold)+parseFloat(burn_gold));
                $("#show_price").empty().html("合计:<span>"+price+"元</span>("+sum_gold+")金币");
            },
            ajax_submit:function () {
                $.ajax({
                    url: "{{route('order_save',['goodsId'=>$oGoods->id])}}",
                    type:'post',
                    dataType: "json",
                    data:{ num:this.goods_num,glod_price:this.gold_price,other:this.other,_method:'post' ,_token:"{{csrf_token()}}"},
                    error:function(data){
                        $.toast("服务器繁忙, 请联系管理员！",'text');
                        return;
                    },
                    success:function(result){
                        if(result.code == 200){
                            window.location.href = result.data.url;
                        } else {
                            $.toast(result.message,'text')
                        }
                    },
                })
            }
        }
        $(function () {
            $("#sbm").on('click',function () {
                order.goods_num = $("#goods_num").val().trim();
                order.other = $("#other").val().trim();
                order.ajax_submit()
            })
        })
    </script>
@endsection
