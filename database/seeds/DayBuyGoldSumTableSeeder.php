<?php

use Illuminate\Database\Seeder;

class DayBuyGoldSumTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0;$i<15;$i++) {
            $day = 15 - $i;
            $prices = [1.78,1.30,1.01,1.22,1.33,1.26,1.23,1.58,1.65,1.20];
            $avg_price = $prices[array_rand($prices,1)];
            $total = mt_rand(100,1000);
            DB::table('day_buygold_sum')->insert([
                'avg_price' => $avg_price,
                'date' => date("Y-m-d",strtotime("-$day day")),
                'sum_total' => $total,
                'sum_price' => bcdiv($total,$avg_price,2)
            ]);
        }
    }
}
