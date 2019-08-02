@extends('czf.base',['header'=>'通知公告',
'css' => [
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
    <body>
    <!--头部-->
    <div class="weui-flex" id="header_top">
        <a href="javascript:history.back(-1)"><img src="/img/fh.png" alt=""></a>
        <div class="weui-flex__item">通知公告</div>
    </div>


    <div class="weui-flex" style="padding: 0 15px">
        <div class="weui-flex__item" style="line-height: 45px">有关规则</div>
    </div>
    <div class="weui-cells" style="margin-top: 0;">
        <a class="weui-cell weui-cell_access" href="javascript:;">
            <div class="weui-cell__bd">
                <p>金币出售规则</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
        <a class="weui-cell weui-cell_access" href="javascript:;">
            <div class="weui-cell__bd">
                <p>购买商品领取10倍积分</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
    </div>


    <div class="weui-flex" style="padding: 0 15px">
        <div class="weui-flex__item" style="line-height: 45px">有关规则</div>
    </div>
    <div class="weui-cells" style="margin-top: 0;">
        <a class="weui-cell weui-cell_access" href="javascript:;">
            <div class="weui-cell__bd">
                <p>金币出售规则</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
        <a class="weui-cell weui-cell_access" href="javascript:;">
            <div class="weui-cell__bd">
                <p>购买商品领取10倍积分</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
    </div>
    </body>

@endsection
