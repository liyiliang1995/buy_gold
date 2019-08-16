@extends('czf.base',['header'=>'文章详情',
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
        body{
            background: #fff;
        }
        #article_title{
            text-align: center;
            /*line-height: 50px;*/
            width: 90%;
            margin:0 auto;
            padding-top: 20px;
        }
        #article_time{
            font-size: 12px;
            text-align: center;
            color: #666;
            line-height: 30px;
            border-bottom: 1px solid #eee;
        }
        #article_main{
            width: 92%;
            margin: 0 auto;
            color: #666;
            text-indent:2em;
            padding: 15px 0;
            text-align: justify;
        }
    </style>
    <body>
    <!--头部-->
    <div class="weui-flex" id="header_top">
        <a href="javascript:history.back(-1)"><img src="{{route('home')}}/img/fh.png" alt=""></a>
        <div class="weui-flex__item">文章详情</div>
    </div>

    <div class="weui-flex" id="article_title">
        <div class="weui-flex__item"><h3>{{$newscontent->title ?? ''}}</h3></div>
    </div>
    <div class="weui-flex" id="article_time">
        <div class="weui-flex__item">{{$newscontent->created_at}}</div>
    </div>

    <div class="weui-flex" id="article_main">
        <div class="weui-flex__item">{{$newscontent->content}}</div>
    </div>
    </body>

@endsection
