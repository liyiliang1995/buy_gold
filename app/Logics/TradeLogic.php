<?php
/**
 * Created by PhpStorm.
 * User: youxingxiang
 * Date: 2019/7/29
 * Time: 10:47 AM
 */
namespace App\Logics;
use App\Member;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class TradeLogic extends BaseLogic
{

    protected $oBuyGoldDetail;
    /**
     * @param $price
     * @return array
     * @see 获取指导价格区间 默认价格为0。5
     * 0.5<price<1 上下浮动4%
     * 1<=price<5 上下浮动3%
     * 5=<price<10 上下浮动2%
     * 10=<price 上下浮动1%
     */
    public function getGuidancePrice():array
    {
        $hapModel = new \App\HourAvgPrice;
        $price = $hapModel->getBestNewAvgPrice();
        // 精度2位
        bcscale(2);
        // 0.52<price<1 上下浮动4%
//        if (bccomp($price,0.5) >= 0  && bccomp($price,1) < 0) {
//            $fTmp = bcmul($price,0.04);
//        }
        // 低于2元 上下浮动2%
        if (bccomp($price,1) >= 0 && bccomp($price,2) < 0) {
            $fTmp = bcmul($price,0.02);
        }
        // 2=<price<5 上下浮动1。5%
        else if(bccomp($price,2) >= 0 && bccomp($price,5) < 0) {
            $fTmp = bcmul($price,0.015);
        }
        else if(bccomp($price,5) >= 0 && bccomp($price,10) < 0) {
            $fTmp = bcmul($price,0.01);
        }
        // 10=<price 上下浮动0.5%
        else if (bccomp($price,10) >= 0) {
            $fTmp = bcmul($price,0.005);
        }
        $aRes['max'] = bcadd($price,$fTmp,2);
        $aRes['min'] = bcsub($price,$fTmp,2) < 1 ? "1.00" : bcsub($price,$fTmp,2);
        return $aRes;
    }

    /**
     * @param array $aParams
     * @return float
     * @see 求购金币
     */
    public function buyGold(array $aParams):float
    {
        request()->validate(
            $this->rules(),
            $this->validationErrorMessages()
        );
        $aParams['user_id'] = userId();
        $aParams['sum_price'] = bcmul($aParams['gold'],$aParams['price'],2);
        $bRes = $this->store($aParams);
        return $bRes ?? false;
    }

    /**
     * @param int $id
     * @see 出售金币
     * @step1 出售数量不能超过持有数量的50%
     * @step2 出售金币消耗同等数量的积分 不够不能出售
     * @step3 出售成功燃烧出售数量的5%金币 买家得到2倍数量的能量值
     * @step4 卖家被冻结
     */
    public function sellGold(int $id):bool
    {
        // 首先计算之前让redis 金币池有值 防止最后统计的时候重复统计
        get_gold_pool();
        $bRes = DB::transaction(function () use($id){
            $this->oBuyGoldDetail = $this->model->where("is_show",1)->where('status',0)->lockForUpdate()->findOrFail($id);
            // 验证
            $this->sellGoldvalidate();
            // 流水
            $this->sellGoldflow();
            // 增减
            $this->sellGoldIncreaseAndDecrease();
            // 把出售下架
            $this->oBuyGoldDetail->is_show = 0;
            // 订单卖家
            $this->oBuyGoldDetail->seller_id = userId();
            $bRes = $this->oBuyGoldDetail->save();
            return $bRes;
        });
        return $bRes;
    }

    /**
     * @卖家扣除金币
     * @卖家消耗积分
     * @买家增加金币
     * @买家增加能量
     * @燃烧金币
     */
    public function sellGoldIncreaseAndDecrease()
    {
        \Auth::user()->decrement('gold',$this->oBuyGoldDetail->sum_gold);
        \Auth::user()->decrement('integral',$this->oBuyGoldDetail->consume_integral);
        $this->oBuyGoldDetail->member->increment('gold',$this->oBuyGoldDetail->gold);
        $this->oBuyGoldDetail->member->increment('energy',$this->oBuyGoldDetail->energy);
        set_gold_pool($this->oBuyGoldDetail->return_burn_gold);
    }

    /**
     * @see 消耗金币验证
     */
    public function sellGoldvalidate()
    {
        if (redis_idempotent('',['sellGoldvalidate']) === false)
            throw ValidationException::withMessages(['gold'=>['请勿恶意提交订单,过2秒钟在尝试！']]);
        if (\Auth::user()->checkMemberOneHalfGold($this->oBuyGoldDetail->gold) === false)
            throw ValidationException::withMessages(['gold'=>["出售金币数量不能超过持有数量的50%！"]]);
        if (\Auth::user()->checkMemberIntegral($this->oBuyGoldDetail->gold) === false)
            throw ValidationException::withMessages(['gold'=>["积分不够，出售金币消耗同等数量的积分！"]]);
    }

    /**
     * @return bool
     * @卖家扣除金币流水
     * @买家增加金币流水
     * @卖家消耗积分流水
     * @买家增加能量值流水
     * @燃烧金币流水
     */
    public function sellGoldflow()
    {
        $this->oBuyGoldDetail->buy_gold_details()->saveMany([
            // 买家增加能量值流水
            $this->getBuyGoldEnergyFlowDetail(2, $this->oBuyGoldDetail->user_id, $this->oBuyGoldDetail->energy,'出售金币获取能量')('App\BuyGoldDetail'),
            // 卖家扣除金币流水
            $this->getBuyGoldGoldFlowDetail(0,2,userId(),$this->oBuyGoldDetail->gold,"出售金币")('App\BuyGoldDetail'),
            // 买家增加金币流水
            $this->getBuyGoldGoldFlowDetail(1,3,$this->oBuyGoldDetail->user_id,$this->oBuyGoldDetail->gold,"求购金币")('App\BuyGoldDetail'),
            // 燃烧金币返回流水
            $this->getBuyGoldGoldFlowDetail(0,5,userId(),$this->oBuyGoldDetail->return_burn_gold,"出售金币返回金币池")('App\BuyGoldDetail'),
            // 出售金币彻底燃烧
            $this->getBuyGoldGoldFlowDetail(0,11,userId(),$this->oBuyGoldDetail->true_burn_gold,"出售金币彻底燃烧")('App\BuyGoldDetail'),
            // 卖家消耗积分流水
            $this->getBuyGoldIntegralFlowDetail(2,userId(),$this->oBuyGoldDetail->consume_integral,"出售金币消耗积分")('App\BuyGoldDetail'),

        ]);
        freeze_member(userId(),2);
    }

    /**
     * @param object $oOrder
     */
    public function confirmOrderValidate(object $oOrder)
    {
        if (!$oOrder->seller)
            throw ValidationException::withMessages(['user_id' => ['卖家已经不存在,无法完成交易，请联系管理员']]);
        if ($oOrder->seller_id != userId())
            throw ValidationException::withMessages(['user_id' => ['不能操作非本人购买的订单！']]);
        if (!$oOrder->seller_id)
            throw ValidationException::withMessages(['user_id' => ['没有卖家出售，无法确认']]);
    }


    /**
     * @return array
     */
    protected function rules():array
    {
        return [
            "gold" => 'required|integer|min:1',
            "price" => 'required|numeric|min:1',
        ];
    }

    /**
     * @return array
     */
    protected function validationErrorMessages():array
    {
        return [
            'gold.required' => '购买数量不能为空',
            'gold.integer' => '购买数量必须是个整数！',
            'gold.min' => '购买数量最小值不能小于1！',
            'price.required' => '购买价格不能为空',
            'price.float' => '购买价格必须是个数字！',
            'price.min' => '购买价格最小值不能小于1！'
        ];
    }

    /**
     * @param array $aParams
     * @return array
     * @see 前端支出显示页面  金币燃烧和返回扣点 统一为一条数据为金币燃烧
     */
    public function  ajaxGetGoldFlow(array $aParams):array
    {
        $aData                = $this->query($aParams)->toArray();
        $aDataLast = Arr::last($aData['data']);
        $aDataFirst =Arr::first($aData['data']);
        // 分页最后一条为金币燃烧
        if (!empty($aDataLast['type']) && $aDataLast['type'] == 11) {
            $this->model->query_page += 1;
            $aData = $this->query($aParams)->toArray();
        }
        // 分页第一条为扣点返回金币池
        if (!empty($aDataFirst['type']) && $aDataFirst['type'] == 5) {
            $aData['data'] = Arr::except($aData['data'],[0]);
        }
        $aTmp = $aData['data'];
        $aTmp2 = [];
        foreach ($aTmp as $key => $value) {
            if ($value['type'] == 11)
                continue;
            else if ($value['type'] == 5) {
                $aTmp[$key - 1]['gold'] = bcadd($aTmp[$key]['gold'],$aTmp[$key - 1]['gold'],2);
                $aTmp2[] = $aTmp[$key - 1];
            }
            else
                $aTmp2[] = $value;
        }
        $aData['data'] = $aTmp2;
        return $aData ?? [];
    }


}