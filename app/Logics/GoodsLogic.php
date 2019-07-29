<?php
/**
 * Created by PhpStorm.
 * User: youxingxiang
 * Date: 2019/7/24
 * Time: 10:04 AM
 */
namespace App\Logics;
use App\Member;
use App\MemberShipAddress;
class GoodsLogic extends BaseLogic
{
    /**
     * @see 修改收货地址
     */
    public function editShipAddress(array $aParam):bool
    {
        $this->validateEditShipAddress();
        $member = Member::find(userId());
        if ($member->ship_address) {
            $member->ship_address->name = $aParam['name'];
            $member->ship_address->ship_address = $aParam['address1']." | ".$aParam['address2'];
            $member->ship_address->phone = $aParam['phone'];
            $bRes = $member->ship_address->save();
        } else {
            $member_ship_address = new MemberShipAddress;
            $member_ship_address->name = $aParam['name'];
            $member_ship_address->ship_address = $aParam['address1']." | ".$aParam['address2'];
            $member_ship_address->phone = $aParam['phone'];
            $bRes = $member->ship_address()->save($member_ship_address) ? true :false;
        }
        return $bRes;
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
