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
    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">收货人</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" type="text" name="name" value="{{$oUser->ship_address->name ?? $oUser->name}}" placeholder="请输入收货人姓名">
        </div>
    </div>

    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">联系电话</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" type="number" name="phone" value="{{$oUser->ship_address->phone ?? $oUser->phone}}" placeholder="请输入联系电话">
        </div>
    </div>

    <div class="weui-cell">
        <div class="weui-cell__hd"><label for="name" class="weui-label">地区</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" name="address1" id="start" type="text" value="湖南省 常德市 武陵区">
        </div>
    </div>

    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">详细地址</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" name="address2" type="text" value="{{explode(" | ",$oUser->ship_address->ship_address ?? '' )[1] ?? ''}}" placeholder="请输入地址">
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
    var check = {
        address_value:'',
        phone_value:'',
        name_value:'',
        phone:function () {
            var pattern = /^1[349578]\d{9}$/;
            if (!pattern.test(this.phone_value)) {
                $.toast("手机号码格式不正确！", 'text');
                return;
            } else {
                return this.phone_value;
            }

        },
        address:function () {
            if(!this.address_value) {
                $.toast("详细地址不能为空！", 'text');
                return;
            } else {
                return this.address_value;
            }
        },
        name:function () {
            if (!this.name_value) {
                $.toast("收货人不能为空！", 'text');
                return;
            } else {
                return this.name_value;
            }
        },
        submit_address:function () {
            if (this.address() && this.phone() && this.name()) {
                $("#address").submit();
            }
        }
    }
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
             check.address_value = $('input[name="address2"]').val().trim();
             check.phone_value = $('input[name="phone"]').val().trim();
             check.name_value = $('input[name="name"]').val().trim();
             check.submit_address();
        })
    })
</script>
@endsection