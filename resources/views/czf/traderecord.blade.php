@extends('czf.base',['header'=>'交易记录',
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
        "FastClick.attach(document.body);",
        '$("#trading_a").bind("click",function () {
            $.toast("撤单成功");
        });',
        '$("#trading_b").bind("click",function () {
            $.toast("收款成功");
        });',
        '$(".infinite").infinite().on("infinite", function() {
        var self = this;
        if(self.loading) return;
        console.log(self)
        self.loading = true;
        console.log(self);
        setTimeout(function() {
          $(self).find(".content-padded").append("<p>我是加载的新内容。我是加载的新内容。。。我是加载的新内容。。。我是加载的新内容。。。我是加载的新内容。。。我是加载的新内容。。。我是加载的新内容。。。我是加载的新内容。。。我是加载的新内容。。。。。</p>");
          self.loading = false;
        }, 2000);   //模拟延迟
      });'
    ]
])
@section('content')
    <body ontouchstart>
    <div class="weui-flex" id="header_top">
        <a href="javascript:history.back(-1)"><img src="/img/fh.png" alt=""></a>
        <div class="weui-flex__item">交易记录</div>
    </div>


    <div class="weui-tab">
        <div class="weui-navbar">
            <div class="weui-navbar__item weui-navbar__item--on no_is_send" href="#tab1">
                求购记录
            </div>
            <div class="weui-navbar__item is_send" href="#tab2">
                出售记录
            </div>
        </div>

        <div class="weui-tab__bd">
            <div id="tab1" class="weui-tab__bd-item weui-tab__bd-item--active infinite">
                <div class="content-padded" id="tab1_item">
                    <!--1-->
                        <div class="cont_list" style="background: #fff">
                            <div class="weui-flex"
                                 style="  font-size: 12px;  padding: 5px 15px;border-top: 5px solid #eee;border-bottom: 2px solid #eee;">
                                <div class="weui-flex__item">2019-07-19 10:44</div>
                                <div class="weui-flex__item" style="text-align: right;color: red">求购中</div>
                            </div>
                            <div class="weui-flex" style=" padding: 5px 15px; ">
                                <div class="weui-flex__item">金币数量</div>
                                <div class="weui-flex__item" style="text-align: right">200</div>
                            </div>
                            <div class="weui-flex" style=" padding: 5px 15px; ">
                                <div class="weui-flex__item">总金额</div>
                                <div class="weui-flex__item" style="text-align: right;color: red">+160</div>
                            </div>
                            <div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;">
                                <div class="weui-flex__item" style="color: red"><a href="buy.html" style="color: #333">查看详情>></a>
                                </div>
                                <div class="weui-flex__item" style="text-align: right"><a id="trading_a" href="javascript:;"
                                                                                          class="weui-btn weui-btn_primary">申请撤单</a>
                                </div>
                            </div>
                        </div>
                    {{--end--}}

                </div>
                <div class="weui-loadmore">
                    <i class="weui-loading"></i>
                    <span class="weui-loadmore__tips">正在加载</span>
                </div>
            </div>
            <div id="tab2" class="weui-tab__bd-item infinite">
                <div class="content-padded" id="tab2_item">

                    <!--1-->
                    <div class="cont_list" style="background: #fff">
                        <div class="weui-flex"
                             style="  font-size: 12px;  padding: 5px 15px;border-top: 5px solid #eee;border-bottom: 2px solid #eee;">
                            <div class="weui-flex__item">2019-07-19 10:44</div>
                            <div class="weui-flex__item" style="text-align: right;color: red">未收款</div>
                        </div>
                        <div class="weui-flex" style=" padding: 5px 15px; ">
                            <div class="weui-flex__item">金币数量</div>
                            <div class="weui-flex__item" style="text-align: right">200</div>
                        </div>
                        <div class="weui-flex" style=" padding: 5px 15px; ">
                            <div class="weui-flex__item">总金额</div>
                            <div class="weui-flex__item" style="text-align: right;color: #4CAF50">+160</div>
                        </div>
                        <div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;">
                            <div class="weui-flex__item" style="color: red"><a href=""
                                                                               style="color: #333">查看详情>></a></div>
                            <div class="weui-flex__item" style="text-align: right"><a id="trading_b"
                                                                                      href="javascript:;"
                                                                                      class="weui-btn weui-btn_primary">确认收款</a>
                                </div>
                    </div>
                </div>
                    {{--end--}}

                </div>
                <div class="weui-loadmore">
                    <i class="weui-loading"></i>
                    <span class="weui-loadmore__tips">正在加载</span>
                </div>
            </div>

    </div>
    </div>

    <script>
        var trade_record = {
            obj:"",
            url:"",
            // 获取订单
            ajaxGetGoldFlow:function () {
                if (!trade_record.url) {
                    $.toast("没有更多数据加载！",'text');
                    $('.weui-loadmore').hide();
                    return;
                }
                $.ajax({
                    url: trade_record.url,
                    type: 'get',
                    dataType: "json",
                    error: function (data) {
                        $.toast("服务器繁忙, 请联系管理员！",'text');
                        return;
                    },
                    success: function (result) {
                        console.log(result);
                        var html = '';
                        $.each(result.data.data,function (index,val) {
                            html += '<div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;border-top: 5px solid #eee;">';
                        //     html += '<div class="weui-flex__item">订单号：'+val.order_no+'</div>';
                        //     html += '</div>';
                        //     html += '<a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg" style="background: #f9f9f9;">';
                        //     html += '<div class="weui-media-box__hd"><img class="weui-media-box__thumb" src="'+val.goods_img+'"></div>';
                        //     html += '<div class="weui-media-box__bd"><h4 class="weui-media-box__title">'+val.goods.name+'</h4>';
                        //     html += '<p class="weui-media-box__desc" style="margin-top: 20px">￥'+val.goods.amount+'<span style="float: right">';
                        //     html += 'X'+val.num+'</span></p>';
                        //     html += '</div></a>';
                        //     html += '<div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;">';
                        //     html += '<div class="weui-flex__item" style="color: #FF2634">'+val.is_send_str+'</div>';
                        //     html += '<div class="weui-flex__item" style="text-align: right">合计：<b style="color: #FF2634">'+val.sum_price+'</b></div></div>'
                        });
                        // trade_record.url = result.data.next_page_url;
                        // trade_record.obj.append(html);
                    }
                })
            }
        };
        $(function () {
            var url1 = "{{route('ajaxGetGoldFlow',['type'=>3])}}";
            var url2 = "{{route('ajaxGetGoldFlow',['type'=>2])}}";
            trade_record.url = url1;
            trade_record.obj = $("#tab1_item");
            trade_record.ajaxGetGoldFlow();
            $(".no_is_send").on('click',function () {
                trade_record.url = url1;
                trade_record.obj = $("#tab1_item");
                trade_record.ajaxGetGoldFlow();
                $("#tab1_item").empty();
            })
            $(".is_send").on('click',function () {
                trade_record.url = url2;
                trade_record.obj = $("#tab2_item");
                trade_record.ajaxGetGoldFlow();
                $("#tab2_item").empty();
            })
            $(".infinite").infinite().on("infinite", function() {
                var self = this;
                if(self.loading) return;
                self.loading = true;
                setTimeout(function() {
                    trade_record.ajaxGetGoldFlow();
                }, 500);   //模拟延迟
            })
        })
    </script>

    </body>
@endsection
