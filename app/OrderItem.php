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
     * @var int
     */
    public $query_page = 1;
    /**
     * @var array
     */
    protected $and_fields = ['is_send'];
    /**
     * @var array
     */
    protected $fillable = ['goods_id','num','unit_price','sum_price','unit_gold','sum_gold','avg_gold_price'];

    /**
     * @return array
     */
    public function getAndFieds():array
    {
        return $this->and_fields??[];
    }

}
