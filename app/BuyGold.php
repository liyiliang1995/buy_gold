<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;
class BuyGold extends Model
{
    use  SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'buy_gold';

    //protected $and_fields = ['is_show','status'];
    /**
     * @var int
     */
    public $query_page = 10;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @see 一对购买金币多订单详情
     */
    public function buy_gold_details()
    {
        return $this->hasMany('App\BuyGoldDetail');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo('App\Member','user_id');
    }


    /**
     * @param array $aData
     */
    public function beforeInsert(array $aData)
    {
        if (\Auth::user()->isNormalMember() == false)
            throw ValidationException::withMessages(['gold'=>["请检查当前用户是否处于未激活或者冻结状态！"]]);
        if ($this->isExistsBuyGold())
            throw ValidationException::withMessages(['gold'=>["当前用户还有一笔求购金币订单交易未完成"]]);
    }

    /**
     * @see 插入以后
     */
    public function afterInsert()
    {
        $this->freezeBuyer();
    }

    /**
     * @see 冻结买房
     */
    public function freezeBuyer()
    {
        //购买金币的状态 为冻结
        \Auth::user()->status = 3;
        \Auth::user()->save();
    }

    /**
     * @return bool
     * @see 是否存在上架没有完成交易的数据
     */
    public function isExistsBuyGold():bool
    {
        $iRes = $this->where(['user_id' => userId(),'status' => 0,'is_show' => 1])->count();
        return $iRes ? true : false;
    }

    /**
     * @param $value
     * @see 燃烧金币
     */
    public function getBurnGoldAttribute():string
    {
        return bcmul($this->gold,0.05,2);
    }

    /**
     * @return float
     * @see 消耗积分
     */
    public function getConsumeIntegralAttribute():string
    {
        return (int)$this->gold;
    }

    /**
     * @return string
     * @see 合计金币
     */
    public function getSumGoldAttribute():string
    {
        return bcadd($this->gold,$this->burn_gold,2);
    }

    /**
     * @return int
     * @see 两部成交金币数量的能量值
     */
    public function getEnergyAttribute():int
    {
        return bcmul($this->gold,2,0);
    }


    /**
     * @return array
     */
    public function parentFlag():array
    {
        return [
            'status' => 0,
            'is_show' => 1,
        ];
    }
}
