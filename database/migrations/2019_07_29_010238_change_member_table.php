<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 2 商品图片表
        Schema::create('member_ship_address', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->comment('用户id');
            $table->string('ship_address', 255)->default('')->comment("收货地址");
            $table->string('name',128)->default('')->comment("收获联系人");
            $table->char('phone',11)->default('')->comment("收获手机号码");
            $table->index('member_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('member', function (Blueprint $table) {
            $table->dropColumn('ship_address');
            $table->unsignedInteger("energy")->unsigned()->default(0)->comment("能量值")->change();
            $table->unsignedInteger("integral")->unsigned()->default(0)->comment("积分")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_ship_address');
        //
    }
}
