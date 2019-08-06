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
        // 领取扣除
        $fGoldA = $this->getAutoGoldSum();
        $fGoldR = $this->getRechargeNum(9);
        return bcadd($fGoldA,$fGoldR,2);
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
        $bNum = $this->getReturnBurnGoldSum();
        // 金币购物返回金币池
        $sNum = $this->getReturnShopGoldNum();
        // 充值扣除返回金币池
        $rNum = $this->getRechargeNum(10);

        return bcadd(bcadd($bNum,$sNum,5),$rNum,2);
    }

    /**
     * @return float
     * @see 购物消耗
     * @see 购物金币流向金币池
     */
    public function getReturnShopGoldNum():float
    {
        return $this->where(['is_statistical' => 0,'type'=>12])->sum('gold');
    }

    /**
     * @return float
     */
    public function getReturnBurnGoldSum():float
    {
        return $this->where(['is_statistical' => 0,'type'=>5])->sum('gold');
    }

    /**
     * @return float
     * @彻底燃烧金币
     */
    public function getBurnGoldSum():float
    {
        return $this->where(['is_statistical' => 0,'type'=>11])->sum('gold');
    }

    /**
     * @return float
     */
    public function getAutoGoldSum():float
    {
        return $this->where(['is_statistical' => 0,'type'=> 4])->sum('gold');
    }

    /**
     * @see 获取最后一条领取数据
     */
    public function getLastAutoGold()
    {
        return $this->where('user_id',userId())->where('type',4)->orderBy('id','desc')->first() ?? [];
    }

}
