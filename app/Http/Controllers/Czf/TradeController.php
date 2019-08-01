<?php

namespace App\Http\Controllers\Czf;

use App\BuyGold;
use App\GoldFlow;
use App\EnergyFlow;
use App\IntegralFlow;
use App\Logics\TradeLogic;
use App\Http\Controllers\Controller;

class TradeController extends Controller
{
    use \App\Traits\Restful;

    public function __construct()
    {
        $this->middleware(['auth','checkmbr']);
    }
    /**
     * @see 交易中心
     */
    public function index(BuyGold $buyGold)
    {
        dd($this->Logic(null)->getGoldPoolNum());
        $aBuyGold = $this->Logic($buyGold)->query(['_sort'=>'price,desc']);
        $fGuidancePrice = $this->Logic(null)->getGuidancePrice();
        return view('czf.tradecenter',compact('aBuyGold','fGuidancePrice'));
    }

    /**
     * @see 求购金币
     */
    public function buyGold(BuyGold $buyGold)
    {
        $aParams['gold'] = request()->post('gold');
        $aParams['price'] = round(request()->post('price'),2);
        $this->Logic($buyGold)->buyGold($aParams);
        return redirect()->route("trade_center");
    }

    /**
     * @param BuyGold $buyGold
     * @see 出售金币
     */
    public function sellGold(int $id,BuyGold $buyGold)
    {
        $oBuyGoldDetail = $this->Logic($buyGold)->find($id);
        return view('czf.sellgold',compact('oBuyGoldDetail'));
    }

    /**
     * @param int $id
     * @param BuyGold $buyGold
     */
    public function sellGoldOrder(int $id,BuyGold $buyGold)
    {
        if ($this->Logic($buyGold)->sellGold($id))
            return redirect(route('trade_center'));
        else
            abort(500);
    }

    /**
     * @see 交易记录
     */
    public function tradeRecord()
    {
        return view('czf.traderecord');
    }

    /**
     * @see 能量记录
     */
    public function energyRecord()
    {
        return view('czf.energyrecord');
    }



    /**
     * @param int $iType 1 求购 2售出
     * @param BuyGold $buyGold
     * @see金币求购订单
     */
    public function ajaxGetBuyGoldType(int $iType,BuyGold $buyGold)
    {
        $aParams['_sort'] = "id,desc";
        if ($iType == 1)
            $aParams['user_id'] = userId();
        else if ($iType == 2)
            $aParams['seller_id'] = userId();
        // 取消是上架 订单未确认才显示
        $buyGold->setParentFlag([]);
        $aData = $this->Logic($buyGold)->query($aParams)->toArray();
        $aData['type'] = $iType;
        if ($aData ){
            return $this->success("请求成功",$aData );
        }
        else
            return $this->server_error();

    }
    /**
     * @param int $Itype
     * @param int $iType   金币流水 支出还是收入 0 支出 1收入
     */
    public function ajaxGetGoldFlow(int $iType,GoldFlow $goldFlow)
    {
        $aParams['_sort'] = "id,desc";
        $aParams['user_id'] = userId();
        $aParams['is_income'] = $iType;
        $aData = $this->Logic($goldFlow)->query($aParams)->toArray();
        if ($aData ){
            return $this->success("请求成功",$aData );
        }
        else
            return $this->server_error();
    }

    /**
     * @param int 业务类型 1收入 2 支出'
     * @param IntegralFlow $integralFlow
     * @see 获取积分流水
     */
    public function ajaxGetIntegralFlow(int $iType,IntegralFlow $integralFlow)
    {
        $aParams['_sort'] = "id,desc";
        $aParams['user_id'] = userId();
        $aParams['type'] = $iType;
        $aData = $this->Logic($integralFlow)->query($aParams)->toArray();
        if ($aData) {
            return $this->success("请求成功", $aData);
        } else
            return $this->server_error();
    }

    /**
     * @param int $iType  业务类型 1 自动领取金币消耗 2求购金币获得
     * @param EnergyFlow $energyFlow
     * @return \Illuminate\Http\JsonResponse
     * @see 获取能量值流水
     */
    public function ajaxGetEnergyFlow(int $iType,EnergyFlow $energyFlow)
    {
        $aParams['_sort'] = "id,desc";
        $aParams['user_id'] = userId();
        $aParams['type'] = $iType;
        $aData = $this->Logic($energyFlow)->query($aParams)->toArray();
        if ($aData) {
            return $this->success("请求成功", $aData);
        } else
            return $this->server_error();
    }

    /**
     * @param object|null $oModel
     * @return TradeLogic
     */
    public function Logic(object $oModel = null)
    {
        return new TradeLogic($oModel);
    }

}
