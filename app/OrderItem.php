<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class OrderItem extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'order_item';
    /**
     * @var array
     */
    protected $fillable = ['goods_id','num','unit_price','sum_price','unit_gold','sum_gold','avg_gold_price'];

}
