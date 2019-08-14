<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePhoneBuyGoldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_gold', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_statistical')->default(0)->comment('是否统计过 0否 1是');
        });
        Schema::table('phone_buy_gold', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_statistical')->default(0)->comment('是否统计过 0否 1是');
        });
        //
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
