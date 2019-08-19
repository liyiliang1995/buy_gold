<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->default('')->comment('标题');
            $table->tinyint('type')->comment('分类');
            $table->text('content')->default('')->comment('内容');
            $table->tinyint('is_recommend')->default('0')->comment('是否推荐');
            $table->timestamps('deleted_at');
            $table->timestamps('created_at');
            $table->timestamps('updated_at');
            $table->integer('weight')->default('0')->comment('权重');
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
        Schema::dropIfExists('news');
    }
}
