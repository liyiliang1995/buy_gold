<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class HourAvgPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crontab:hour_avg_price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '计划任务：每小时统计均价';

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

    }
}
