@extends('czf.base',['header'=>'交易中心',
'css' => [
        'css/weui.min.css',
        'css/jquery-weui.css',
        'css/demos.css'
    ],
'js' => [
        'js/fastclick.js',
        'js/jquery-weui.js',
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

        #p1 {
            height: 300px;
        }

        .radio_style {
            overflow: hidden;
            padding: 15px;
        }

        .radio_style label {
            width: 25%;
            float: left;
            text-align: left;
            line-height: 35px;
        }

        label {
            display: inline-block;
            width: 100px;
            text-align: left;
            line-height: 26px;
        }

        label input[type="radio"] {
            display: none;
        }

        label input[type="radio"] + span {
            box-sizing: border-box;
            display: inline-block;
            width: 20px;
            height: 20px;
            padding: 2px;
            border: 2px solid #ccc;
            vertical-align: sub;
            margin-right: 5px;
        }

        label input[type="radio"] + span > span {
            display: inline-block;
            width: 10px;
            height: 10px;
            float: left;
            background: #33bb00;
            opacity: 0;
        }

        label input[type="radio"]:checked + span {
            border-color: #33bb00;
            background: #33bb00;
        }

        label:hover input[type="radio"] + span > span {
            opacity: 0.5;
        }

        label input[type="radio"]:checked + span > span {
            opacity: 1;
        }

        label input[type="radio"] + span {
            border-radius: 50%;
        }
    </style>
    <body>
    <!--头部-->
    <div class="weui-flex" id="header_top">
        <a href="{{route('member_index')}}"><img src="{{route('home')}}/img/fh.png" alt=""></a>
        <div class="weui-flex__item">交易中心</div>
    </div>

    <div class="weui-flex" style="border-bottom: 2px solid #ccc;">
        <div class="weui-flex__item" id="trading_va">今日均价：￥<b>{{$avgPrice}}</b></div>
    </div>
    </div>
    <div class="weui-flex"
         style="padding-top: 20px;padding-left:15px;background: #fff;border-bottom: 1px solid #ccc;font-weight: bold;">
        <div class="weui-flex__item">金币求购 <span style="font-weight: normal;font-size: 12px;">（成交后请在“我的交易”中联系对方线下收付款）</span></div>
    </div>

    <div class="weui-cells weui-cells_form" style="margin-top: 0;">
        <form action="{{ route('buy_gold')}}" method="post" id="submit_buy">
            <div class="radio_style">
                <label><input name="gold" type="radio" value="50" checked/><span></span><span></span>50 </label>
                <label><input name="gold" type="radio" value="100"/><span></span><span></span>100 </label>
                <label><input name="gold" type="radio" value="200"/><span></span><span></span>200 </label>
                <label><input name="gold" type="radio" value="500"/><span></span><span></span>500 </label>
                <label><input name="gold" type="radio" value="1000"/><span></span><span></span>1000 </label>
                <label><input name="gold" type="radio" value="2000"/><span></span><span></span>2000 </label>
                <label><input name="gold" type="radio" value="5000"/><span></span><span></span>5000 </label>
                <label><input name="gold" type="radio" value="10000"/><span></span><span></span>10000 </label>
            </div>

            <div class="weui-cell weui-cell_vcode" style="padding: 15px;">
                <div class="weui-cell__hd" style="width: 10%"><label class="weui-label">价格</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" id="pre" type="text" name="price" value="{{old('price')}}"
                           onkeyup="this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''"
                           placeholder="请输入价格">
                </div>
                <div class="weui-cell__bd" style="font-size: 12px">
                    <span>指导价：{{$fGuidancePrice['min']}}-{{$fGuidancePrice['max']}}</span>
                </div>
                <div class="weui-cell__bd" style="text-align: right;">
                    <span id="intext">金额 <b style="color: red;">￥0</b></span>
                </div>
            </div>
            @csrf
        </form>
    </div>
    <div class="weui-flex" style="padding:5px 30px;background: #fff;">
        <div class="weui-flex__item"><a href="javascript:;" id="submit" class="weui-btn weui-btn_primary"
                                        style="background: #FF2634;">求购</a></div>
    </div>

    <div class="weui-flex"
         style="padding-top: 20px;padding-left:15px;background: #fff;border-bottom: 1px solid #ccc;font-weight: bold;margin-top: 10px;">
        <div class="weui-flex__item">求购列表</div>
    </div>
    <div class="weui-flex" id="trading_list">
        @if($member->is_admin == 1)
            <div class="weui-flex__item">姓名</div>
        @endif
        <div class="weui-flex__item">数量</div>
        <div class="weui-flex__item">单价</div>
        <div class="weui-flex__item">金额</div>
        <div class="weui-flex__item">操作</div>
    </div>
    @foreach($aBuyGold as $value)
        <div class="weui-flex" id="trading_list">
            @if($member->is_admin == 1)
                <div class="weui-flex__item">{{$value->member->name}}</div>
            @endif
            <div class="weui-flex__item">{{$value->gold}}</div>
            <div class="weui-flex__item">{{$value->price}}</div>
            <div class="weui-flex__item">{{$value->sum_price}}</div>
            <div class="weui-flex__item">
                @if(userId() != $value->user_id)
                    <a href="{{route('sell_gold',['id'=>$value->id])}}" class="weui-btn weui-btn_primary"
                       style="margin-top:5px; width: 60px; height: 30px;  padding: 0 10px;font-size: 12px;">出售</a>
                @else
                    <a href="javascript:void(0)" disabled="disabled" class="weui-btn weui-btn_disabled weui-btn_primary"
                       style="margin-top:5px; width: 60px; height: 30px;  padding: 0 10px;font-size: 12px;">出售</a>
                @endif
            </div>
        </div>
    @endforeach
    </body>
    <script>
        var min = {{$fGuidancePrice['min']}};
        var max = {{$fGuidancePrice['max']}};
        // 购买金币
        var buy_gold = {
            unit_price_val: 0,
            sum_val: 0,
            unit_price: function () {
                if (!this.unit_price_val || this.unit_price_val < 0) {
                    $.toast("请输入正确的购买价格！", 'text');
                    return;
                } else if (this.unit_price_val < min || this.unit_price_val > max) {

                    $.toast("请输入区间内价格!", 'text');
                    return;
                } else {
                    return this.unit_price_val;
                }
            },
            sum: function () {
                if (!this.sum_val || this.sum_val < 0) {
                    $.toast("请输入正确的购买数量！", 'text');
                } else {
                    return this.sum_val;
                }
            },
            submit_buy: function () {
                if (this.unit_price() && this.sum_val) {

                    $("#submit_buy").submit();
                }
            },
            intToFloat: function (val) {
                var num = 2;
                // return new Number(val).toFixed(2);
                var a_type = typeof (val);
                if (a_type == "number") {
                    var aStr = val.toString();
                    var aArr = aStr.split('.');
                } else if (a_type == "string") {
                    var aArr = val.split('.');
                }

                if (aArr.length > 1) {
                    val = aArr[0] + "." + aArr[1].substr(0, num);
                }
                return val;
            },
        }
        $(function () {
            @if($errors->has('gold'))
            $.toast("{{$errors->get('gold')[0]}}", 'text');
            @endif
            @if($errors->has('price'))
            $.toast("{{$errors->get('price')[0]}}", 'text');
            @endif
        });

        $("#pre").on("blur", function () {
            var item = $('input[name="gold"]:checked').val();
            var price = $(this).val().trim();
            var min = {{$fGuidancePrice['min']}};
            var max = {{$fGuidancePrice['max']}};
            if (price >= min && price <= max) {
                var sum = buy_gold.intToFloat(price * item);
                $("#intext>b").empty().html("￥" + sum);
            } else {
                $.toast("请输入区间内价格！", "text");
                return false;
            }

        });
        $("#submit").on("click",function () {
        buy_gold.unit_price_val = $("#pre").val().trim();
        buy_gold.sum_val = $('input[name="gold"]:checked').val();
        buy_gold.submit_buy();
        });
    </script>
    <script>
        var url = "{{route('get_trend')}}";
        $.ajax({
            url: url,
            type: "get",
            dataType: "json",
            error: function (data) {
                $.toast("服务器繁忙, 请联系管理员！", "text");
                return;
            },
            success: function (result) {
                console.log(result);
                var dom = document.getElementById("p1");
                var myChart = echarts.init(dom);
                var app = {};
                option = null;
                option = {
                    xAxis: {
                        type: "category",
                        data: result.data.adata
                    },
                    yAxis: {
                        type: "value"
                    },
                    series: [{
                        type: "line",
                        data: result.data.bdata
                    }]
                };
                if (option && typeof option === "object") {
                    myChart.setOption(option, true);
                }
            }
        });


    </script>
@endsection
