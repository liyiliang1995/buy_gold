@extends('czf.base',['header'=>'帮助中心',
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
    <style>
        .weui-cell__bd{
            font-size: 14px;
        }
    </style>
    <body>
    <div class="weui-flex" style="padding: 0 15px">
        <div class="weui-flex__item" style="line-height: 45px">有关规则</div>
    </div>
    <div class="weui-cells" style="margin-top: 0;">
        <a class="weui-cell weui-cell_access" href="{{route('article_content','1')}}">
            <div class="weui-cell__bd">
                <p>金币出售规则</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
        <a class="weui-cell weui-cell_access" href="{{route('article_content','1')}}">
            <div class="weui-cell__bd">
                <p>购买商品领取10倍积分</p>
            </div>
            <div class="weui-cell__ft">
            </div>
        </a>
    </div>

    </body>

@endsection
