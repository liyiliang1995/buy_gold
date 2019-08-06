<?php

namespace App\Console\Commands;
use App\BuyGold;
use App\HourAvgPrice;
use App\DayBuyGoldSum;
use Illuminate\Console\Command;


class EveryDayAvgPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crontab:day_avg_price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天算一次均价';
    /**
     * @var
     */
    protected $hap_model;
    /**
     * @var
     */
    protected  $dbgs_model;
    /**
     * @var
     */
    protected $date;
    /**
     * @var
     */
    protected $buy_gold_model;

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
        $this->hap_model = new HourAvgPrice;
        $this->dbgs_model = new DayBuyGoldSum;
        $this->buy_gold_model = new BuyGold;
        $this->date = date("Y-m-d");
        $this->start_time = $this->date." 00:00:00";
        $this->end_time = $this->date." 23:59:59";
        if ($this->existsData() === false) {
            // 一天的均价
            $this->dbgs_model->avg_price = $this->getAvgPrice();
            $this->dbgs_model->sum_price = $this->getSumPrice();
            $this->dbgs_model->sum_total = $this->getGoldNum();
            $this->dbgs_model->date = $this->date;
            $this->dbgs_model->save();
        }

    }

    /**
     * @return float
     * @see均价
     */
    public function getAvgPrice()
    {
        $aRes = $this->hap_model->whereBetween('created_at',[$this->start_time,$this->end_time])->avg('avg_price');
        return $aRes ?? 0.00;
    }

    /**
     * @see 获取一天金币交易数量
     */
    public function getGoldNum():float
    {
        return $this->buy_gold_model->whereBetween('created_at',[$this->start_time,$this->end_time])->where('status',1)->sum('gold') ?? 0.00;
    }

    /**
     * @return mixed
     */
    public function getSumPrice():float
    {
        return $this->buy_gold_model->whereBetween('created_at',[$this->start_time,$this->end_time])->where('status',1)->sum('sum_price')?? 0.00;;
    }

    public function existsData():bool
    {
        return $this->dbgs_model->where('date',$this->date)->exists();
    }


}
