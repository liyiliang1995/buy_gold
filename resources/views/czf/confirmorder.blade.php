@extends('czf.base',['header'=>'订单提交 - 翠竹坊',
'css' => [
        'http://at.alicdn.com/t/font_1300674_bwcd8riknaj.css',
        'https://cdn.bootcss.com/weui/2.0.1/style/weui.min.css',
        'https://cdn.bootcss.com/jquery-weui/1.2.1/css/jquery-weui.min.css',
        'css/swiper.min.css',
        'css/style.css'
    ],
'js' => [
        'https://cdn.bootcss.com/jquery-weui/1.2.1/js/jquery-weui.min.js',
        'js/swiper.min.js',
        'https://cdn.bootcss.com/jquery-weui/1.2.1/js/city-picker.min.js'
    ],
'script' => [
        'add_delete();',
        'function add_delete () {
            var MAX = 99, MIN = 1;
            $(".weui-count__decrease").click(function (e) {
                var $input = $(e.currentTarget).parent().find(".weui-count__number");
                console.log($input.val());
                var number = parseInt($input.val() || "0") - 1
                if (number < MIN) number = MIN;
                $input.val(number);
                order.goods_unit_price=$("#goods_unit_price").html().trim();
                order.goods_num=number;
                order.show_price();
            });
            $(".weui-count__increase").click(function (e) {
                var $input = $(e.currentTarget).parent().find(".weui-count__number");
                var number = parseInt($input.val() || "0") + 1
                if (number > MAX) number = MAX;
                $input.val(number);
                order.goods_unit_price=$("#goods_unit_price").html().trim();
                order.goods_num=number;
                order.show_price();
            });
        }'
    ],
])
@section('content')
    <body class="confirm-order">
    <div class="weui-panel order-address">
        <div class="weui-panel__bd">
            <div class="weui-media-box weui-media-box_small-appmsg">
                <div class="weui-cells">
                    <a class="weui-cell weui-cell_access" href="{{route('getEditAddress',['url'=>url()->full()])}}">
                        <div class="weui-cell__hd">
                            <i class="iconfont icon-location"></i>
                        </div>
                        <div class="weui-cell__bd weui-cell_primary">
                            <div class="weui-flex">
                                <div class="weui-flex__item contact_name">收货人：{{$oUser->ship_address->name ?? ''}}</div>
                                <div class="weui-flex__item contact_tel">{{$oUser->ship_address->phone ?? ''}}</div>
                            </div>
                            <p class="contact_address">收货地址：{{$oUser->ship_address->ship_address ?? ''}}</p>
                        </div>
                        <span class="weui-cell__ft"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="weui-panel order-info weui-panel_access">
        <div class="weui-panel__bd">
            <div class="weui-row">
                <div class="weui-col-33">
                    <img class="weui-media-box__thumb" width="100%" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB4CAMAAAAOusbgAAAAeFBMVEUAwAD///+U5ZTc9twOww7G8MYwzDCH4YcfyR9x23Hw+/DY9dhm2WZG0kbT9NP0/PTL8sux7LFe115T1VM+zz7i+OIXxhes6qxr2mvA8MCe6J6M4oz6/frr+us5zjn2/fa67rqB4IF13XWn6ad83nxa1loqyirn+eccHxx4AAAC/klEQVRo3u2W2ZKiQBBF8wpCNSCyLwri7v//4bRIFVXoTBBB+DAReV5sG6lTXDITiGEYhmEYhmEYhmEYhmEY5v9i5fsZGRx9PyGDne8f6K9cfd+mKXe1yNG/0CcqYE86AkBMBh66f20deBc7wA/1WFiTwvSEpBMA2JJOBsSLxe/4QEEaJRrASP8EVF8Q74GbmevKg0saa0B8QbwBdjRyADYxIhqxAZ++IKYtciPXLQVG+imw+oo4Bu56rjEJ4GYsvPmKOAB+xlz7L5aevqUXuePWVhvWJ4eWiwUQ67mK51qPj4dFDMlRLBZTqF3SDvmr4BwtkECu5gHWPkmDfQh02WLxXuvbvC8ku8F57GsI5e0CmUwLz1kq3kD17R1In5816rGvQ5VMk5FEtIiWislTffuDpl/k/PzscdQsv8r9qWq4LRWX6tQYtTxvI3XyrwdyQxChXioOngH3dLgOFjk0all56XRi/wDFQrGQU3Os5t0wJu1GNtNKHdPqYaGYQuRDfbfDf26AGLYSyGS3ZAK4S8XuoAlxGSdYMKwqZKM9XJMtyqXi7HX/CiAZS6d8bSVUz5J36mEMFDTlAFQzxOT1dzLRljjB6+++ejFqka+mXIe6F59mw22OuOw1F4T6lg/9VjL1rLDoI9Xzl1MSYDNHnPQnt3D1EE7PrXjye/3pVpr1Z45hMUdcACc5NVQI0bOdS1WA0wuz73e7/5TNqBPhQXPEFGJNV2zNqWI7QKBd2Gn6AiBko02zuAOXeWIXjV0jNqdKegaE/kJQ6Bfs4aju04lMLkA2T5wBSYPKDGF3RKhFYEa6A1L1LG2yacmsaZ6YPOSAMKNsO+N5dNTfkc5Aqe26uxHpx7ZirvgCwJpWq/lmX1hA7LyabQ34tt5RiJKXSwQ+0KU0V5xg+hZrd4Bn1n4EID+WkQdgLfRNtvil9SPfwy+WQ7PFBWQz6dGWZBLkeJFXZGCfLUjCgGgqXo5TuSu3cugdcTv/HjqnBTEMwzAMwzAMwzAMwzAMw/zf/AFbXiOA6frlMAAAAABJRU5ErkJggg==" alt="">
                </div>
                <div class="weui-col-66">
                    <h4 class="weui-media-box__title">{{$oGoods->name}}</h4>
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <em style="">¥</em>
                            <span style="" id="goods_unit_price">{{$oGoods->amount}}</span>
                        </div>
                        <div class="weui-cell__ft">
                            <div class="weui-count">
                                <a class="weui-count__btn weui-count__decrease"></a>
                                <input class="weui-count__number" type="number" id="goods_num" value="1" />
                                <a class="weui-count__btn weui-count__increase"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="weui-panel__ft">
            <a href="javascript:void(0);" class="weui-cell weui-cell_access weui-cell_link">
                <div class="weui-cell__bd" style="color: #333333">配送方式</div>
                <span class="weui-cell__ft">快递</span>
            </a>
        </div>
    </div>
    <div class="weui-cells__title message">买家留言</div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <textarea class="weui-textarea" placeholder="请输入文本" id="other" rows="3"></textarea>
                <div class="weui-textarea-counter"><span>0</span>/200</div>
            </div>
        </div>
    </div>
    <!-- footer -->
    <div class="weui-footer_fixed-bottom">
        <div class="weui-tabbar">
            <a href="javascript:;" class="weui-tabbar__item">
                @php
                    $pay_gold = pay_gold($hour_avg_price,$oGoods->amount);
                @endphp
                <p class="weui-tabbar__label" id="show_price">合计:<span>{{$oGoods->amount}}元</span>（{{sum_gold($pay_gold,burn_gold($pay_gold))}}金币）</p>
            </a>
            <a href="javascript:void(0)" id="sbm" class="weui-tabbar__item weui-bar__item--on">
                <p class="weui-tabbar__label">提交订单</p>
            </a>
        </div>
    </div>
    </body>
    <script>
        var order = {
            gold_price:"{{$hour_avg_price}}",
            goods_unit_price:$("#goods_unit_price").html().trim(),
            goods_num:$("#goods_num").val().trim(),
            other:$("#other").val().trim(),
            // 保留两位数
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
            show_price:function () {
                var price = this.intToFloat(parseFloat(this.goods_unit_price) * parseFloat(this.goods_num));
                var gold = this.intToFloat(parseFloat(price)/parseFloat(this.gold_price));
                var burn_gold = this.intToFloat(parseFloat(gold)*0.05);
                var sum_gold = this.intToFloat(parseFloat(gold)+parseFloat(burn_gold));
                $("#show_price").empty().html("合计:<span>"+price+"元</span>("+sum_gold+")金币");
            },
            ajax_submit:function () {
                $.ajax({
                    url: "{{route('order_save',['goodsId'=>$oGoods->id])}}",
                    type:'post',
                    dataType: "json",
                    data:{ num:this.goods_num,glod_price:this.gold_price,other:this.other,_method:'post' ,_token:"{{csrf_token()}}"},
                    error:function(data){
                        $.toast("服务器繁忙, 请联系管理员！",'text');
                        return;
                    },
                    success:function(result){
                        if(result.code == 200){
                            window.location.href = result.data.url;
                        } else {
                            $.toast(result.message,'text')
                        }
                    },
                })
            }
        }
        $(function () {
            $("#sbm").on('click',function () {
                order.goods_num = $("#goods_num").val().trim();
                order.other = $("#other").val().trim();
                order.ajax_submit()
            })
        })
    </script>
@endsection