<?php

namespace App\Http\Controllers\Czf;
use App\Good;
use App\Member;
use App\HourAvgPrice;
use App\Logics\GoodsLogic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    use \App\Traits\Restful;
    /**
     * GoodsController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth','checkmbr'])->except(['getEditAddress','postEditAddress']);
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
    public function confirmOrder(int $goodsId,Good $good,HourAvgPrice $hourAvgPrice)
    {
        $bRes = $this->Logic($good)->userHasExistsAddress();
        if (!$bRes) {
            return redirect(route('getEditAddress',['url'=>url()->full()]));
        }
        $oGoods = $good->findOrFail($goodsId);
        $hour_avg_price = $hourAvgPrice->getBestNewAvgPrice();
        $oUser = \Auth::user();
        return view('czf.confirmorder',compact('oGoods','oUser','hour_avg_price'));
    }

    /**
     * @提交订单逻辑处理
     */
    public function postOrderSave(int $goodsId,Good $good)
    {
        $aParams['num'] = request()->input('num');
        $aParams['other'] = request()->input('other');
        $aParams['goods_id'] = $goodsId;
        $aParams['gold_price'] = request()->input('glod_price');
        if ($this->Logic($good)->orderSave($aParams))
            return $this->success("",["url"=>route("home")]);
        else
            return $this->server_error();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEditAddress()
    {
        $oUser = \Auth::user();
        return view('czf.editaddress',compact('oUser'));
    }

    /**
     * @see 修改地址
     */
    public function postEditAddress(Member $member)
    {
        $sUrl = request()->input('url');
        $aParam['address1'] = request()->post('address1');
        $aParam['address2'] = request()->post('address2');
        $aParam['name'] = request()->post('name');
        $aParam['phone'] = request()->post('phone');
        $bRes = $this->Logic($member)->editShipAddress($aParam);
        if ($bRes) {
            return  $sUrl ? redirect($sUrl) : redirect(route('home'));
        } else {
            abort(500);
        }

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
