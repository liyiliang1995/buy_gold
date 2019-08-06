<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class IntegralFlow extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'integral_flow';
    /**
     * @var array
     */
    protected $fillable = ['type','integral','user_id','other'];
    /**
     * @var int
     */
    public $query_page = 10;
    /**
     * @var array
     */
    protected $and_fields = ['user_id','type'];
    /**
     * @var array
     */
    protected $hidden = ['other'];
    /**
     * @var array
     */
    protected $appends = ["show_type"];
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

    public function getShowTypeAttribute()
    {
        return config("czf.integral_show_type")[$this->type];
    }

}
