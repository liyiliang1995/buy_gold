<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMemberRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gold_change_day', function (Blueprint $table) {
            $table->dropColumn('shop_gold');
            $table->decimal("burn_gold",14,2)->unsigned()->default('0.00')->comment("彻底燃烧金币");
        });
        Schema::table('member', function (Blueprint $table) {
            $table->decimal("rate",11,2)->unsigned()->default('0.00')->comment("股东分成比列");
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
