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
    public $query_page = 7;
    /**
     * @var array
     */
    protected $and_fields = ['user_id','type'];
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
          return  $this->buy_gold_detail->buy_gold;
    }

}
