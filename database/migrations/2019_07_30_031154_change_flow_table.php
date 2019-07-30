<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFlowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('energy_flow', function (Blueprint $table) {
            $table->unsignedInteger("energy")->default(0)->comment("能量值")->change();
        });
        Schema::table('integral_flow', function (Blueprint $table) {
            $table->unsignedInteger("integral")->default(0)->comment("积分值")->change();
        });
        Schema::table('buy_gold', function (Blueprint $table) {
            $table->index('is_show');
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
