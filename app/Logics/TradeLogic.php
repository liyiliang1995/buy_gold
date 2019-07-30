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
    public function sellGold(int $id)
    {
        DB::transaction(function () use($id){
            $this->oBuyGoldDetail = $this->model->lockForUpdate()->findOrFail($id);
            $this->sellGoldvalidate();
            $this->sellGoldflow();
        });

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
     */
    public function sellGoldflow()
    {
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
            'price.min' => '购买价格最小值不能小于0.5！'
        ];
    }


}