<?php

namespace App\Console\Commands;
use App\HourAvgPrice as HourAvgPriceModel;
use App\BuyGold;
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
     * @var
     */
    protected $hour_avg_price_model;
    /**
     * @var
     */
    protected $items;
    /**
     * @var
     */
    protected $buy_gold_model;
    /**
     * @var
     */
    protected $start_time;

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
        $this->run_logic();
    }

    /**
     * @return int|void
     * @脚本运行
     */
    public function run_logic()
    {
        $aData = $this->getHourAvgPriceLast();
        // 小时均价表没有数据
        if (empty($aData['created_at'])) {
            $this->items = $this->getNoTimeItems();

        } else {
            $this->start_time = $aData['created_at'];
            $this->items = $this->getItems();
        }
        if (empty($this->items))
            $fAvgPrice = 0.50;
        else
            $fAvgPrice = $this->getAvgPrice();
        $this->getHourAvgPriceModel()->avg_price = $fAvgPrice;
        $this->getHourAvgPriceModel()->save();
    }

    /**
     * @return mixed
     */
    public function getNoTimeItems()
    {
        return $this->getBuyGoldModel()
            ->select('price')
            ->where('status',1)
            ->get('price')
            ->toArray();
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->getBuyGoldModel()
            ->select('price')
            ->where('status',1)
            ->whereBetween('created_at',[$this->start_time,$this->getEndTime()])
            ->get('price')
            ->toArray();
    }

    public function getEndTime()
    {
        return date('Y-m-d H:i:s', time());
    }

    /**
     * @return float
     */
    public function getSum():float
    {
        return array_sum(array_column($this->items,'price'));
    }

    /**
     * @return int
     */
    public function getCount():int
    {
        return count($this->items);
    }

    /**
     * @return float
     * @see 计算
     */
    public function compute():float
    {
        return bcdiv($this->getSum(),$this->getCount(),2);
    }

    /**
     * @return float
     */
    public function getAvgPrice():float
    {
        return $this->compute();
    }



    //public function

    /**
     * @return object
     */
    public function getHourAvgPriceModel():object
    {
        if (!($this->hour_avg_price_model instanceof HourAvgPriceModel)) {
            $this->hour_avg_price_model =  new HourAvgPriceModel;
        }
        return $this->hour_avg_price_model;
    }

    /**
     * @return object
     */
    public function getBuyGoldModel():object
    {
        if (!($this->buy_gold_model instanceof BuyGold)) {
            $this->buy_gold_model =  new BuyGold();
        }
        return $this->buy_gold_model;

    }

    /**
     * @see 获取最后一跳数据
     */
    public function getHourAvgPriceLast()
    {
        return $this->getHourAvgPriceModel()->orderBy('id','desc')->first();
    }

}
