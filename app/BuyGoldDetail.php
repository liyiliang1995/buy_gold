<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BuyGoldDetail extends Model
{
    use SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = ['type','buy_gold_id','flow_id'];
    /**
     * @var string
     */
    protected $table = 'buy_gold_detail';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buy_gold()
    {
        return $this->belongsTo('App\BuyGold');
    }
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
    public function glod_flow()
    {
        return $this->belongsTo('App\GlowFlow');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @see 一对一 积分值值流水
     */
    public function integral_flow()
    {
        return $this->belongsTo('App\IntegralFlow');
    }
}
