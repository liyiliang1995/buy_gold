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
     * @param array $aData
     */
    public function beforeInsert(array $aData)
    {
        if ($this->isExistsBuyGold())
            throw ValidationException::withMessages(['gold'=>["当前用户还有一笔求购金币订单交易未完成"]]);
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
