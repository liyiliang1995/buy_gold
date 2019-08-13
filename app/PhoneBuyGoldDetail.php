<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PhoneBuyGoldDetail extends Model
{
    //
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'phone_buy_gold_detail';
    /**
     * @var array
     */
    protected $fillable = ['type','buy_gold_id','flow_id'];
}
