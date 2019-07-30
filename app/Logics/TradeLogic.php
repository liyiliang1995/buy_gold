<?php
/**
 * Created by PhpStorm.
 * User: youxingxiang
 * Date: 2019/7/29
 * Time: 10:47 AM
 */
namespace App\Logics;
use App\BuyGoldDetail;
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
    public function getGuidancePrice(float $price = 0.50):array
    {
        // 精度2位
        bcscale(2);
        if (bccomp($price,0.5) >= 0 && bccomp($price,0.52) < 0) {
            $fTmp = bcmul($price,0.04);
            $min =  bcadd($price,0);
        }
        // 0.52<price<1 上下浮动4%
        if (bccomp($price,0.52) >= 0  && bccomp($price,1) < 0) {
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
        $aRes['min'] = $min ?? bcsub($price,$fTmp);
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
        \Auth::user()->gold = bcsub(\Auth::user()->gold,$this->oBuyGoldDetail->sum_gold,2);
        \Auth::user()->integral = bcsub(\Auth::user()->integral,$this->oBuyGoldDetail->consume_integral,0);
        \Auth::user()->save();
        $this->oBuyGoldDetail->member->gold = bcadd($this->oBuyGoldDetail->member->gold,$this->oBuyGoldDetail->gold,2);
        $this->oBuyGoldDetail->member->energy = bcadd($this->oBuyGoldDetail->member->energy,$this->oBuyGoldDetail->energy,0);
        $this->oBuyGoldDetail->member->save();
        // 燃烧金币未完成
    }

    /**
     * @see 消耗金币验证
     */
    public function sellGoldvalidate()
    {
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
            $this->getBuyGoldEnergyFlowDetail(2, $this->oBuyGoldDetail->user_id, $this->oBuyGoldDetail->energy,'出售金币获取能量'),
            // 卖家扣除金币流水
            $this->getBuyGoldGoldFlowDetail(0,2,userId(),$this->oBuyGoldDetail->sum_gold,"出售金币"),
            // 买家增加金币流水
            $this->getBuyGoldGoldFlowDetail(1,3,$this->oBuyGoldDetail->user_id,$this->oBuyGoldDetail->gold,"求购金币"),
            // 燃烧金币流水
            $this->getBuyGoldGoldFlowDetail(0,5,userId(),$this->oBuyGoldDetail->burn_gold,"燃烧金币"),
            // 卖家消耗积分流水
            $this->getBuyGoldIntegralFlowDetail(2,userId(),$this->oBuyGoldDetail->consume_integral,"出售金币消耗积分"),

        ]);
        $this->freezeSeller();
    }

    /**
     * @param int $iType 1 自动领取金币消耗 2求购金币获得
     * @param int $iUserId
     * @param int $iEnergy 能量值
     * @return BuyGoldDetail
     * @能量流水 type=3 代表能量
     */
    public function getBuyGoldEnergyFlowDetail(int $iType,int $iUserId,int $iEnergy,string $sOther = ''):object
    {
        $oEnergyModel = new \App\EnergyFlow([
            // 业务类型 1 自动领取金币消耗 2求购金币获得
            'type' => $iType,
            'energy' => $iEnergy,
            'user_id' => $iUserId,
            'other' => $sOther,
        ]);
        $oEnergyModel->save();
        $oModel  = new BuyGoldDetail([
            // 流水单号类型 1金币流水 2积分流水 3能量流水
            'type'=> 3,
            'flow_id' => $oEnergyModel->id,
        ]);
        return $oModel;
    }

    /**
     * @param  int $iIsIncome 是否收入 0 支出 1收入
     * @param int $iType 业务类型 1 用户消费 2 用户出售 3 用户求购 4领取金币 5返回金币池 6代理注册扣除 7代理扣除增加 8 15天为登陆扣除
     * @param int $iUserId
     * @param float $fGold
     * @return object
     */
    public function getBuyGoldGoldFlowDetail(int $iIsIncome,int $iType,int $iUserId,float $fGold,string $sOther = ''):object
    {
        $oGoldModel = new \App\GoldFlow([
            // $iType 业务类型 1 用户消费 2 用户出售 3 用户求购 4领取金币 5返回金币池 6代理注册扣除 7代理扣除增加 8 15天为登陆扣除
            'type' => $iType,
            'gold' => $fGold,
            'user_id' => $iUserId,
            'is_income' => $iIsIncome,
            'other' => $sOther,
        ]);
        $oGoldModel->save();
        $oModel  = new BuyGoldDetail([
            // 流水单号类型 1金币流水 2积分流水 3能量流水
            'type'=> 1,
            'flow_id' => $oGoldModel->id,
        ]);
        return $oModel;


    }

    /**
     * @param int $iType 业务类型 1消费获得 2 出售金币消耗
     * @param int $iUserId
     * @param int $iIntegral 积分值
     * @param string $sOther
     * @return object
     */
    public function getBuyGoldIntegralFlowDetail(int $iType,int $iUserId,int $iIntegral,string $sOther = ''):object
    {
        $oIntegralModel = new \App\IntegralFlow([
            // 业务类型 1消费获得 2 出售金币消耗
            'type' => $iType,
            'integral' => $iIntegral,
            'user_id' => $iUserId,
            'other' => $sOther,
        ]);
        $oIntegralModel->save();
        $oModel  = new BuyGoldDetail([
            // 流水单号类型 1金币流水 2积分流水 3能量流水
            'type'=> 2,
            'flow_id' => $oIntegralModel->id,
        ]);
        return $oModel;
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
            'price.min' => '购买价格最小值不能小于0.5！'
        ];
    }


}