<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DayBuyGoldSum extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'day_buygold_sum';
    /**
     * @var int
     */
    public $query_page = 15;

    public $appends = ['day'];

    public function getDayAttribute()
    {
         return explode('-',$this->date)[2];
    }
}
