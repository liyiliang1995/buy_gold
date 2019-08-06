<?php

namespace App\Http\Controllers\Czf;

use App\BuyGold;
use App\GoldFlow;
use App\EnergyFlow;
use App\HourAvgPrice;
use App\IntegralFlow;
use App\DayBuyGoldSum;
use App\Logics\TradeLogic;
use App\Http\Controllers\Controller;

class TradeController extends Controller
{
    use \App\Traits\Restful;

    public function __construct()
    {
        $this->middleware(['auth', 'checkmbr']);
    }

    /**
     * @see 交易中心
     */
    public function index(BuyGold $buyGold,HourAvgPrice $hourAvgPrice)
    {
        $aBuyGold       = $this->Logic($buyGold)->query(['_sort' => 'price,desc']);
        $fGuidancePrice = $this->Logic(null)->getGuidancePrice();
        $avgPrice = $hourAvgPrice->getBestNewAvgPrice();
        return view('czf.tradecenter', compact('aBuyGold', 'fGuidancePrice','avgPrice'));
    }

    /**
     * @see 求购金币
     */
    public function buyGold(BuyGold $buyGold)
    {
        $aParams['gold']  = request()->post('gold');
        $aParams['price'] = round(request()->post('price'), 2);
        $this->Logic($buyGold)->buyGold($aParams);
        return redirect()->route("trade_record",['show'=>3]);
    }

    /**
     * @param BuyGold $buyGold
     * @see 出售金币
     */
    public function sellGold(int $id, BuyGold $buyGold)
    {
        $oBuyGoldDetail = $this->Logic($buyGold)->find($id);
        return view('czf.sellgold', compact('oBuyGoldDetail'));
    }

    /**
     * @param BuyGold $buyGold
     * @see 求购详情
     */

    public function orderGoldDetail(int $id, BuyGold $buyGold)
    {
        // 取消是上架 订单未确认才显示
        $buyGold->setParentFlag([]);
        $oOrderGold = $this->Logic($buyGold)->find($id);
        return view('czf.ordergolddetail', compact('oOrderGold'));

    }


    /**
     * @param int $id
     * @param BuyGold $buyGold
     */
    public function sellGoldOrder(int $id, BuyGold $buyGold)
    {
        if ($this->Logic($buyGold)->sellGold($id)) {
            return redirect(route('trade_record',['show'=>2]));
        } else {
            abort(500);
        }
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
     * @see 积分记录
     */
    public function integralRecord()
    {
        return view('czf.integralrecord');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @see 金币记录
     */
    public function goldRecord()
    {


        return view('czf.goldrecored');
    }

    /**
     * @param int $iType 1 求购 2售出
     * @param BuyGold $buyGold
     * @see金币求购订单
     */
    public function ajaxGetBuyGoldType(int $iType, BuyGold $buyGold)
    {
        $aParams['_sort'] = "id,desc";
        if ($iType == 1) {
            $aParams['user_id'] = userId();
        } else {
            if ($iType == 2) {
                $aParams['seller_id'] = userId();
            }
        }
        // 取消是上架 订单未确认才显示
        $buyGold->setParentFlag([]);
        $aData         = $this->Logic($buyGold)->query($aParams)->toArray();
        $aData['type'] = $iType;
        if ($aData) {
            return $this->success("请求成功", $aData);
        } else {
            return $this->server_error();
        }

    }

    /**
     * @param int $Itype
     * @param int $iType 金币流水 支出还是收入 0 支出 1收入
     */
    public function ajaxGetGoldFlow(int $iType, GoldFlow $goldFlow)
    {
        $aParams['_sort']     = "id,desc";
        $aParams['user_id']   = userId();
        $aParams['is_income'] = $iType;
        $aData                = $this->Logic($goldFlow)->query($aParams)->toArray();
        if ($aData) {
            return $this->success("请求成功", $aData);
        } else {
            return $this->server_error();
        }
    }

    /**
     * @param int 业务类型 1收入 2 支出'
     * @param IntegralFlow $integralFlow
     * @see 获取积分流水
     */
    public function ajaxGetIntegralFlow(int $iType, IntegralFlow $integralFlow)
    {
        $aParams['_sort']   = "id,desc";
        $aParams['user_id'] = userId();
        $aParams['type']    = $iType;
        $aData              = $this->Logic($integralFlow)->query($aParams)->toArray();
        if ($aData) {
            return $this->success("请求成功", $aData);
        } else {
            return $this->server_error();
        }
    }

    /**
     * @param int $iType 业务类型 1 自动领取金币消耗 2求购金币获得
     * @param EnergyFlow $energyFlow
     * @return \Illuminate\Http\JsonResponse
     * @see 获取能量值流水
     */
    public function ajaxGetEnergyFlow(int $iType, EnergyFlow $energyFlow)
    {
        $aParams['_sort']   = "id,desc";
        $aParams['user_id'] = userId();
        $aParams['type']    = $iType;
        $aData              = $this->Logic($energyFlow)->query($aParams)->toArray();
        if ($aData) {
            return $this->success("请求成功", $aData);
        } else {
            return $this->server_error();
        }
    }

    /**
     * @see 申请撤单
     */
    public function applyCancelOrder(int $id,BuyGold $buyGold)
    {
        $this->Logic($buyGold)->applyCancelOrder($id);
        return redirect(route('trade_record',['show'=>3]));
    }

    /**
     * @param int $id
     * @see 确认收款
     */
    public function confirmOrder(int $id,BuyGold $buyGold)
    {
        $this->Logic($buyGold)->confirmOrder($id);
        return redirect(route('trade_record',['show'=>2]));
    }

    /**
     * @获取金币池剩余金币数量
     */
    public function ajaxGetGoldPool()
    {
        $aData = gold_compute();
        $aData['test'] = array_sum($aData);
        $aData['redis'] = get_gold_pool();
        return $this->success('',$aData);
    }

    /**
     * @see ajax 获取15天均价走势
     */
    public function ajaxGetTrend(DayBuyGoldSum $dayBuyGoldSum)
    {
        $aParams['_sort']   = "id,asc";
        $aRes = $this->Logic($dayBuyGoldSum)->query($aParams)->toArray();
        foreach ($aRes['data'] as $key => $vg){
            $adata['adata'][$key] = $vg['day'];
            $adata['bdata'][$key] = $vg['avg_price'];
        }
        return $this->success('',$adata);
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
