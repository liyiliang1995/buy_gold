<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class OrderDetail extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = "order_detail";
    /**
     * @var array
     */
    protected $fillable = ['type','order_id','flow_id'];
    /**
     * @var array
     */
    protected $appends = ["show_type"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @see 一对一 能量值流水
     */
    public function energy_flow()
    {
        return $this->belongsTo('App\EnergyFlow','flow_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @see 一对一 金币值流水
     */
    public function gold_flow()
    {
        return $this->belongsTo('App\GoldFlow','flow_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @see 一对一 积分值值流水
     */
    public function integral_flow()
    {
        return $this->belongsTo('App\IntegralFlow','flow_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo('App\Order');
    }
    /**
     * @return mixed
     */
    public function getShowTypeAttribute()
    {
        return config("czf.flow_show_type")[$this->type];
    }
}
