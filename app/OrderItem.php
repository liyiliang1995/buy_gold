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
    public $query_page = 10;
    /**
     * @var array
     */
    protected $and_fields = ['is_send','member_id'];
    /**
     * @var
     */
    protected $appends = ["goods_img","is_send_str","express"];
    /**
     * @var array
     */
    protected $fillable = ['goods_id','num','unit_price','sum_price','unit_gold','sum_gold','avg_gold_price','member_id'];

    /**
     * @return array
     */
    public function getAndFieds():array
    {
        return $this->and_fields??[];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo('App\Order','order_no','order_no');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function goods()
    {
        return $this->belongsTo("App\Good");
    }

    /**
     * @return mixed
     */
    public function getGoodsImgAttribute():string
    {
        return  czf_asset($this->goods->list_img);
    }

    /**
     * @return string
     */
    public function getIsSendStrAttribute():string
    {
        return $this->is_send ? "已发货" : "待发货";
    }

    /**
     * @return string
     */
    public function getExpressAttribute():string
    {
        return $this->order->express ?? "";
    }

}
