<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHourAvgPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hour_avg_price', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal("avg_price",11,2)->unsigned()->default(0.00)->comment('小时金币价格');
            $table->unsignedTinyInteger('is_statistical')->default(0)->comment('是否统计过 0否 1是');
            $table->index('avg_price');
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
        Schema::dropIfExists('hour_avg_price');
    }
}
