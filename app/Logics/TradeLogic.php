<?php
/**
 * Created by PhpStorm.
 * User: youxingxiang
 * Date: 2019/7/29
 * Time: 10:47 AM
 */
namespace App\Logics;
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
        if (bccomp($price,0.5) >= 0  && bccomp($price,1) < 0) {
            $fTmp = bcmul($price,0.04);
        }
        // 1<=price<5 上下浮动3%
        else if (bccomp($price,1) >= 0 && bccomp($price,5) < 0) {
            $fTmp = bcmul($price,0.03);
        }
        // 5=<price<10 上下浮动2%
        else if(bccomp($price,5) >= 0 && bccomp($price,10) < 0) {
            $fTmp = bcmul($price,0.02);
        }
        // 10=<price 上下浮动1%
        else if (bccomp($price,10) >= 0) {
            $fTmp = bcmul($price,0.01);
        }
        $aRes['max'] = bcadd($price,$fTmp,2);
        $aRes['min'] = bcsub($price,$fTmp) < 1 ? "1.00" : bcsub($price,$fTmp);
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
            $this->getBuyGoldGoldFlowDetail(0,2,userId(),$this->oBuyGoldDetail->sum_gold,"出售金币")('App\BuyGoldDetail'),
            // 买家增加金币流水
            $this->getBuyGoldGoldFlowDetail(1,3,$this->oBuyGoldDetail->user_id,$this->oBuyGoldDetail->gold,"求购金币")('App\BuyGoldDetail'),
            // 燃烧金币返回流水
            $this->getBuyGoldGoldFlowDetail(0,5,userId(),$this->oBuyGoldDetail->return_burn_gold,"出售金币返回金币池")('App\BuyGoldDetail'),
            // 出售金币彻底燃烧
            $this->getBuyGoldGoldFlowDetail(0,11,userId(),$this->oBuyGoldDetail->true_burn_gold,"出售金币彻底燃烧")('App\BuyGoldDetail'),
            // 卖家消耗积分流水
            $this->getBuyGoldIntegralFlowDetail(2,userId(),$this->oBuyGoldDetail->consume_integral,"出售金币消耗积分")('App\BuyGoldDetail'),

        ]);
        $this->freezeSeller();
    }

    /**
     * @see 冻结卖方
     */
    public function freezeSeller()
    {
        //出售金币的状态 为冻结
        \Auth::user()->status = 2;
        \Auth::user()->save();
    }


    /**
     * @return array
     */
    protected function rules():array
    {
        return [
            "gold" => 'required|integer|min:1',
            "price" => 'required|numeric|min:0.5',
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
     * @param int $id
     * @return bool
     * @判断订单是否是当前用户的
     * @判断订单是否是没有在交易中
     * @撤单后解除冻结
     */
    public function applyCancelOrder(int $id):bool
    {
        $bRes =  DB::transaction(function () use($id) {
            $oBuyGold = $this->model->lockForUpdate()->findOrFail($id);
            if ($oBuyGold->user_id != userId())
                throw ValidationException::withMessages(['user_id' => ['不能操作非本人购买的订单！']]);
            if ($oBuyGold->seller_id)
                throw ValidationException::withMessages(['user_id' => ['当前订单已处于交易中，无法撤销！']]);
            if ($oBuyGold->status != 0)
                throw ValidationException::withMessages(['user_id' => ['当前订单无法撤销！']]);
            // 下架
            $oBuyGold->delete();
            \Auth::user()->status = 1;
            return  \Auth::user()->save();
        });
        return $bRes ?? false;
    }

    /**
     * @param int $id
     * @see 确认收款
     * @订单状态改为1
     * @双方会员用户状态为正常
     */
    public function confirmOrder(int $id):bool
    {
       $bRes =  DB::transaction(function () use($id) {
            $oBuyGold = $this->model->lockForUpdate()->findOrFail($id);
            if (!$oBuyGold->seller)
                throw ValidationException::withMessages(['user_id' => ['卖家已经不存在,无法完成交易，请联系管理员']]);
            if ($oBuyGold->seller_id != userId())
                throw ValidationException::withMessages(['user_id' => ['不能操作非本人购买的订单！']]);
            if (!$oBuyGold->seller_id)
                throw ValidationException::withMessages(['user_id' => ['没有卖家出售，无法确认']]);
            $oBuyGold->status = 1;
            $oBuyGold->is_show = 0;
            $oBuyGold->member->status = 1;
            $oBuyGold->push();
            \Auth::user()->status = 1;
            return  \Auth::user()->save();
        });
        return $bRes ?? false;

    }


}