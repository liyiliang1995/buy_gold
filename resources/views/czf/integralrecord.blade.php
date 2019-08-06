@extends('czf.base',['header'=>'积分明细',
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
    ],
])
@section('content')
    <body ontouchstart style="background: #fff;">
    <div class="weui-tab">
        <div class="weui-navbar">
            <a class="weui-navbar__item weui-bar__item--on no_is_send" href="#tab1">
                收入明细
            </a>
            <a class="weui-navbar__item is_send" href="#tab2">
                支出明细
            </a>
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
        var integral_record = {
            obj:"",
            url:"",
            // 获取订单
            ajaxGetIntegralFlow:function () {
                if (!integral_record.url) {
                    $.toast("没有更多数据加载！",'text');
                    $('.weui-loadmore').hide();
                    return;
                }
                $.ajax({
                    url: integral_record.url,
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
                        // 1收入 2 支出
                        var html = '';
                        $.each(result.data.data,function (index,val) {
                            html +='<div class="weui-flex" id="integral"><div class="weui-flex__item">';
                            html +='<p style="font-weight: bold;font-size: 14px;color: #666">'+val.show_type+'</p>';
                            html +='<p style="font-size: 12px;color: #999">'+ val.created_at +'</p></div>';
                            html +='<div class="weui-flex__item" style="color: red;text-align: right">'+val.integral+'</div></div>';
                        });

                        integral_record.url = result.data.next_page_url;
                        integral_record.obj.append(html);
                    }
                })
            }
        };
        $(function () {
            var url1 = "{{route('ajaxGetIntegralFlow',['type'=>1])}}";
            var url2 = "{{route('ajaxGetIntegralFlow',['type'=>2])}}";
            integral_record.url = url1;
            integral_record.obj = $("#tab1_item");
            integral_record.ajaxGetIntegralFlow();
            $(".no_is_send").on('click',function () {
                integral_record.url = url1;
                integral_record.obj = $("#tab1_item");
                integral_record.ajaxGetIntegralFlow();
                $("#tab1_item").empty();
            })
            $(".is_send").on('click',function () {
                integral_record.url = url2;
                integral_record.obj = $("#tab2_item");
                integral_record.ajaxGetIntegralFlow();
                $("#tab2_item").empty();
            })
            $(".infinite").infinite().on("infinite", function() {
                var self = this;
                if(self.loading) return;
                self.loading = true;
                setTimeout(function() {
                    integral_record.ajaxGetIntegralFlow();
                }, 500);   //模拟延迟
            })
        })
    </script>





    </body>
@endsection

