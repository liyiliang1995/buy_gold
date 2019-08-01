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
        $aBuyGold = $this->Logic($buyGold)->query(['_sort'=>'price,desc']);
        return view('czf.tradecenter',compact('aBuyGold'));
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
     * @param int $Itype
     * 业务类型 1 用户消费 2 用户出售 3 用户求购 4领取金币 5返回金币池 6代理注册扣除 7代理扣除增加 8 15天为登陆扣除
     */
    public function ajaxGetGoldFlow(int $iType,GoldFlow $goldFlow)
    {
        $aParams['_sort'] = "id,desc";
        $aParams['user_id'] = userId();
        $aParams['type'] = $iType;
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
