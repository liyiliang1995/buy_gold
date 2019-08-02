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
            line-height: 50px;
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
    <div class="weui-flex" id="article_title">
        <div class="weui-flex__item"><h3>这里是新闻标题很是舒服的新闻标题</h3></div>
    </div>
    <div class="weui-flex" id="article_time">
        <div class="weui-flex__item">2019-08-02 15:30:30</div>
    </div>

    <div class="weui-flex" id="article_main">
        <div class="weui-flex__item">
            潇湘晨报长沙讯 7月下旬来，湖南经历了今年最大范围的持续性高温过程，张家界、石门等11地实现7月下旬的高温“大满贯”。8月1日开始，随着副热带高压东退以及雨水降温，湖南南部高温会逐渐“熄火”，包括长沙在内的湘东北部分地区高温闷热天气仍将延续。监测显示，7月31日8时至8月1日8时，全省最高气温35℃至37℃，共87县市最高气温超过35℃，其中长沙、湘潭等33县市超过37℃，最高出现在株洲，38.5℃。

            省气象台预计，受热带低压云系影响，接下来三天省内多阵性降雨，湘南局地大雨。1日下午到2日白天，益阳、岳阳、长沙、湘潭及株洲北部晴天间多云，其他地区多云有阵雨或雷阵雨，其中永州、郴州部分中雨，局地大雨。2日晚到3日白天，湘中以南多云间阴天有阵雨或雷阵雨，其中湘西南、湘东南南部部分中雨，局地大雨，其他地区多云，部分地区有阵雨或雷阵雨。3日晚到4日白天：湘西、湘南多云间阴天有阵雨或雷阵雨，局地中雨，其他地区多云，部分地区有阵雨或雷阵雨。

            气温方面，8月1日起随着副热带高压东退以及雨水降温作用，湖南南部持续多日的高温将告一段落，而湘东北地区的高温天气仍难消退。8月2日最高气温湘西、湘南32至34℃，其他地区34至36℃。
        </div>
    </div>
    </body>

@endsection
