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
}
