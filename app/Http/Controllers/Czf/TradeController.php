<?php

namespace App\Http\Controllers\Czf;

use App\BuyGold;
use App\Logics\TradeLogic;
use App\Http\Controllers\Controller;

class TradeController extends Controller
{
    /**
     * @see 交易中心
     */
    public function index(BuyGold $buyGold)
    {
//        dd($this->Logic(null)->getGuidancePrice(9));
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
        return redirect()->route("buy_gold");
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
