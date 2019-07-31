<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Order extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = "order";

    /**
     * @return string
     * @see 获取单号
     */
    public function getOrderNo():string
    {
        return date('YmdHis') . rand(1000,9999);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_items()
    {
        return $this->hasMany('App\OrderItem','order_no','order_no');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_details()
    {
        return $this->hasMany('App\OrderDetail','order_id');
    }

}
