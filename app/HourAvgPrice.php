<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class HourAvgPrice extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'hour_avg_price';
    /**
     * @var float
     * @默认价格
     */
    protected $default_avg_price = 1.00;

    /**
     * 获取最新均价
     */
    public function getBestNewAvgPrice():string
    {
        return $this->orderBy('id','desc')->value("avg_price") ?? $this->default_avg_price ;
    }
}
