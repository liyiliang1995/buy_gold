<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class HourAvgPrice extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'hour_avg_price';
}
