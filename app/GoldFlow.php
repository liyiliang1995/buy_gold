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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function buy_gold_detail()
    {
        return $this->hasOne('App\BuyGoldDetail','flow_id');
    }
}
