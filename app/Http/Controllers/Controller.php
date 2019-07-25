<?php

namespace App\Http\Controllers;
use App\Logics\MemberLogic;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * @var
     * @see 逻辑对象
     */
    protected static $oLogic;

    /**
     * @param Member $member
     * @return MemberLogic
     */
    public static function getLogic(object $oModel)
    {
        if(!(self::$oLogic instanceof MemberLogic)) {
            self::$oLogic = new MemberLogic($oModel);
        }
        return self::$oLogic;
    }
}
