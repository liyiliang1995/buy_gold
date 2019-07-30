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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function buy_gold_detail()
    {
        return $this->hasOne('App\BuyGoldDetail','flow_id');
    }
}
