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

    ],
'script'=> [

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
        <div class="weui-flex__item">手机充值</div>
    </div>


    <div class="weui-cells weui-cells_form" style="margin-top: 0;padding-top: 15px;">
        <form action="{{ route('phone_buy_gold')}}" method="post" id="submit_buy">
            @csrf
            <div class="radio_style">
                <label><input name="money" @if(old('money') == 50)checked="checked"@endif type="radio" value="50"/><span></span><span></span>50元 </label>
                <label><input name="money" @if(old('money') == 100)checked="checked"@endif type="radio" value="100"/><span></span><span></span>100元 </label>
                <label><input name="money" @if(old('money') == 200)checked="checked"@endif type="radio" value="200"/><span></span><span></span> 200元 </label>
                <label><input name="money" @if(old('money') == 300)checked="checked"@endif type="radio" value="300"/><span></span><span></span>300元 </label>
            </div>
        </form>
    </div>
    <div class="weui-flex" style="padding: 15px;background: #fff;padding-bottom: 0;">
        <div class="weui-flex__item" style="text-align: center"><B id="sum_pre">支付金币数量以抢单时的币价计算</B></div>
    </div>
    <div class="weui-flex" style="padding: 30px;background: #fff;">
        <div class="weui-flex__item"><a href="javascript:;" id="submit" class="weui-btn weui-btn_primary"
                                        style="background: #FF2634;">挂单</a></div>
    </div>


    <div class="weui-flex" id="trading_list" style=" margin-top: 10px;">
        @if($member->is_admin == 1)
            <div class="weui-flex__item">姓名</div>
        @endif
        <div class="weui-flex__item">充值金额</div>
        <div class="weui-flex__item">金币数</div>
        <div class="weui-flex__item">操作</div>
    </div>
    @foreach($aPhoneBuyGold as $value)
        <div class="weui-flex" id="trading_list">
            @if($member->is_admin == 1)
                <div class="weui-flex__item">{{$value->member->name}}</div>
            @endif
            <div class="weui-flex__item">{{$value->sum_price}}</div>
            <div class="weui-flex__item">{{$value->gold}}</div>
            <div class="weui-flex__item">
                @if(userId() != $value->user_id)
                    <a href="{{route('phone_sell',['id'=>$value->id])}}" class="weui-btn weui-btn_primary"
                       style="margin-top:5px; width: 60px; height: 30px;  padding: 0 10px;font-size: 12px;">抢单</a>
                @else
                    <a href="javascript:void(0)" disabled="disabled" class="weui-btn weui-btn_disabled weui-btn_primary"
                       style="margin-top:5px; width: 60px; height: 30px;  padding: 0 10px;font-size: 12px;">抢单</a>
                @endif
            </div>
        </div>
    @endforeach


    </body>
    <script>
        var phone_buy_gold = {
            avg_price: "{{$avgPrice}}",
            price: $('input[name="money"]:checked').val().trim(),
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
            // show_gold:function () {
            //     var gold = this.intToFloat(parseFloat(this.price)/parseFloat(this.avg_price));
            //     var show_gold = this.intToFloat(parseFloat(gold*1.2));
            //     $("#sum_pre").empty().html(show_gold);
            // }
        };
        $(document).ready(function () {
            // phone_buy_gold.show_gold();
            @if($errors->has('price'))
            $.toast("{{$errors->get('price')[0]}}", 'text');
            @endif

            // 点击选择
            $('input:radio').click(function () {
                phone_buy_gold.price = $('input[name="money"]:checked').val().trim();
                // phone_buy_gold.show_gold();
            });

            $("#submit").on('click', function () {
                $("#submit_buy").submit();
            })
        });
    </script>

@endsection
