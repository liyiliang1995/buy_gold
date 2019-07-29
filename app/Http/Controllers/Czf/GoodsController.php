<?php

namespace App\Http\Controllers\Czf;
use App\Good;
use App\Member;
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
    public function confirmOrder(int $goodsId,Good $good)
    {
        $bRes = $this->Logic($good)->userHasExistsAddress();
        if (!$bRes) {
            return redirect(route('getEditAddress',['url'=>url()->full()]));
        }
        $oGoods = $good->findOrFail($goodsId);
        $oUser = \Auth::user();
        return view('czf.confirmorder',compact('oGoods','oUser'));
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
