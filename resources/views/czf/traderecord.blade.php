@extends('czf.base',['header'=>'交易记录',
    'css' => [
            'css/weui.min.css',
            'css/jquery-weui.css',
            'css/demos.css'
        ],
    'js' => [
            'js/fastclick.js',
            'js/jquery-weui.js',
        ],
    'script'=> [
        "FastClick.attach(document.body);",
        '$("#trading_a").bind("click",function () {
            $.toast("撤单成功");
        });',
        '$("#trading_b").bind("click",function () {
            $.toast("收款成功");
        });'
    ]
])
@section('content')
    <body>
    <div class="weui-flex" id="header_top">
        <a href="javascript:history.back(-1)"><img src="/img/fh.png" alt=""></a>
        <div class="weui-flex__item">交易记录</div>
    </div>


    <div class="weui-tab">
        <div class="weui-navbar">
            <a class="weui-navbar__item weui-bar__item--on" href="#tab1">
                求购记录
            </a>
            <a class="weui-navbar__item" href="#tab2">
                出售记录
            </a>
        </div>

        <div class="weui-tab__bd">
            <div id="tab1" class="weui-tab__bd-item weui-tab__bd-item--active">
                <div class="weui-panel weui-panel_access">
                    <div class="weui-panel__bd">
                        <!--1-->
                        <div class="weui-flex" style="  font-size: 12px;  padding: 5px 15px;border-top: 5px solid #eee;border-bottom: 2px solid #eee;">
                            <div class="weui-flex__item">2019-07-19 10:44</div>
                            <div class="weui-flex__item" style="text-align: right;color: red">求购中</div>
                        </div>
                        <div class="weui-flex" style=" padding: 5px 15px; ">
                            <div class="weui-flex__item">金币数量</div>
                            <div class="weui-flex__item" style="text-align: right">200</div>
                        </div>
                        <div class="weui-flex"  style=" padding: 5px 15px; ">
                            <div class="weui-flex__item">总金额</div>
                            <div class="weui-flex__item" style="text-align: right;color: red">+160</div>
                        </div>
                        <div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;">
                            <div class="weui-flex__item" style="color: red"><a href="buy.html" style="color: #333">查看详情>></a></div>
                            <div class="weui-flex__item" style="text-align: right"><a id="trading_a" href="javascript:;" class="weui-btn weui-btn_primary">申请撤单</a></div>
                        </div>
                        <!--2-->
                        <div class="weui-flex" style="  font-size: 12px;  padding: 5px 15px;border-top: 5px solid #eee;border-bottom: 2px solid #eee;">
                            <div class="weui-flex__item">2019-07-19 10:44</div>
                            <div class="weui-flex__item" style="text-align: right;color: red">已成交</div>
                        </div>
                        <div class="weui-flex" style=" padding: 5px 15px; ">
                            <div class="weui-flex__item">金币数量</div>
                            <div class="weui-flex__item" style="text-align: right">200</div>
                        </div>
                        <div class="weui-flex"  style=" padding: 5px 15px; ">
                            <div class="weui-flex__item">总金额</div>
                            <div class="weui-flex__item" style="text-align: right;color: red">+160</div>
                        </div>
                        <div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;">
                            <div class="weui-flex__item" style="color: red"><a href="" style="color: #333">查看详情>></a></div>
                            <div class="weui-flex__item" style="text-align: right"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tab2" class="weui-tab__bd-item">
                <div class="weui-panel weui-panel_access">
                    <div class="weui-panel__bd">
                        <!--1-->
                        <div class="weui-flex" style="  font-size: 12px;  padding: 5px 15px;border-top: 5px solid #eee;border-bottom: 2px solid #eee;">
                            <div class="weui-flex__item">2019-07-19 10:44</div>
                            <div class="weui-flex__item" style="text-align: right;color: red">未收款</div>
                        </div>
                        <div class="weui-flex" style=" padding: 5px 15px; ">
                            <div class="weui-flex__item">金币数量</div>
                            <div class="weui-flex__item" style="text-align: right">200</div>
                        </div>
                        <div class="weui-flex"  style=" padding: 5px 15px; ">
                            <div class="weui-flex__item">总金额</div>
                            <div class="weui-flex__item" style="text-align: right;color: #4CAF50">+160</div>
                        </div>
                        <div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;">
                            <div class="weui-flex__item" style="color: red"><a href="" style="color: #333">查看详情>></a></div>
                            <div class="weui-flex__item" style="text-align: right"><a id="trading_b" href="javascript:;" class="weui-btn weui-btn_primary">确认收款</a></div>
                        </div>
                        <!--2-->
                        <div class="weui-flex" style="  font-size: 12px;  padding: 5px 15px;border-top: 5px solid #eee;border-bottom: 2px solid #eee;">
                            <div class="weui-flex__item">2019-07-19 10:44</div>
                            <div class="weui-flex__item" style="text-align: right;color: red">已收款</div>
                        </div>
                        <div class="weui-flex" style=" padding: 5px 15px; ">
                            <div class="weui-flex__item">金币数量</div>
                            <div class="weui-flex__item" style="text-align: right">200</div>
                        </div>
                        <div class="weui-flex"  style=" padding: 5px 15px; ">
                            <div class="weui-flex__item">总金额</div>
                            <div class="weui-flex__item" style="text-align: right;color: #4CAF50">+160</div>
                        </div>
                        <div class="weui-flex" style="  font-size: 12px;  padding: 10px 15px;">
                            <div class="weui-flex__item" style="color: red"><a href="" style="color: #333">查看详情>></a></div>
                            <div class="weui-flex__item" style="text-align: right"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
    </body>
@endsection