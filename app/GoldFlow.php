<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class GoldFlow extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'gold_flow';
    /**
     * @var array
     */
    protected $fillable = ['type','gold','is_income','user_id','other'];
    /**
     * @var array
     */
    protected $hidden = ['buy_gold_detail'];
    /**
     * @var int
     */
    public $query_page = 10;
    /**
     * @var array
     */
    protected $and_fields = ['user_id','type','is_income'];
    /**
     * @var array
     */
    protected $appends = ["order"];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function buy_gold_detail()
    {
        return $this->hasOne('App\BuyGoldDetail','flow_id');
    }
    /**
     * @return array
     */
    public function getAndFieds():array
    {
        return $this->and_fields??[];
    }

    /**
     * @return mixed
     */
    public function getOrderAttribute()
    {
          return  $this->buy_gold_detail->buy_gold ?? null;
    }

    /**
     * @return float
     * @see 金币池出
     */
    public function getGoldPullOut():float
    {
        return $this->getRechargeNum(9);
    }

    /**
     * @param int $iType 9 后台充值增加 10后台充值减少
     * @return float
     * @see 金币充值
     */
    public function getRechargeNum(int $iType):float
    {
        return $this->where(['is_statistical' => 0,'type'=>$iType])->sum('gold');
    }
    /**
     * @return float金币池进
     */
    public function getGoldPullIn():float
    {
        // 金币燃烧返回金币池
        $bNum = $this->getBurnGoldSum();
        // 充值扣除
        $rNum = $this->getRechargeNum(10);
        return bcadd($bNum,$rNum,2);
    }

    /**
     * @return float
     * @see 购物消耗
     */
    public function getShopGoldNum():float
    {
        return $this->where(['is_statistical' => 0,'type'=>1])->sum('gold');
    }

    /**
     * @return float
     */
    public function getBurnGoldSum():float
    {
        return $this->where(['is_statistical' => 0,'type'=>5])->sum('gold');
    }

}
