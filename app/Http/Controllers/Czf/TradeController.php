<?php

namespace App\Http\Controllers\Czf;

use App\BuyGold;
use App\Logics\TradeLogic;
use App\Http\Controllers\Controller;

class TradeController extends Controller
{
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
     * @param object|null $oModel
     * @return TradeLogic
     */
    public function Logic(object $oModel = null)
    {
        return new TradeLogic($oModel);
    }

}
