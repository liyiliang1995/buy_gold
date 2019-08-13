<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhoneBuyGoldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_buy_gold', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->decimal("gold",11,2)->unsigned()->default(0.00)->comment("金币值");
            $table->decimal("price",11,2)->unsigned()->default(0.00)->comment('销售价格');
            $table->decimal("sum_price",11,2)->unsigned()->default(0.00)->comment('总价格');
            $table->unsignedTinyInteger('status')->default(0)->comment('支付状态 0 未完成 1 已完成');
            $table->unsignedInteger("seller_id")->nullable()->comment("卖家id");
            $table->unsignedTinyInteger('is_show')->default(1)->comment('是否上架 1 true 2false');
            $table->index('user_id');
            $table->index('status');
            $table->index("is_show");
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('phone_buy_gold_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('phone_buy_gold_id')->comment('手机充值订单id');
            $table->unsignedTinyInteger('type')->comment('流水单号类型 1金币流水 2积分流水 3能量流水');
            $table->unsignedInteger('flow_id')->comment('流水id');
            $table->index('phone_buy_gold_id');
            $table->index('type');
            $table->index('flow_id');
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
        Schema::dropIfExists('phone_buy_gold');
        Schema::dropIfExists('phone_buy_gold_detail');
    }
}
