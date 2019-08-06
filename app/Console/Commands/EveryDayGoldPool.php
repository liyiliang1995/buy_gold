<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EveryDayGoldPool extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crontab:gold_pool';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天统计金币池数量，用户手中金币数量，燃烧金币数量一次';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $aGoldInfo = gold_compute();
        dd($aGoldInfo);
    }
}
