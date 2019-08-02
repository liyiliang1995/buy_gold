@extends('czf.base',['header'=>'用户 - 完善信息',
'css' => [
         'css/weui.min.css',
        '/css/weui.css',
        'css/demos.css',
    ],
'js' => [
        'js/fastclick.js',
        'js/jquery-weui.js'
    ],
'script' => [
        "FastClick.attach(document.body);"
    ]
])

@section('content')
    <style>
        #partner_list{
            font-size: 12px;
        }
    </style>
    <body>
    <div class="weui-flex">
        <div class="weui-flex__item" style="line-height: 35px;padding: 10px;font-weight: bold">注册新用户</div>
    </div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" name="phone" value="{{ old('phone') }}" placeholder="请输入手机号">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <input class="weui-input" type="password" name="password" placeholder="请输入密码">
                </div>
            </div>
        </div>
        <div class="weui-flex">
            <div class="weui-flex__item"><a id="rg" href="javascript:;" class="weui-btn weui-btn_primary"
                                            style="width: 90%;margin-top: 30px;">注册</a></div>
        </div>


    <div class="weui-flex">
        <div class="weui-flex__item" style="line-height: 35px;padding: 10px;font-weight: bold">我的伙伴</div>
    </div>

    <div class="weui-flex" id="partner_list">
        <div class="weui-flex__item">持有金币总数：<span style="color: #F44336">{{$dSum}}</span></div>
    </div>


    @foreach($oPartner as $key=>$value)
    <div class="weui-flex" id="partner_list">
        <div class="weui-flex__item" id="partner_user">
            <p>{{$value['name'] ?: $value['phone']}}</p>
            <p>{{$value['created_at']}} 加入</p>
        </div>
        <div class="weui-flex__item" style="line-height: 45px">
            金币数：<span>{{$value['gold']}}</span>
        </div>
        <div class="weui-flex__item">
            {!! $value['stat'] !!}
        </div>
    </div>
    @endforeach

    </body>
    <script>
        var rg = {
            isPhoneNo:function (phone) {
                var pattern = /^1[349578]\d{9}$/;
                return pattern.test(phone);
            },
            phone:function () {
                var phone = $('input[name="phone"]').val().trim();
                if (this.isPhoneNo(phone))
                    return phone;
                else {
                    $.toast("手机号码格式不正确！", 'text');
                    return;
                }

            },
            pwd:function () {
                var pwd = $('input[name="password"]').val().trim();
                if (pwd)
                    return pwd;
                else{
                    $.toast("密码不能为空！", 'text');
                    return;
                }

            },
            register:function () {
                if (this.pwd() && this.phone()) {
                    $.ajax({
                        url: "{{route('agentRegister')}}",
                        type:'post',
                        dataType: "json",
                        data:{ phone:this.phone(),password:this.pwd(),_method:'post' ,_token:"{{csrf_token()}}"},
                        error:function(data){
                            $.toast("服务器繁忙, 请联系管理员！",'text');
                            return;
                        },
                        success:function(result){
                            if(result.code == 200){
                                $.toast('注册成功!','text');
                                window.location.reload();
                            } else {
                                $.toast(result.message,'text')
                            }
                        },
                    })
                }
            }
        }
        $(function () {
            $("#rg").on('click',function() {
                rg.register();
            })
        })
    </script>
@endsection
