<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    public $query_page = 100;
    //
    public function goodsimgs()
    {
        return $this->hasMany(GoodsImg::class,'goods_id');
    }

}
