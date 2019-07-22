<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    //
    public function goodsimgs()
    {
        return $this->belongsToMany(GoodsImg::class);
    }
}
