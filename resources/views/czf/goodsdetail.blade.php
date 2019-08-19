@extends('czf.base',['header'=>'商品详情 - 翠竹坊',
'css' => [
        'http://at.alicdn.com/t/font_1300674_bwcd8riknaj.css',
        'css/weui.min.css',
        'css/jquery-weui.min.css',
        'css/swiper.min.css',
        'css/style.css',
        'css/demos.css'
    ],
'js' => [
        'js/jquery-weui.min.js',
    //    'js/swiper.min.js',
        'js/city-picker.min.js'
    ],
'script' => [
        "var swiper = new Swiper('.swiper-container', {
            zoom: false,
            pagination: {
                el: '.swiper-pagination',
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });"
    ]
])
@section('content')
    <style>
        #goods_detail_but a{
            height: 50px !important;
            line-height: 50px !important;
        }
        .weui-panel__hd{
            font-size: 16px !important;
        }
    </style>
    <body class="details-page">
    <!--头部-->
    <script src="/js/swiper.min.js"></script>
    <div class="weui-flex" id="header_top">
        <a href="javascript:history.back(-1)"><img src="{{route('home')}}/img/fh.png" alt=""></a>
        <div class="weui-flex__item">商品详情</div>
    </div>

    <!-- Swiper -->
    <div class="swiper-container">
        <div class="swiper-wrapper">
            @foreach($oGoods->goodsimgs as $item)
            <div class="swiper-slide">
                <div class="swiper-zoom-container">
                    <img src="{{czf_asset($item['img'])}}">
                </div>
            </div>
            @endforeach

        </div>
        <!-- Add Pagination -->
        {{--<div class="swiper-pagination swiper-pagination-white"></div>--}}
    </div>
    <div class="weui-panel weui-panel_access goods-info">
        <div class="weui-panel__bd">
            <div class="weui-media-box__bd">
                <h4 class="weui-media-box__title">{{$oGoods->name}}</h4>
                <p class="price-info" style="">
                    <em>¥</em>
                    <span>{{$oGoods->amount}}</span>
                </p>
            </div>
        </div>
    </div>
    <!-- Details -->
    <div class="weui-panel weui-panel_access">
        <div class="weui-panel__hd">商品详情</div>
        <div class="weui-panel__bd good-detail">
            {!!  $oGoods->describe !!}
        </div>
        <div class="weui-panel__ft">
        </div>
    </div>

    <!-- footer -->
    <div class="weui-footer_fixed-bottom">

        <div class="weui-tabbar" id="goods_detail_but">
            <a href="{{route('confirmOrder',['goodsId'=>$oGoods->id])}}" class="weui-btn">
                立即购买
            </a>
        </div>
    </div>
    <script type="text/javascript">
        var mySwiper  = new Swiper(".swiper-container",{

            autoplay:true//设置自动循环播放

        })
    </script>

    </body>
@endsection
