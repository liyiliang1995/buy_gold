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
        <a href="javascript:history.back(-1)"><img src="/img/fh.png" alt=""></a>
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
            <div id="tab1" class="weui-tab__bd-item weui-tab__bd-item--active infinite">

                <div class="weui-panel weui-panel_access">
                    <div class="weui-panel__bd content-padded" id="tab1_item">
                        <!--1-->


                    </div>
                </div>
                <div class="weui-loadmore">
                    <i class="weui-loading"></i>
                    <span class="weui-loadmore__tips">正在加载</span>
                </div>
            </div>
            <div id="tab2" class="weui-tab__bd-item infinite">

                <div class="weui-panel weui-panel_access">
                    <div class="weui-panel__bd content-padded" id="tab2_item">

                    </div>
                </div>
                <div class="weui-loadmore">
                    <i class="weui-loading"></i>
                    <span class="weui-loadmore__tips">正在加载</span>
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
                if (!order_list.url) {
                    $.toast("没有更多数据加载！",'text');
                    $('.weui-loadmore').hide();
                    return;
                }
                $.ajax({
                    url: order_list.url,
                    type: 'get',
                    dataType: "json",
                    error: function (data) {
                        $.toast("服务器繁忙, 请联系管理员！",'text');
                        return;
                    },
                    success: function (result) {
                        var html = '';
                        $.each(result.data.data,function (index,val) {
                            html += '<div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;border-top: 5px solid #eee;">';
                            html += '<div class="weui-flex__item">订单号：'+val.order_no+'</div>';
                            html += '</div>';
                            html += '<a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg" style="background: #f9f9f9;">';
                            html += '<div class="weui-media-box__hd"><img class="weui-media-box__thumb" src="'+val.goods_img+'"></div>';
                            html += '<div class="weui-media-box__bd"><h4 class="weui-media-box__title">'+val.goods.name+'</h4>';
                            html += '<p class="weui-media-box__desc" style="margin-top: 20px">￥'+val.goods.amount+'<span style="float: right">';
                            html += 'X'+val.num+'</span></p>';
                            html += '</div></a>';
                            html += '<div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;">';
                            html += '<div class="weui-flex__item" style="color: #FF2634">'+val.is_send_str+'</div>';
                            html += '<div class="weui-flex__item" style="text-align: right">合计：<b style="color: #FF2634">'+val.sum_price+'</b></div></div>'
                        });
                        order_list.url = result.data.next_page_url;
                        order_list.obj.append(html);
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
            $(".infinite").infinite().on("infinite", function() {
                var self = this;
                if(self.loading) return;
                self.loading = true;
                setTimeout(function() {
                    order_list.ajaxGetOrderList();
                }, 500);   //模拟延迟
            })
        })
    </script>
    </body>
@endsection