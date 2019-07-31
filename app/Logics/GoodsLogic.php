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
use App\Exceptions\CzfException;
class GoodsLogic extends BaseLogic
{
    /**
     * @var
     */
    protected $goods_detail;
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

    /**
     * @param array $aParams
     * @see 保存订单
     * @金币购买时 商品消耗金币不能超过持有金币的50%
     * @购物后赠送10倍积分
     * @至少激活一个用户才可以购物
     */
    public function orderSave(array $aParams)
    {
        $this->goods_detail = $this->model->findOrFail($aParams['goods_id']);
        $this->orderSaveValidate($aParams);
        $bRes = DB::transaction(function (){

        });
    }

    public function orderSaveValidate(array $aParams)
    {
        if (floor($aParams['num']) - $aParams['num'] != 0 || $aParams['num'] <= 0)
            throw new CzfException('购买数量必须是一个大于0的整数');
        if (isset($aParams['other']) && mb_strlen($aParams['other']) > 200)
            throw new CzfException("留言长度字符不能超过200字符");
        if (empty($aParams['gold_price']) || $aParams['gold_price'] < 0.5)
            throw new CzfException("操作异常,购买价格不正常！");
        //dd(\Auth::user()->checkMemberOneHalfGold($this->goods_detail->gold));
        if (\Auth::user()->checkMemberOneHalfGold($this->goods_detail->gold) === false)
        {

        }
    }

    /**
     * 设置购买商品总价格
     */
    public function getGoodsSumPrice(int $iNum):float
    {
        return bcmul($this->goods_detail->amount,$iNum,2);
    }

    /**
     * @param int $iNum
     * @return string
     */
    public function getGoodsSumGold(int $iNum)
    {
        $sum_price = $this->getGoodsSumPrice();
        //return bcdiv($sum_price,);
    }

}
