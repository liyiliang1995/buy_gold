<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Good extends Model
{
    use SoftDeletes;
    /**
     * @var int
     */
    public $query_page = 100;
    /**
     * @see 一对多
     */
    public function goodsimgs()
    {
        return $this->hasMany(GoodsImg::class,'goods_id');
    }

    /**
     * @see 判断用户是否有地址
     */
    public function userHasExistsAddress():bool
    {
        return (\Auth::user()->ship_address) ? true :false;
    }

    /**
     * @see 转换金币
     */
    public function amountToGold(int $iNum,float $fAvgPrice):float
    {
        return bcdiv($this->getSumPrice($iNum),$fAvgPrice,2);
    }

    /**
     * @param $fAvgPrice
     * @return float
     * @see 单价转金币
     */
    public function unitAmountToGold(float $fAvgPrice,int $iNum):float
    {
        return bcdiv($this->amountToGold($iNum,$fAvgPrice),$iNum,2);
    }

    /**
     * @return float
     */
    public function getSumPrice(int $iNum):float
    {
        return bcmul($this->amount,$iNum,2);
    }



}
