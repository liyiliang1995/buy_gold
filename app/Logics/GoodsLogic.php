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
     * @var
     */
    protected $flows;
    /**
     * @var
     */
    protected $in_tmp_pool;

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
     * @购物后赠送10倍积分  改为可变
     * @至少激活一个用户才可以购物
     * @扣除商品价格5%返回金币池
     */
    public function orderSave(array $aParams):bool
    {
        // 首先计算之前让redis 金币池有值 防止最后统计的时候重复统计
        get_gold_pool();
        // 商品详情
        $this->goods_detail = $this->model->findOrFail($aParams['goods_id']);
        // 购物需要的金币
        $this->gold = $this->goods_detail->amountToGold($aParams['num'],$aParams['gold_price']);
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
        if (empty($aParams['gold_price']) || $aParams['gold_price'] < 1)
            throw new CzfException("操作异常,购买价格不正常！");
        if (\Auth::user()->getChildMemberNum() < 1)
            throw new CzfException("至少激活一个用户才可以购物!");
        if (\Auth::user()->checkMemberOneHalfGold($this->gold) === false)
            throw new CzfException("购物金币数量不能超过持有数量的50%!");
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
        $this->flows = $this->getBaseflow();
        $this->stockholderShareGold();
        $this->order_model->order_details()->saveMany(
            array_map(function ($val){return $val('App\OrderDetail');},$this->flows)
        );
    }

    /**
     * @see 设置流水
     */
    public function getBaseflow():array
    {
        return [
            // 购物扣除
            $this->getBuyGoldGoldFlowDetail(0,1,userId(),$this->gold,"购物消耗金币"),
            // 燃烧金币返回金币池
            $this->getBuyGoldGoldFlowDetail(0,5,userId(),$this->getReturnBurnGold(),"购物金币燃烧返回金币池"),
            // 彻底燃烧
            $this->getBuyGoldGoldFlowDetail(0,11,userId(),$this->getTrueBurnGold(),"购物金币彻底燃烧"),
            // 赠送10倍积分
            $this->getBuyGoldIntegralFlowDetail(1,userId(),$this->getGiveIntegral(),"购物赠送积分"),
        ];
    }

    /**
     * @see 股东流水分配
     */
    public function stockholderShareGold()
    {
        $oMember = new Member;
        $aStockholder = $oMember->where('is_admin' ,1)->where('rate','>',0)->get();
        $this->in_tmp_pool = $this->gold;
        // 是否分配了股东分成
        if(count($aStockholder) > 0) {
            foreach ($aStockholder as $item) {
                $fStockholderGold  = $this->getGoldByRate($item->rate);
                $this->in_tmp_pool -= $fStockholderGold;
                // 股东增加金币
                $item->increment('gold',$fStockholderGold);
                // 股东奖励
                $this->flows[] = $this->getBuyGoldGoldFlowDetail(1,13,$item['id'],$fStockholderGold,"用户购物股东获得奖励");
            }
            // 购物金币流向金币池 0代表系统 这个操作归属用户为系统
            $this->flows[] = $this->getBuyGoldGoldFlowDetail(1,12,0,$this->in_tmp_pool,"购物金币流向金币池");
        }
        // 没有股东金币全部流入币池
        else {
            // 购物金币流向金币池 0代表系统 这个操作归属用户为系统
            $this->flows[] = $this->getBuyGoldGoldFlowDetail(1,12,0,$this->gold,"购物金币流向金币池");
        }
    }

    /**
     * @return float
     * @see 按比例获取金额
     */
    public function getGoldByRate(float $fRate):float
    {
        $fTrueRate = bcmul(config('czf.stockholders_rate'),$fRate,2);
        $fTmp = bcmul($this->gold,$fTrueRate,2);
        $fStockholderGold = bcdiv($fTmp,100,2);
        return $fStockholderGold ?? 0.00;
    }

    /**
     * @用户扣除金币
     * @用户增加积分
     * @金币池增加积分
     */
    public function orderIncreaseAndDecrease()
    {
        // 扣除
        \Auth::user()->decrement("gold",$this->getSumGold());
        // 增加积分
        \Auth::user()->increment('integral',$this->getGiveIntegral());
        //燃烧金币未完成
        set_gold_pool(bcadd($this->in_tmp_pool,$this->getReturnBurnGold(),2));
    }

    /**
     * @return float
     * @see 燃烧金币总计
     */
    public function getBurnGold():float
    {
        return bcadd($this->getTrueBurnGold(),$this->getReturnBurnGold(),2);
    }

    /**
     * @return float
     * @see 真正燃烧金币
     */
    public function getTrueBurnGold():float
    {
        return burn_gold($this->gold,0.01);
    }

    /**
     * @return float
     * @see 燃烧返回金币池
     */
    public function getReturnBurnGold():float
    {
        return burn_gold($this->gold,0.04);
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
        $rate = (int)(getConfigByType(1)['integral_rate'] ?? 10);
        return bcmul($this->gold,$rate,0);
    }



}
