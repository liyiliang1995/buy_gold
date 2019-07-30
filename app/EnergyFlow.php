<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Types\Integer;

class EnergyFlow extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'energy_flow';
    /**
     * @var array
     */
    protected $fillable = ['type','energy','user_id','other'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function buy_gold_detail()
    {
        return $this->hasOne('App\BuyGoldDetail','flow_id');
    }

}
