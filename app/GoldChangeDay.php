<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class GoldChangeDay extends Model
{

    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'gold_change_day';

    /**
     * @see 上一次金币统计数据
     */
    public function getLastData():object
    {
        return $this->orderBy('id','desc')->first();
    }
}
