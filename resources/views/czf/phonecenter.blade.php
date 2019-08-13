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

        label input[type="radio"]+span{ border-radius:50%; }
    </style>
    <body>
    <!--头部-->
    <div class="weui-flex" id="header_top">
        <a href="{{route('member_index')}}"><img src="{{route('home')}}/img/fh.png" alt=""></a>
        <div class="weui-flex__item">手机充值</div>
    </div>


    <div class="weui-cells weui-cells_form" style="margin-top: 0;padding-top: 15px;">
        <form action="{{ route('buy_gold')}}" method="post" id="submit_buy">
            <div class="radio_style">
                <label><input name="money" type="radio" value="100" checked/><span></span><span></span>100元 </label>
                <label><input name="money" type="radio" value="200"/><span></span><span></span> 200元 </label>
                <label><input name="money" type="radio" value="300"/><span></span><span></span>300元 </label>
                <label><input name="money" type="radio" value="500"/><span></span><span></span>500元 </label>
            </div>
        </form>
    </div>
    <div class="weui-flex" style="padding: 15px;background: #fff;padding-bottom: 0;">
        <div class="weui-flex__item">需支付金币：<B id="sum_pre">0</B></div>
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
            <a href="{{route('phone_details')}}" class="weui-btn weui-btn_primary"
               style="margin-top:5px; width: 60px; height: 30px;  padding: 0 10px;font-size: 12px;">抢单</a>
        </div>
    </div>


    </body>
    <script>
        $(document).ready(function(){
            $('input:radio').click(function () {
                var pre = $('input[name="money"]:checked').val();
                document.getElementById('sum_pre').innerHTML = pre;
            });
        });
    </script>

@endsection
