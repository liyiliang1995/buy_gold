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
        return pay_gold($fAvgPrice,$this->getSumPrice($iNum));
    }

    /**
     * @param $fAvgPrice
     * @return float
     * @see 单价转金币
     */
    public function unitAmountToGold(float $fAvgPrice,int $iNum):float
    {
        return unit_gold($this->amountToGold($iNum,$fAvgPrice),$iNum);
    }

    /**
     * @param int $iNum
     * @return float
     * @返回商品总价格
     */
    public function getSumPrice(int $iNum):float
    {
        return sum_price($iNum,$this->amount);
    }



}
