<?php
/**
 * Created by PhpStorm.
 * User: youxingxiang
 * Date: 2019/7/24
 * Time: 10:04 AM
 */
namespace App\Logics;

class GoodsLogic extends BaseLogic
{
    /**
     * @see 修改收货地址
     */
    public function editShipAddress(array $aParam)
    {
        $this->validateEditShipAddress();
        $bRes = $this->update(userId(),$aParam);
        dd($bRes);
    }

    /**
     * @param $aParam
     */
    public function validateEditShipAddress()
    {
        request()->validate(
            [
                'address1' => 'required|max:120',
                'address2' => 'required|max:120',
            ],
            [
                "address2.required"  => "详细地址不能为空！",
            ]
        );
    }
}
