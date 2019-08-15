@extends('czf.base',['header'=>'翠竹坊',
'css' => [
        'http://at.alicdn.com/t/font_1300674_bwcd8riknaj.css',
        'css/weui.min.css',
        'css/jquery-weui.min.css',
        'css/swiper.min.css',
        'css/style.css'
    ],
'js' => [
        'js/jquery-weui.min.js',
        'js/swiper.min.js',
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
        });",
        "
         $(function(){
            if({$member_status}== 2 || {$member_status} == 3){
            $.toast('账户冻结中','text');
            }
         });
        ",
    ]
])
@section('content')
    <style>
        .title {
            font-size: 14px;
        }
    </style>
    <body class="index-page">
    <div class="weui-flex">
        <!-- Swiper -->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="swiper-zoom-container">
                        <img src="{{$aConfig['banner'] ?? ''}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="weui-flex notice">

        <div class="weui-cell__bd" style="height: 30px;line-height: 30px">
            <label class="weui-label" style="width: 100%" style="height: 30px;line-height: 30px">
                <marquee direction="left" style="height: 30px;line-height: 30px;width: 60%;">{{$newslist['0']['title'] ?? ''}}</marquee>
                <p style="float: right;width: 40%"> 会员总数 <span style="color: red;">{{$member_count}}</span></p>
            </label>

        </div>
    </div>
    <div class="weui-flex">
        <div class="weui-row">
            @foreach($aGoods as $value)
                <div class="weui-col-50">
                    <a href="{{route('goodsDetail',['id'=>$value['id']])}}" style="color: #666;border: none;">
                        <div class="weui-col__hd"><img src="{{czf_asset($value['list_img'])}}" width="100%"></div>
                        <div class="weui-col__bd" style="height: 45px;overflow: hidden;">
                            <p class="title">{{$value['name']}}</p>
                        </div>
                    </a>
                    <div class="weui-col__ft">
                        <div class="weui-cell">
                            <div class="weui-cell__hd">
                                <em>¥</em>
                                <span>{{$value['amount']}}</span>
                            </div>
                            <a href="{{route('goodsDetail',['id'=>$value['id']])}}"
                               style="width: 80px !important;    width: 80px !important; height: 30px !important;  line-height: 30px;font-size: 14px;"
                               class="weui-cell__bd weui-btn weui-btn_plain-primary">购买</a>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    </div>
    <div class="weui-flex" style="margin-top: 20px;">
        <div class="weui-flex__item"><img src="{{route('home')}}/images/indexbg.png" alt=""
                                          style="width: 100%;display: block;margin: 0 auto;"></div>
    </div>
    <div class="weui-flex" style="margin-top: 20px;">
        <div class="weui-flex__item"><img src="{{route('home')}}/images/footer.png" alt=""
                                          style="width: 90%;display: block;margin: 0 auto;"></div>
    </div>
    <!-- footer -->
    <div class="weui-footer_fixed-bottom" style="bottom: 0;">

        <div class="weui-tabbar">
            <a href="{{route('home')}}" class="weui-tabbar__item weui-bar__item--on">
                <div class="weui-tabbar__icon">
                    <i class="iconfont icon-home-fill"></i>
                </div>
                <p class="weui-tabbar__label">首页</p>
            </a>
            <a href="{{route('member_index')}}" class="weui-tabbar__item">
                <div class="weui-tabbar__icon">
                    <i class="iconfont icon-user-fill"></i>
                </div>
                <p class="weui-tabbar__label">个人中心</p>
            </a>
        </div>
    </div>
    </body>
    {{--<script>--}}
        {{--$(function () {--}}

            {{--if(!navigator.onLine){--}}
                {{--e.stopPropagation();--}}
                {{--$.toast('网络未链接，请重试', "text");--}}
                {{--return;--}}
            {{--}--}}
        {{--});--}}
    {{--</script>--}}
@endsection
