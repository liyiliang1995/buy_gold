@extends('czf.base',['header'=>'编辑地址',
'css' => [
        'https://cdn.bootcss.com/weui/2.0.1/style/weui.min.css',
        'https://cdn.bootcss.com/jquery-weui/1.2.1/css/jquery-weui.min.css',
        'css/jquery-weui.css',
        'css/demos.css'
    ],
'js' => [
        'js/fastclick.js',
        'js/jquery-weui.js',
        'js/city-picker.js'
    ],
'script' => [
        'FastClick.attach(document.body);',
        '$("#start").cityPicker({
            title: "选择地区",
            onChange: function (picker, values, displayValues) {
                console.log(values, displayValues);
            }
        });'
        ]
])
@section('content')
<body>
<!--头部-->
<div class="weui-flex" id="header_top">
    <a href="javascript:history.back(-1)"><img src="/img/fh.png" alt=""></a>
    <div class="weui-flex__item">编辑地址</div>
</div>

<form action="{{ route("postEditAddress",['url'=>request()->input('url') ?? ''])}}" method="post" id="address">
<div class="weui-cells weui-cells_form" style="margin-top: 0">
    {{--<div class="weui-cell">--}}
        {{--<div class="weui-cell__hd"><label class="weui-label">姓名</label></div>--}}
        {{--<div class="weui-cell__bd">--}}
            {{--<input class="weui-input" type="text" name="" placeholder="请输入姓名">--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<div class="weui-cell">--}}
        {{--<div class="weui-cell__hd"><label class="weui-label">电话</label></div>--}}
        {{--<div class="weui-cell__bd">--}}
            {{--<input class="weui-input" type="tel" placeholder="请输入电话">--}}
        {{--</div>--}}
    {{--</div>--}}

    <div class="weui-cell">
        <div class="weui-cell__hd"><label for="name" class="weui-label">地区</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" name="address1" id="start" type="text" value="湖南省 常德市 武陵区">
        </div>
    </div>

    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">详细地址</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" name="address2" type="text" placeholder="请输入地址">
        </div>
    </div>
    @csrf
</div>
    <div class="weui-flex" >
        <div class="weui-flex__item" id="check_but"><a href="javascript:;" id="sb" class="weui-btn weui-btn_primary">保存并使用</a></div>
    </div>
</form>
</body>
<script>
    $(function () {
        @if($errors->has('address1'))
            @foreach($errors->get('address1') as $message)
                $.toast("{{$message}}", 'text');
            @endforeach
        @endif
            @if($errors->has('address2'))
            @foreach($errors->get('address2') as $message)
            $.toast("{{$message}}", 'text');
        @endforeach
        @endif
        $("#sb").on('click', function () {
            var address2 =  $('input[name="address2"]').val().trim();
            if(!address2) {
                $.toast("详细地址不能为空！", 'text');
                return;
            }
            $("#address").submit();
        })
    })
</script>
@endsection