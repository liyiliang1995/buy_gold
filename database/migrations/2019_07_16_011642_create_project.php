<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1 商品表
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',128)->default('')->comment("商品名称");
            $table->text('describe')->default('')->comment('商品描述');
            $table->string('list_img',255)->default('')->comment("列表图片");
            $table->decimal("amount",11,2)->unsigned()->default(0.00)->comment('销售价格');
            $table->softDeletes();
            $table->timestamps();
        });
        // 2 商品图片表
        Schema::create('goods_img', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('goods_id')->comment('商品id');
            $table->string('img',255)->default('')->comment("图片");
            $table->index('goods_id');
            $table->softDeletes();
            $table->timestamps();
        });
        // 3 用户表
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',128)->default('')->comment("用户名称");
            $table->char('phone',11)->default('')->comment("手机号码");
            $table->char('phone2',11)->default('')->comment("联系手机");
            $table->decimal("gold",11,2)->unsigned()->default(0.00)->comment("持有金币量");
            $table->decimal("energy",11,2)->unsigned()->default(0.00)->comment("能量值");
            $table->decimal("integral",11,2)->unsigned()->default(0.00)->comment("积分");
            $table->unsignedInteger('parent_user_id')->comment('上级代理用户id');
            $table->unsignedSmallInteger('child_user_num')->comment('下线用户个数');
            $table->string('wechat',64)->default('')->comment("微信号");
            $table->unsignedTinyInteger('status')->default(0)->comment('状态 0 正常 1出售金币 2购买金币');
            $table->unsignedTinyInteger('is_admin')->default(0)->comment('是否管理员 0否 1是');
            $table->string('ship_address',255)->default('')->comment("收货地址");
            $table->index('phone');
            $table->index('gold');
            $table->index('energy');
            $table->index('integral');
            $table->index('parent_user_id');
            $table->index('is_admin');
            $table->softDeletes();
            $table->timestamps();
        });
        // 4 代理注册表
        Schema::create('agent_register', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->char('phone',11)->default('')->comment("手机号码");
            $table->unsignedTinyInteger('status')->default(0)->comment('状态 0 未激活 1 已经激活');
            $table->index('user_id');
            $table->index('phone');
            $table->softDeletes();
            $table->timestamps();
        });

        // 5 金币池按天变化表
        Schema::create('gold_change_day', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal("gold",11,2)->unsigned()->default(0.00)->comment("持有金币量");
            $table->decimal("user_sum_gold",11,2)->unsigned()->default(0.00)->comment("用户持有金币数量");
            $table->char('date',10)->default('')->comment("时间 如:2019-07-10");
            $table->index('gold');
            $table->index('user_sum_gold');
            $table->softDeletes();
            $table->timestamps();
        });

        // 6 订单详情表
        Schema::create('order_item', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_no',32)->default('')->comment("订单号");
            $table->unsignedInteger('goods_id')->comment('商品id');
            $table->unsignedSmallInteger('num')->default(0)->comment('商品数量');
            $table->decimal("unit_price",11,2)->unsigned()->default(0.00)->comment("单价");
            $table->decimal("sum_price",11,2)->unsigned()->default(0.00)->comment("总价");
            $table->decimal("unit_gold",11,2)->unsigned()->default(0.00)->comment("单价转化金币");
            $table->decimal("sum_gold",11,2)->unsigned()->default(0.00)->comment("总价转化金币");
            $table->decimal('avg_gold_price',11,2)->unsigned()->sdefault(0.00)->comment('当前市场金币价格 1金币/元');
            $table->index('order_no');
            $table->index('goods_id');
            $table->softDeletes();
            $table->timestamps();
        });
        // 7 订单表
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_no',32)->default('')->comment("订单号");
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->unsignedTinyInteger('pay_status')->default(0)->comment('支付状态 0 未支付 1 已支付');
            $table->decimal("pay_gold",11,2)->unsigned()->default(0.00)->comment("支付金币");
            $table->decimal("amount",11,2)->unsigned()->default(0.00)->comment("价值金额");
            $table->string('express',32)->default('')->comment("快递单号");
            $table->index('order_no');
            $table->index('user_id');
            $table->index('pay_status');
            $table->softDeletes();
            $table->timestamps();
        });

        // 8 订单流水
        Schema::create('order_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->comment('订单id');
            $table->unsignedTinyInteger('type')->comment('关联单据类型 1 金币流水 2积分流水');
            $table->unsignedInteger('flow_id')->comment('流水id');
            $table->index('order_id');
            $table->index('type');
            $table->index('flow_id');
            $table->softDeletes();
            $table->timestamps();
        });

        // 9 积分流水
        Schema::create('gold_flow', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('is_income')->comment('是否收入 0 支出 1收入 ');
            $table->unsignedTinyInteger('type')->comment('业务类型 1 用户消费 2 用户出售 3 用户求购 4领取金币 5返回金币池 6代理注册扣除 7代理扣除增加 8 15天为登陆扣除 ');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->decimal("gold",11,2)->unsigned()->default(0.00)->comment("金币值");
            $table->string('other',128)->default('')->comment("备注");
            $table->unsignedTinyInteger('is_statistical')->default(0)->comment('是否统计过 0否 1是');
            $table->index('is_income');
            $table->index('type');
            $table->index('user_id');
            $table->softDeletes();
            $table->timestamps();
        });
        // 10 能量流水
        Schema::create('energy_flow', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('type')->comment('业务类型 1 自动领取金币消耗 2求购金币获得 ');
            $table->decimal("energy",11,2)->unsigned()->default(0.00)->comment("能量值");
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->string('other',128)->default('')->comment("备注");
            $table->index('type');
            $table->index('user_id');
            $table->softDeletes();
            $table->timestamps();
        });
        // 11 积分流水
        Schema::create('integral_flow', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('type')->comment('业务类型 1消费获得 2 出售金币消耗');
            $table->decimal("integral",11,2)->unsigned()->default(0.00)->comment("积分值");
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->string('other',128)->default('')->comment("备注");
            $table->index('type');
            $table->index('user_id');
            $table->softDeletes();
            $table->timestamps();
        });

        // 12 购买金币表
        Schema::create('buy_gold', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->decimal("gold",11,2)->unsigned()->default(0.00)->comment("金币值");
            $table->decimal("price",11,2)->unsigned()->default(0.00)->comment('销售价格');
            $table->decimal("sum_price",11,2)->unsigned()->default(0.00)->comment('总价格');
            $table->unsignedTinyInteger('status')->default(0)->comment('支付状态 0 未完成 1 已完成');
            $table->index('user_id');
            $table->index('status');
            $table->softDeletes();
            $table->timestamps();
        });
        // 13 求购金币详情
        Schema::create('buy_gold_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('buy_gold_id')->comment('求购订单id');
            $table->unsignedTinyInteger('type')->comment('流水单号类型 1金币流水 2积分流水 3能量流水');
            $table->unsignedInteger('flow_id')->comment('流水id');
            $table->index('buy_gold_id');
            $table->index('type');
            $table->index('flow_id');
            $table->softDeletes();
            $table->timestamps();
        });

        // 14 每天成交价格数量均价汇总表
        Schema::create('day_buygold_sum', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal("sum_price",11,2)->unsigned()->default(0.00)->comment('当天成交金币总价格');
            $table->unsignedInteger('sum_total')->comment('当天成交金币总数量');
            $table->decimal("avg_price",11,2)->unsigned()->default(0.00)->comment('当天金币成交均价');
            $table->char('date',10)->default('')->comment("时间 如:2019-07-10");
            $table->softDeletes();
            $table->timestamps();
        });
        // 15 新闻表
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',64)->default('')->comment("新闻标题");
            $table->unsignedTinyInteger('type')->comment('分类');
            $table->text('content')->default('')->comment('内容');
            $table->unsignedTinyInteger('is_recommend')->default(0)->comment('是否推荐 0 否 1是');
            $table->softDeletes();
            $table->timestamps();
        });





    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
        Schema::dropIfExists('goods_img');
        Schema::dropIfExists('user');
        Schema::dropIfExists('agent_register');
        Schema::dropIfExists('gold_change_day');
        Schema::dropIfExists('order_item');
        Schema::dropIfExists('order');
        Schema::dropIfExists('order_detail');
        Schema::dropIfExists('gold_flow');
        Schema::dropIfExists('energy_flow');
        Schema::dropIfExists('integral_flow');
        Schema::dropIfExists('buy_gold');
        Schema::dropIfExists('buy_gold_detail');
        Schema::dropIfExists('day_buygold_sum');
        Schema::dropIfExists('news');
    }
}
