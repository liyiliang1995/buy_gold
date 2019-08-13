@extends('czf.base',['header'=>'个人中心',
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
            color: #666;
        }
        #member_but p{
            color: red;
        }
        #member_but i{
            color: red;
        }
        #user_gold{
            width: 60%;
        }
        #user_but{
            width: 40%;
        }
        .weui-cell{
            padding: 10px !important;
        }
        .user_num{
            font-size: 16px;
            font-weight: bold;
        }
        .weui-cells{
            margin-top: 10px !important;
        }
        .weui-row{
            padding: 10px 15px;
        }
        .weui-cell_access .weui-cell__ft:after{
            border-color: #666 !important;
        }
    </style>
    <body>
    <!--个人中心-->
    <div class="user_bg">
        <div class="weui-flex">
            <div class="weui-flex__item" id="user_tx">
                <div class="weui-row">
                    <div class="weui-col-50"><img src="./img/logo.png" alt=""></div>
                    <div class="weui-col-50"><p>{{$member->name}}</p></div>
                </div>
            </div>
            <div class="weui-flex__item">
                <a href="{{route('userset')}}">
                    <i style="float: right;margin: 5%;color: #fff;font-size: 24px;" class="iconfont icon-setting"></i>
                </a>
            </div>
        </div>

        <div class="weui-flex" id="user_value">
            <div class="weui-flex__item"><a href="{{route('gold_record')}}"><p class="user_num">{{$member->gold}}</p>
                <p>金币</p></a></div>
            <div class="weui-flex__item"><a href="{{route('integral_record')}}"><p class="user_num">{{$member->integral}}</p>
                <p>积分</p></a></div>
            <div class="weui-flex__item"><a href="{{route('energy_record')}}"><p class="user_num">{{$member->energy}}</p>
                <p>能量</p></a></div>
        </div>
        <div class="weui-flex" id="user_dh">
            <div class="weui-flex__item"><a href="javascript:;" class="weui-btn weui-btn_primary">100金币兑换900积分</a></div>
        </div>
    </div>

    <div class="weui-row" id="user_bg" style="padding: 0 !important;">
        <div class="weui-col-50" id="user_gold">
            <p>当前金币总数：2000000000</p>
            <p>其中币池剩余：{{$gold_pool}}</p>
            <p>距下次领取：<b id="next_time">{{$gold_time}}</b><input type="text" style="display: none" value="{{$gold_time}}" id="next_time_f"></p>

        </div>
        <script>
            $(function(){
               function set_time() {
                var res = document.getElementById('next_time_f').value;
                var resa = res-1;
                if (resa == 0){
                    window.location.reload();
                    // return;
                }
                   var ssss = formatSeconds(resa);
                   document.getElementById("next_time").innerHTML = ssss;
                   document.getElementById("next_time_f").value = resa;
               }
              setInterval (function ()
                {
                    set_time();
                }, 1000);
            });
            function formatSeconds(value) {
                var theTime = parseInt(value);// 需要转换的时间秒
                var theTime1 = 0;// 分
                var theTime2 = 0;// 小时
                var theTime3 = 0;// 天
                if(theTime > 60) {
                    theTime1 = parseInt(theTime/60);
                    theTime = parseInt(theTime%60);
                    if(theTime1 > 60) {
                        theTime2 = parseInt(theTime1/60);
                        theTime1 = parseInt(theTime1%60);
                        if(theTime2 > 24){
                            //大于24小时
                            theTime3 = parseInt(theTime2/24);
                            theTime2 = parseInt(theTime2%24);
                        }
                    }
                }
                var result = '';
                if(theTime > 0){
                    result = ""+parseInt(theTime)+"秒";
                }
                if(theTime1 > 0) {
                    result = ""+parseInt(theTime1)+"分"+result;
                }
                if(theTime2 > 0) {
                    result = ""+parseInt(theTime2)+"小时"+result;
                }
                if(theTime3 > 0) {
                    result = ""+parseInt(theTime3)+"天"+result;
                }
                return result;
            }
        </script>
        <div class="weui-col-50" id="user_but">

                @if($is_auto == 1)
                <div id="check_a">
                    <a class="weui-btn weui-btn_plain-primary" style="color: #666;border: 1px solid #666;">自动领取中</a>
                </div>
                <div class="weui-cell__ft" style="margin-top: 20px;  font-size: 14px;  font-weight: bold;">
                    <p>自动领取</p>
                    <input class="weui-switch" type="checkbox"  onclick="checkboxOnclick(this)" checked>
                    @else
                        <div id="check_a">
                        <a href="javascript:;" id="submit" class="weui-btn weui-btn_plain-primary">手动领取</a>
                        </div>
                        <div class="weui-cell__ft" style="margin-top: 20px;  font-size: 14px;  font-weight: bold;">
                            <p>自动领取</p>
                    <input class="weui-switch" type="checkbox" onclick="checkboxOnclick(this)" >
                @endif
            </div>
        </div>
    </div>
    <script>
        function checkboxOnclick(checkbox) {
        if ( checkbox.checked == true){
            var url = "{{route('add_auto_gold',['type'=>1])}}";
            document.getElementById('check_a').innerHTML = "<a class='weui-btn weui-btn_plain-primary' style='color: #666;border: 1px solid #666;'>自动领取中</a>";
            }else{
            var url = "{{route('add_auto_gold',['type'=>0])}}";
            document.getElementById('check_a').innerHTML = "<a href='javascript:;' id='submit' class='weui-btn weui-btn_plain-primary'>手动领取</a>";
            }
            $.ajax({
                url: url,
                type: 'get',
                dataType: "json",
                error: function (data) {
                    $.toast("服务器繁忙, 请联系管理员！",'text');
                    return;
                },
                success: function (result) {
                    if (result.code != 200) {
                        $.toast(result.message, "forbidden");
                    }
                }
            });
        }

        $("#submit").bind("click",function () {
        var url = "{{route('manual_give_gold')}}";
        $.ajax({
            url: url,
            type: "get",
            dataType: "json",
            error: function (data) {
                $.toast("服务器繁忙, 请联系管理员！","text");
                return;
            },
            success: function (result) {
                if (result.code == 200){
                    $.toast("领取成功");
                }else{
                    $.toast(result.message, "forbidden");
                }
                console.log(result);
            }
        });
 });
    </script>

    </div>

    <div class="weui-cells">
        <a class="weui-cell weui-cell_access" href="{{route('order_list')}}">
            <div class="weui-cell__hd"><i class="iconfont icon-filesearch"></i></div>
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
            <div class="weui-cell__hd"><i class="iconfont icon-icon_mobilephone"></i></div>
            <div class="weui-cell__bd">
                <p>手机充值</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
        <a class="weui-cell weui-cell_access" href="{{route('phone_record')}}">
            <div class="weui-cell__hd"><i class="iconfont icon-mobile-check"></i></div>
            <div class="weui-cell__bd">
                <p>充值记录</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
        <a class="weui-cell weui-cell_access" href="{{route('gold_record')}}">
            <div class="weui-cell__hd"><i class="iconfont icon-qianbi101"></i></div>
            <div class="weui-cell__bd">
                <p>金币明细</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
        <a class="weui-cell weui-cell_access" href="{{route('integral_record')}}">
            <div class="weui-cell__hd"><i class="iconfont icon-accountbook"></i></div>
            <div class="weui-cell__bd">
                <p>积分明细</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
        <a class="weui-cell weui-cell_access" href="{{route('energy_record')}}">
            <div class="weui-cell__hd"><i class="iconfont icon-rocket"></i></div>
            <div class="weui-cell__bd">
                <p>能量明细</p>
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
        <a class="weui-cell weui-cell_access" href="tel:">
            <div class="weui-cell__hd"><i class="iconfont icon-mail"></i></div>
            <div class="weui-cell__bd">
                <p>联系客服</p>
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
