@extends('czf.base',['header'=>'订单列表',
'css' => [
        'css/weui.min.css',
        'css/jquery-weui.css',
        'css/demos.css'
    ],
'js' => [
        'js/fastclick.js',
        'js/jquery-weui.js',
    ],
'script'=> [
    'FastClick.attach(document.body);'
    ],
])
@section('content')
    <body ontouchstart>
    <!--头部-->
    <div class="weui-flex" id="header_top">
        <a href="javascript:history.back(-1)"><img src="./img/fh.png" alt=""></a>
        <div class="weui-flex__item">订单列表</div>
    </div>

    <div class="weui-tab">
        <div class="weui-navbar">
            <a class="weui-navbar__item weui-bar__item--on no_is_send" href="#tab1">
                待发货
            </a>
            <a class="weui-navbar__item is_send" href="#tab2">
                已发货
            </a>
        </div>
        <div class="weui-tab__bd">
            <div id="tab1" class="weui-tab__bd-item weui-tab__bd-item--active">

                <div class="weui-panel weui-panel_access">
                    <div class="weui-panel__bd" id="tab1_item">
                        <!--1-->
                        <div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;border-top: 5px solid #eee;">
                            <div class="weui-flex__item">订单号：897941361324156456</div>
                        </div>
                        <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg"
                           style="background: #f9f9f9;">
                            <div class="weui-media-box__hd">
                                <img class="weui-media-box__thumb" src="images/swiper-1.jpg">
                            </div>
                            <div class="weui-media-box__bd">
                                <h4 class="weui-media-box__title">笔记本电脑轻薄便携学生15.6英寸上网本手提办公商务吃鸡游戏本</h4>
                                <p class="weui-media-box__desc" style="margin-top: 20px">￥115 <span
                                            STYLE="float: right">X1</span></p>
                            </div>
                        </a>
                        <div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;">
                            <div class="weui-flex__item" style="color: #FF2634">待发货</div>
                            <div class="weui-flex__item" style="text-align: right">合计：<b style="color: #FF2634">￥115</b></div>
                        </div>
                        <!--2-->
                        <div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;border-top: 5px solid #eee;">
                            <div class="weui-flex__item">订单号：897941361324156456</div>
                        </div>
                        <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg"
                           style="background: #f9f9f9;">
                            <div class="weui-media-box__hd">
                                <img class="weui-media-box__thumb" src="images/swiper-1.jpg">
                            </div>
                            <div class="weui-media-box__bd">
                                <h4 class="weui-media-box__title">笔记本电脑轻薄便携学生15.6英寸上网本手提办公商务吃鸡游戏本</h4>
                                <p class="weui-media-box__desc" style="margin-top: 20px">￥115 <span
                                            STYLE="float: right">X1</span></p>
                            </div>
                        </a>
                        <div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;">
                            <div class="weui-flex__item" style="color: #FF2634">待发货</div>
                            <div class="weui-flex__item" style="text-align: right">合计：<b style="color: #FF2634">155</b></div>
                        </div>
                        <!--3-->
                        <div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;border-top: 5px solid #eee;">
                            <div class="weui-flex__item">订单号：897941361324156456</div>
                        </div>
                        <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg"
                           style="background: #f9f9f9;">
                            <div class="weui-media-box__hd">
                                <img class="weui-media-box__thumb" src="images/swiper-1.jpg">
                            </div>
                            <div class="weui-media-box__bd">
                                <h4 class="weui-media-box__title">笔记本电脑轻薄便携学生15.6英寸上网本手提办公商务吃鸡游戏本</h4>
                                <p class="weui-media-box__desc" style="margin-top: 20px">￥115 <span
                                            STYLE="float: right">X1</span></p>
                            </div>
                        </a>
                        <div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;">
                            <div class="weui-flex__item" style="color: #FF2634">待发货</div>
                            <div class="weui-flex__item" style="text-align: right">合计：<b style="color: #FF2634">155</b></div>
                        </div>

                    </div>
                </div>
            </div>
            <div id="tab2" class="weui-tab__bd-item">

                <div class="weui-panel weui-panel_access">
                    <div class="weui-panel__bd" id="tab2_item">
                        <!--1-->
                        <div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;border-top: 5px solid #eee;">
                            <div class="weui-flex__item">订单号：897941361324156456</div>
                        </div>
                        <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg"
                           style="background: #f9f9f9;">
                            <div class="weui-media-box__hd">
                                <img class="weui-media-box__thumb" src="images/swiper-1.jpg">
                            </div>
                            <div class="weui-media-box__bd">
                                <h4 class="weui-media-box__title">笔记本电脑轻薄便携学生15.6英寸上网本手提办公商务吃鸡游戏本</h4>
                                <p class="weui-media-box__desc" style="margin-top: 20px">￥115 <span
                                            STYLE="float: right">X1</span></p>
                            </div>
                        </a>
                        <div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;">
                            <div class="weui-flex__item" style="color: #FF2634">已发货</div>
                            <div class="weui-flex__item" style="text-align: right">合计：<b style="color: #FF2634">155</b></div>
                        </div>
                        <!--2-->
                        <div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;border-top: 5px solid #eee;">
                            <div class="weui-flex__item">订单号：897941361324156456</div>
                        </div>
                        <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg"
                           style="background: #f9f9f9;">
                            <div class="weui-media-box__hd">
                                <img class="weui-media-box__thumb" src="images/swiper-1.jpg">
                            </div>
                            <div class="weui-media-box__bd">
                                <h4 class="weui-media-box__title">笔记本电脑轻薄便携学生15.6英寸上网本手提办公商务吃鸡游戏本</h4>
                                <p class="weui-media-box__desc" style="margin-top: 20px">￥115 <span
                                            STYLE="float: right">X1</span></p>
                            </div>
                        </a>
                        <div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;">
                            <div class="weui-flex__item" style="color: #FF2634">已发货</div>
                            <div class="weui-flex__item" style="text-align: right">合计：<b style="color: #FF2634">155</b></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        var order_list = {
            obj:"",
            url:"",
            // 获取订单
            ajaxGetOrderList:function () {
                $.ajax({
                    url: this.url,
                    type: 'get',
                    dataType: "json",
                    error: function (data) {
                        $.toast("服务器繁忙, 请联系管理员！",'text');
                        return;
                    },
                    success: function (result) {
                       // var html = "";
                    }
                })
            }
        };
        $(function () {
            var url1 = "{{route('ajaxGetOrderList',['is_send'=>0])}}";
            var url2 = "{{route('ajaxGetOrderList',['is_send'=>1])}}";
            order_list.url = url1;
            order_list.obj = $("#tab1_item");
            order_list.ajaxGetOrderList();
            $(".no_is_send").on('click',function () {
                order_list.url = url1;
                order_list.obj = $("#tab1_item");
                order_list.ajaxGetOrderList();
                $("#tab1_item").empty();
            })
            $(".is_send").on('click',function () {
                order_list.url = url2;
                order_list.obj = $("#tab2_item");
                order_list.ajaxGetOrderList();
                $("#tab2_item").empty();
            })
        })
    </script>
    </body>
@endsection