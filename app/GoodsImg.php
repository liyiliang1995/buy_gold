<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class GoodsImg extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'goods_img';
    /**
     * @var array 
     */
    protected $fillable = ['img'];
}
