<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PhoneBuyGold extends Model
{
    use  SoftDeletes;
    /**
     * @var int
     */
    public $query_page = 10;
    /**
     * @var string
     */
    protected $table = 'phone_buy_gold';
    /**
     * @var array
     */
    protected $and_fields = ['user_id','seller_id','status','is_show'];
    /**
     * @var array
     */
    protected $parent_flag = ['status' => 0, 'is_show' => 1];
    /**
     * @var array
     */
    protected $appends = ["phone_buy_gold_status",'give_status','apply_url','confirm_url','detail_url'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phone_buy_gold_details()
    {
        return $this->hasMany('App\PhoneBuyGoldDetail');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo('App\Member','user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo('App\Member','seller_id');
    }

    /**
     * @return bool
     */
    public function isExistsPhoneBuyGold():bool
    {
        $iRes = $this->where(['user_id' => userId(),'status' => 0,'is_show' => 1])->count();
        return $iRes ? true : false;
    }

    /**
     * @return float
     * @see 用户挂单出去的金币
     */
    public function getNotclinchGold():float
    {
        return $this->where('status',0)->where('is_show',1)->sum('gold') ?? 0.00;
    }

    /**
     * @return array
     */
    public function getAndFieds():array
    {
        return $this->and_fields??[];
    }

    /**
     * @return string
     */
    public function getPhoneBuyGoldStatusAttribute():string
    {
        $sRes = '';
        if ($this->status == 0 && !$this->seller_id)
            $sRes = "求购中";
        else if ($this->status == 0 && $this->seller_id)
            $sRes = "交易中";
        else if  ($this->status == 1)
            $sRes = "交易完成";
        return $sRes;
    }

    /**
     * @param $price
     * @return string
     */
    public function getPriceAttribute($price):string
    {
        return $price > 0 ? $price : (new HourAvgPrice)->getBestNewAvgPrice();
    }

    /**
     * @param $price
     * @return string
     */
    public function getGoldAttribute($gold):string
    {
        return $gold > 0 ? $gold :bcmul(bcdiv($this->sum_price,$this->price,2),1.2,2);
    }

    /**
     * @return string
     */
    public function getGiveStatusAttribute():string
    {
        $sRes = '';
        if ($this->status == 0)
            $sRes = "未收款";
        else if ($this->status == 1)
            $sRes = '已收款';
        return $sRes;
    }

    public function getDetailUrlAttribute():string
    {
        return route('phone_details',['id'=>$this->id]);
    }

    /**
     * @return string
     * @see 撤销订单url
     */
    public function getApplyUrlAttribute():string
    {
        return route("apply_cancel_phone_order",['id'=>$this->id]);
    }

    /**
     * @return string
     * @see 确认订单url
     */
    public function getConfirmUrlAttribute():string
    {
        return route("confirm_phone_order",['id'=>$this->id]);
    }


}
