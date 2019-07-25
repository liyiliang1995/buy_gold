<?php
/**
 * Created by PhpStorm.
 * User: youxingxiang
 * Date: 2019/7/24
 * Time: 10:04 AM
 */
namespace App\Logics;

class MemberLogic extends BaseLogic
{
    /**
     * @param array $aParam
     * @return bool
     * @see 代理注册
     */
    public function agentRegisterLogic(array $aParam):bool
    {
        $bRes = false;
        if (phoneVerif($aParam['phone'])) {
            $bRes = $this->store($aParam);
        }
        return $bRes;
    }
}
