<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhoneBuyGold extends Model
{
    //
    public function phone_buy_gold_details()
    {
        return $this->hasMany('App\PhoneBuyGoldDetail');
    }
}
