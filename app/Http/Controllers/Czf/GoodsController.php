<?php

namespace App\Http\Controllers\Czf;
use App\Good;
use App\Logics\GoodsLogic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    /**
     * GoodsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @see 商品详情
     */
    public function goodsDetail(int $id,Good $good)
    {
        $oGoods = $good->findOrFail($id);
        return view('czf.goodsdetail',compact('oGoods'));
    }

    /**
     * @see 确认订单
     */
    public function confirmOrder(int $goodsId,Good $good)
    {
        $bRes = $this->Logic($good)->userHasExistsAddress();
        if (!$bRes)
            return redirect(route('getEditAddress'));
        $oGoods = $good->findOrFail($goodsId);
        $oUser = \Auth::user();
        return view('czf.confirmorder',compact('oGoods','oUser'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEditAddress()
    {
        return view('czf.editaddress');
    }

    /**
     * @see 修改地址
     */
    public function postEditAddress(Good $good)
    {
        $aParam['address1'] = request()->post('address1');
        $aParam['address2'] = request()->post('address2');
        $this->Logic($good)->editShipAddress($aParam);
    }
    /**
     * @param MemberLogic $memberLogic
     * @param $oMdel
     * @see 获取逻辑
     */
    public function Logic(object $oModel)
    {
        return new GoodsLogic($oModel);
    }


}
