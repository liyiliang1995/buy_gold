<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('k',32)->default('')->comment('字段名称');
            $table->text('v')->comment('值');
            $table->unsignedTinyInteger('type')->default(1)->comment('设置类型 1网站常用配置 2 费率配置');
            $table->string('name',32)->default('')->comment('字段名称');
            $table->string('desc',128)->default('')->comment('字段描述');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->string('text_type',32)->default('text')->comment('text select fileupload kindeditor password');
            $table->string('text_value',32)->default('')->comment("{1:'名称',2:'名称'}多选框 多选值");
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
        Schema::dropIfExists('config');
    }
}
