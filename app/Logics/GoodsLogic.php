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
use Illuminate\Support\Facades\DB;
class GoodsLogic extends BaseLogic
{
    /**
     * @var
     */
    protected $goods_detail;
    /**
     * @var
     */
    protected $gold;
    /**
     * @var
     */
    protected $order_model;

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
     * @扣除商品价格5%返回金币池
     */
    public function orderSave(array $aParams):bool
    {
        $this->goods_detail = $this->model->findOrFail($aParams['goods_id']);
        $this->orderSaveValidate($aParams);

        $bRes = DB::transaction(function () use($aParams){
            // 保存订单
            $this->orderSaveByModel($aParams);
            // 保存订单详情
            $this->orderItemSaveByModel($aParams);
            // 流水
            $this->orderflow();
            // 用户增减
            $this->orderIncreaseAndDecrease();
            return true;
        });
        return $bRes;
    }

    /**
     * @param array $aParams
     * @throws CzfException
     * @验证字段
     */
    public function orderSaveValidate(array $aParams)
    {
        if (redis_idempotent() === false)
            throw new CzfException('请勿恶意提交订单，过2秒钟在尝试！');
        if (floor($aParams['num']) - $aParams['num'] != 0 || $aParams['num'] <= 0)
            throw new CzfException('购买数量必须是一个大于0的整数');
        if (isset($aParams['other']) && mb_strlen($aParams['other']) > 200)
            throw new CzfException("留言长度字符不能超过200字符");
        if (empty($aParams['gold_price']) || $aParams['gold_price'] < 0.5)
            throw new CzfException("操作异常,购买价格不正常！");
        $this->gold = $this->goods_detail->amountToGold($aParams['num'],$aParams['gold_price']);
        if (\Auth::user()->checkMemberOneHalfGold($this->gold) === false)
            throw new CzfException("出售金币数量不能超过持有数量的50%!");
        if (\Auth::user()->getChildMemberNum() < 1)
            throw new CzfException("至少激活一个用户才可以购物!");
    }

    /**
     * @param array $aParams
     * @see 保存订单详情
     */
    public function orderSaveByModel(array $aParams)
    {
        $this->order_model = new \App\Order;
        $this->order_model->order_no = $this->order_model->getOrderNo();
        $this->order_model->user_id = userId();
        $this->order_model->pay_gold = $this->getSumGold();
        $this->order_model->amount = $this->goods_detail->getSumPrice($aParams['num']);
        $this->order_model->other = $aParams['other'] ?? '';
        $this->order_model->save();
    }
    /**
     * @param array $aParams
     * @see 订单明细
     */
    public function orderItemSaveByModel(array $aParams)
    {
        $this->order_model->order_items()->save(new \App\OrderItem([
            'goods_id' => $this->goods_detail->id,
            'member_id' => userId(),
            'num' => $aParams['num'],
            'unit_price' => $this->goods_detail->amount,
            'sum_price' => $this->order_model->amount,
            'unit_gold' => $this->goods_detail->unitAmountToGold($aParams['gold_price'],$aParams['num']),
            'sum_gold' => $this->gold,
            'avg_gold_price' => $aParams['gold_price'],
        ]));
    }
    /**
     * @购物完成流水
     */
    public function orderflow()
    {
        $this->order_model->order_details()->saveMany([
            // 购物扣除
            $this->getBuyGoldGoldFlowDetail(0,1,userId(),$this->gold,"购物消耗金币")('App\OrderDetail'),
            // 返回金币池
            $this->getBuyGoldGoldFlowDetail(0,5,userId(),$this->getBurnGold(),"购物燃烧金币返回金币池")('App\OrderDetail'),
            // 赠送10倍积分
            $this->getBuyGoldIntegralFlowDetail(1,userId(),$this->getGiveIntegral(),"购物赠送积分")('App\OrderDetail'),
        ]);
    }
    /**
     * @用户扣除金币
     * @用户增加积分
     * @金币池增加积分
     */
    public function orderIncreaseAndDecrease()
    {
        \Auth::user()->gold = bcsub(\Auth::user()->gold,$this->getSumGold(),2);
        \Auth::user()->integral = bcadd(\Auth::user()->integral,$this->getGiveIntegral(),0);
        \Auth::user()->save();
        //燃烧金币未完成
        set_gold_pool($this->getBurnGold());
    }

    /**
     * @return float
     * @see 燃烧金币
     */
    public function getBurnGold():float
    {
        return burn_gold($this->gold);
    }

    /**
     * @return float
     * @购物实际支付金币
     */
    public function getSumGold():float
    {
        return sum_gold($this->gold,$this->getBurnGold());
    }

    /**
     * @return string
     * @see 赠送积分
     */
    public function getGiveIntegral():int
    {
        return bcmul($this->gold,10,0);
    }



}
