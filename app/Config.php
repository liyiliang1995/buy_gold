<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';

    /**
     * @param int $iType
     * @see 按类型获取配置
     */
    public static function getConfigByType(int $iType):array
    {
        $aRes = [];
        $aData = self::select('k','v')->where('type',$iType)->get()->toArray();
        array_map(function (array $aItem)use (&$aRes) {
            $aRes[$aItem['k']] = $aItem['v'];
        },$aData);
        return $aRes ?: [];
    }

}
