<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeGoldChangeDayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gold_change_day', function (Blueprint $table) {
            $table->decimal("shop_gold",14,2)->unsigned()->default('0.00')->comment("购物消耗金币");
            $table->decimal("gold",14,2)->unsigned()->default(0.00)->comment("金币值")->change();
            $table->decimal("user_sum_gold",14,2)->unsigned()->default(0.00)->comment("用户持有金币数量")->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
