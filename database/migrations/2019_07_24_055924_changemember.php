<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Changemember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member', function (Blueprint $table) {
            $table->string('remember_token', 100)->default('');
            $table->unsignedInteger('parent_user_id')->default(0)->comment('上级代理用户id')->change();
            $table->unsignedSmallInteger('child_user_num')->default(0)->comment('下线用户个数')->change();
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
