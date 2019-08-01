@extends('czf.base',['header'=>'交易中心',
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
        title: "选择出售金币数",
        items: ["100", "200", "500", "1000", "2000", "5000", "10000"]
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
        .weui-btn_primary{
            background: #07c160;
        }
        .weui-icon-success{
            color:#07c160 ;
        }
    </style>
    <body>
    <!--头部-->
    <div class="weui-flex" id="header_top">
        <a href="javascript:history.back(-1)"><img src="./img/fh.png" alt=""></a>
        <div class="weui-flex__item">交易中心</div>
    </div>

    <div class="weui-flex" style="border-bottom: 2px solid #ccc;">
        <div class="weui-flex__item" id="trading_va">今日均价：<b>0.82</b></div>
    </div>


    <div class='wrapper'>
        <div class='chart' id='p1'>
            <canvas id='c1'></canvas>
        </div>
    </div>
    </div>

    <div class="weui-flex"
         style="padding-top: 20px;padding-left:15px;background: #fff;border-bottom: 1px solid #ccc;font-weight: bold;margin-top: 10px;">
        <div class="weui-flex__item">金币求购</div>
    </div>

    <div class="weui-cells weui-cells_form" style="margin-top: 0;">
        <form action="{{ route('buy_gold')}}" method="post" id="submit_buy">
        <div class="weui-cell">
            <div class="weui-cell__hd"><label for="name" class="weui-label"
                                              style="    width: 100%;">选择出售金币数</label></div>
            <div class="weui-cell__bd">
                <i class="weui-icon-success" style="position: absolute;"></i> <input class="weui-input" name="gold" id="job" type="text"
                                                                                     value="100"
                                                                                     style="text-align: right;color: red;">
            </div>
        </div>
        <div class="weui-cell weui-cell_vcode" style="padding: 15px;">
            <div class="weui-cell__hd" style="width: 10%"><label class="weui-label">价格</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" id="pre" type="text" name="price"  value="{{old('price')}}" onkeyup="this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''" placeholder="请输入价格">
            </div>
            <div class="weui-cell__bd" style="font-size: 12px">
                <span>指导价：0.81-0.85</span>
            </div>
            <div class="weui-cell__bd" style="text-align: right;">
                <span id="intext">金额 <b style="color: red;">￥99</b></span>
            </div>
        </div>
            @csrf
        </form>
    </div>
    <div class="weui-flex" style="padding: 30px;background: #fff;">
        <div class="weui-flex__item"><a href="javascript:;" id="submit" class="weui-btn weui-btn_primary"
                                        style="background: #FF2634;">求购</a></div>
    </div>

    <div class="weui-flex"
         style="padding-top: 20px;padding-left:15px;background: #fff;border-bottom: 1px solid #ccc;font-weight: bold;margin-top: 10px;">
        <div class="weui-flex__item">求购列表</div>
    </div>
    <div class="weui-flex" id="trading_list">
        <div class="weui-flex__item">数量</div>
        <div class="weui-flex__item">单价</div>
        <div class="weui-flex__item">金额</div>
        <div class="weui-flex__item">操作</div>
    </div>
    @foreach($aBuyGold as $value)
    <div class="weui-flex" id="trading_list">
        <div class="weui-flex__item">{{$value->gold}}</div>
        <div class="weui-flex__item">{{$value->price}}</div>
        <div class="weui-flex__item">{{$value->sum_price}}</div>
        <div class="weui-flex__item">
            @if(userId() != $value->user_id)
                <a href="{{route('sell_gold',['id'=>$value->id])}}" class="weui-btn weui-btn_primary" style="margin-top:5px; width: 60px; height: 30px;  padding: 0 10px;font-size: 12px;">出售</a>
            @else
                <a href="javascript:void(0)"  disabled="disabled" class="weui-btn weui-btn_disabled weui-btn_primary" style="margin-top:5px; width: 60px; height: 30px;  padding: 0 10px;font-size: 12px;">出售</a>
            @endif
        </div>
    </div>
    @endforeach
    </body>
    <script>
        // 购买金币
        var buy_gold = {
            unit_price_val:0,
            sum_val:0,
            unit_price:function () {
                if (!this.unit_price_val || this.unit_price_val < 0) {
                    $.toast("请输入正确的购买价格！", 'text');
                    return;
                } else {
                    return this.unit_price_val;
                }
            },
            sum:function () {
                if(!this.sum_val || this.sum_val <0) {
                    $.toast("请输入正确的购买数量！", 'text');
                } else {
                    return this.sum_val;
                }
            },
            submit_buy:function () {
                if (this.unit_price() && this.sum_val) {
                    $("#submit_buy").submit();
                }
            },
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
        }
        $(function () {
            @if($errors->has('gold'))
                $.toast("{{$errors->get('gold')[0]}}", 'text');
            @endif
            @if($errors->has('price'))
                $.toast("{{$errors->get('price')[0]}}", 'text');
            @endif
        })
    </script>

@endsection