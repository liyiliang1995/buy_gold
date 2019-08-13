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
    protected $and_fields = ['user_id','seller_id'];
    /**
     * @var array
     */
    protected $parent_flag = ['status' => 0, 'is_show' => 1];
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


}
