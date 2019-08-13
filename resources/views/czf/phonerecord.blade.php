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
    <style>
        .weui-flex__item{
            color: #666;
            font-size: 14px;

        }
    </style>
    <body ontouchstart>
    <!--头部-->
    <div class="weui-flex" id="header_top">
        <a href="{{route('member_index')}}"><img src="{{route('home')}}/img/fh.png" alt=""></a>
        <div class="weui-flex__item">充值记录</div>
    </div>

    <div class="weui-tab">
        <div class="weui-navbar">
            <div class="weui-navbar__item weui-navbar__item--on no_is_send" href="#tab1">
                抢单记录
            </div>
            <div class="weui-navbar__item is_send" href="#tab2">
                挂单记录
            </div>
        </div>

        <div class="weui-tab__bd">
            <div id="tab1" class="weui-tab__bd-item weui-tab__bd-item--active infinite">
                <div class="content-padded" id="tab1_item">

                </div>
                <div class="weui-loadmore">
                    <i class="weui-loading"></i>
                    <span class="weui-loadmore__tips">正在加载</span>
                </div>
            </div>
            <div id="tab2" class="weui-tab__bd-item infinite">
                <div class="content-padded" id="tab2_item">



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
            ajaxGetBuyGoldType:function () {
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
                        if (result.data.data != null){
                            $('.weui-loadmore').hide();
                        }
                        // 3:求购 2:出售
                        var html = '';
                        if(result.data.type == 1){
                            $.each(result.data.data,function (index,val) {
                                html += ' <div class="cont_list" style="background: #fff">';
                                html +='<div class="weui-flex"style="  font-size: 12px;  padding: 5px 15px;border-top: 5px solid #eee;border-bottom: 2px solid #eee;">';
                                html +='<div class="weui-flex__item">'+val.created_at+'</div>';
                                html +='<div class="weui-flex__item" style="text-align: right;color: red">'+val.buy_gold_status+'</div></div>';
                                html +='<div class="weui-flex" style=" padding: 5px 15px; ">';
                                html +='<div class="weui-flex__item">充值金额</div>';
                                html +='<div class="weui-flex__item" style="text-align: right">'+val.gold+'</div></div>';
                                html +='<div class="weui-flex" style=" padding: 5px 15px; ">';
                                html +='<div class="weui-flex__item">金币总数</div>';
                                html +='<div class="weui-flex__item" style="text-align: right;color: red">'+val.sum_price+'</div></div>';
                                html +='<div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;">';
                                html +='<div class="weui-flex__item" style="color: red"><a href="'+val.detail_url+'" style="color: #333">查看详情>></a></div>';
                                html +='<div class="weui-flex__item" style="text-align: right">';
                              if(val.buy_gold_status == '交易中'){
                                    html +='<a id="trading_a" style="color: #666;border: #eee;"  class="weui-btn weui-btn_primary" disabled>等待确认</a>';
                                }
                                html +='</div></div></div>';
                            });
                        }else{
                            $.each(result.data.data,function (index,val) {
                                html += ' <div class="cont_list" style="background: #fff">';
                                html +='<div class="weui-flex"style="  font-size: 12px;  padding: 5px 15px;border-top: 5px solid #eee;border-bottom: 2px solid #eee;">';
                                html +='<div class="weui-flex__item">'+val.created_at+'</div>';
                                html +='<div class="weui-flex__item" style="text-align: right;color: red">'+val.give_status+'</div></div>';
                                html +='<div class="weui-flex" style=" padding: 5px 15px; ">';
                                html +='<div class="weui-flex__item">充值金额</div>';
                                html +='<div class="weui-flex__item" style="text-align: right">'+val.gold+'</div></div>';
                                html +='<div class="weui-flex" style=" padding: 5px 15px; ">';
                                html +='<div class="weui-flex__item">支付金币</div>';
                                html +='<div class="weui-flex__item" style="text-align: right;color: red">'+val.sum_price+'</div></div>';
                                html +='<div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;">';
                                html +='<div class="weui-flex__item" style="color: red"><a href="'+val.detail_url+'" style="color: #333">查看详情>></a></div>';
                                html +='<div class="weui-flex__item" style="text-align: right">';
                                if (val.give_status == '未收款'){
                                    html +='<a id="trading_b" href="'+val.confirm_url+'" class="weui-btn weui-btn_primary">确认收款</a>';
                                }
                                html +='</div></div></div>';
                            });



                        }

                        trade_record.url = result.data.next_page_url;
                        trade_record.obj.append(html);
                    }
                })
            }
        };
        $(function () {
            @php
                $errors = session()->get('lock');
            @endphp
            @if($errors)
            $.toast("{{$errors}}", 'text');
                @endif
            var show = "{{request()->input("show") ?? ''}}";
            var url1 = "{{route('ajaxGetBuyGoldType',['type'=>1])}}";
            var url2 = "{{route('ajaxGetBuyGoldType',['type'=>2])}}";


            $(".no_is_send").on('click',function () {
                trade_record.url = url1;
                trade_record.obj = $("#tab1_item");
                trade_record.ajaxGetBuyGoldType();
                $("#tab1_item").empty();
            })
            $(".is_send").on('click',function () {
                trade_record.url = url2;
                trade_record.obj = $("#tab2_item");
                trade_record.ajaxGetBuyGoldType();
                $("#tab2_item").empty();
            })
            $(".infinite").infinite().on("infinite", function() {
                var self = this;
                if(self.loading) return;
                self.loading = true;
                setTimeout(function() {
                    trade_record.ajaxGetBuyGoldType();
                }, 500);   //模拟延迟
            })
            if (show.trim() == '2') {
                $(".is_send").trigger('click');
            } else {
                $(".no_is_send").trigger('click');
            }
        })
    </script>

    </body>
@endsection
