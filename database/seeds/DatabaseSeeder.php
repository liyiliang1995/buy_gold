<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gold_change_day')->insert([
            // 金币池总金额
            'gold' => 2000000000.00,
            // 用户手中总金额
            'user_sum_gold' => 0.00,
            'date' => date('Y-m-d'),
            // 用户购物消耗总金额
            'burn_gold' => 0.00,
        ]);
    }
}
