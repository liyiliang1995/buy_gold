@extends('czf.base',['header'=>'用户 - 完善信息',
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
    <body class="index-page">
    <div class="weui-flex">
        <!-- Swiper -->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="swiper-zoom-container">
                        <img src="{{$aConfig['banner']}}">
                    </div>
                </div>
            </div>
            <!-- Add Pagination -->
            <!--<div class="swiper-pagination swiper-pagination-white"></div>-->
            <!-- Add Navigation -->
            <!--<div class="swiper-button-prev"></div>-->
            <!--<div class="swiper-button-next"></div>-->
        </div>
    </div>
    <div class="weui-flex notice">
        <div class="weui-cell__hd">
            <i></i>
        </div>
        <div class="weui-cell__bd">
            <label class="weui-label">测试通知信息</label>
        </div>
    </div>
    <div class="weui-flex">
        <div class="weui-row">
           @foreach($aGoods as $value)
            <div class="weui-col-50">
                <div class="weui-col__hd"><img src="{{$value['list_img']}}" width="100%"></div>
                <div class="weui-col__bd">
                    <p class="title">{{$value['name']}}</p>
                </div>
                <div class="weui-col__ft">
                    <div class="weui-cell">
                        <div class="weui-cell__hd">
                            <em>¥</em>
                            <span>{{$value['amount']}}</span>
                        </div>
                        <a href="javascript:;" class="weui-cell__bd weui-btn weui-btn_plain-primary">购买</a>
                    </div>
                </div>
            </div>
           @endforeach
        </div>
    </div>


    <!-- footer -->
    <div class="weui-footer_fixed-bottom" style="bottom: 0;">

        <div class="weui-tabbar">
            <a href="javascript:;" class="weui-tabbar__item weui-bar__item--on">
                <div class="weui-tabbar__icon">
                    <i class="iconfont icon-home-fill"></i>
                </div>
                <p class="weui-tabbar__label">首页</p>
            </a>
            <a href="javascript:;" class="weui-tabbar__item">
                <div class="weui-tabbar__icon">
                    <i class="iconfont icon-user-fill"></i>
                </div>
                <p class="weui-tabbar__label">个人中心</p>
            </a>
        </div>
    </div>
    </body>
@endsection
