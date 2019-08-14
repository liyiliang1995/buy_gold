<?php

namespace App\Http\Controllers\Czf;

use App\News;
use App\Member;
use App\HourAvgPrice;
use App\PhoneBuyGold;
use App\AgentRegister;
use App\Logics\MemberLogic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    use \App\Traits\Restful;

    /**
     * MemberController constructor.
     * @see 权限检测
     */

    public function __construct()
    {
        // dd(get_gold_pool());
        $this->middleware(['auth', 'checkmbr'])->except(['sendMsg', 'getUserSet', 'setUser']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @see 会员中心
     */
    public function memberIndex()
    {
        $gold_pool = \Auth::user()->gold_pool;
        $aConfig   = getConfigByType(1);
        $member    = \Auth::guard()->user();
        $is_auto   = \Auth::user()->is_auto;
        $gold_num  = $this->Logic($member)->getNextGoldAttribute();
        $gold_time = $this->Logic($member)->getNextAutoGoldTimeAttribute();
        return view('czf.member', compact('member', 'gold_pool', 'is_auto', 'gold_num', 'gold_time', 'aConfig'));
    }

    public function getAutoGold(Member $member)
    {

        $gold_time = $this->Logic($member)->getNextAutoGoldTimeAttribute();
        if ($gold_time) {
            return $gold_time;
        }
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @see 获取用户设置
     */
    public function getUserSet()
    {
        $member = \Auth::guard()->user();
        return view('czf.userset', compact('member'));
    }


    /**
     * 用户设置
     */
    public function setUser(Request $request, Member $member)
    {
        $aData           = [];
        $aData['name']   = $request->post('name');
        $aData['phone']  = $request->post('phone');
        $aData['phone2'] = $request->post('phone2');
        $aData['wechat'] = $request->post('wechat');
        if (!empty($request->post('pw1'))) {
            $aData['password'] = $request->post('pw1');
        }
        $res = $this->Logic($member)->setUser($aData);
        return $res ? $this->success() : $this->server_error();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @see 发送短信验证码
     */
    public function sendMsg(Request $request, Member $member)
    {
        $to = $request->post('phone');
        if (
            !empty($request->post('is_check'))
            && !$member->isExistsPhone($to)
        ) {
            return $this->notfound_error('未找到当前手机号的信息！请确认手机号是否正确');
        }
        try {
            sendMsg($to);
            return $this->success("发送成功！");
        } catch (\Exception $e) {
            return $this->params_error($e->getMessage());
        }
    }

    /**
     * @see 我的伙伴
     */
    public function myPartner(Member $member)
    {
        $oPartner = $member->where('parent_user_id', userId())->orderBy('id', 'desc')->get();
        $dSum     = array_sum(array_column($oPartner->toArray(), 'gold'));
        $dSum     = bcadd($dSum, 0, 2);
        return view('czf.partner', compact('oPartner', 'dSum'));
    }


    /**
     * @see 代理注册
     */
    public function agentRegister(AgentRegister $agentRegister, Request $request)
    {
        $aParam['user_id']  = userId();
        $aParam['phone']    = $request->post('phone');
        $aParam['password'] = $request->post('password');
        $aParam['name']     = $request->post('name');
        $aParam['code']     = $request->post('code');
        if ($this->Logic($agentRegister)->agentRegisterLogic($aParam)) {
            return $this->success('注册成功');
        } else {
            return $this->params_error('注册失败');
        }
    }

    /**
     *
     * @see 帮助中心
     */
    public function helpCenter(News $news)
    {
        $newslist = $news->where('type', 0)->orderBy('id', 'desc')->get();

        return view('czf.helpcenter', compact('newslist'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @see 通知公告
     */
    public function notificationList(News $news)
    {
        $newslist = $news->where('type', 1)->orderBy('id', 'desc')->get();

        return view('czf.notification', compact('newslist'));
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @see 文章内容
     */
    public function articleContent(int $id, News $news)
    {

        $newscontent = $this->Logic($news)->find($id);

        return view('czf.articlecontent', compact('newscontent'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @see 手机充值中心
     */
    public function phoneCenter(PhoneBuyGold $phoneBuyGold,HourAvgPrice $hourAvgPrice)
    {
        $aParams['_sort'] = 'price,desc';
        $aParams['status'] = 0;
        $aParams['is_show'] = 1;
        $aPhoneBuyGold       = $this->Logic($phoneBuyGold)->query($aParams);
        $avgPrice = $hourAvgPrice->getBestNewAvgPrice();
        return view('czf.phonecenter',compact('aPhoneBuyGold','avgPrice'));
    }

    /**
     * @see 手机挂单
     */
    public function phoneBuyGold(PhoneBuyGold $phoneBuyGold,HourAvgPrice $hourAvgPrice)
    {
        $aParams['sum_price'] = request()->post('money');
        $aParams['price'] = $hourAvgPrice->getBestNewAvgPrice();
        if ($this->Logic($phoneBuyGold)->phoneBuyGold($aParams))
            return redirect(route('phone_center'));
        else
            abort(500);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @see 手机充值列表
     */
    public function phoneRecord()
    {
        return view('czf.phonerecord');
    }

    /**
     * @param int $iType 1获取挂单记录  2获取抢单记录
     * @param PhoneBuyGold $phoneBuyGold
     * @return \Illuminate\Http\JsonResponse
     * @see 获取手机充值订单
     */
    public function ajaxGetPhoneRecord(int $iType,PhoneBuyGold $phoneBuyGold)
    {
        $aParams['_sort'] = "id,desc";
        if ($iType == 1) {
            $aParams['user_id'] = userId();
        } else {
            if ($iType == 2) {
                $aParams['seller_id'] = userId();
            }
        }
        $aData         = $this->Logic($phoneBuyGold)->query($aParams)->toArray();
        if ($aData) {
            return $this->success("请求成功", $aData);
        } else {
            return $this->server_error();
        }

    }

    /**
     * @param int $id
     * @see 手机充值订单
     */
    public function phoneSell(int $id,PhoneBuyGold $phoneBuyGold)
    {
        $oPhoneBuyGold = $this->Logic($phoneBuyGold)->find($id);
        return view('czf.phonesell',compact('oPhoneBuyGold'));
    }

    /**
     * @see 抢单
     */
    public function phoneGrabOrder(int $id,PhoneBuyGold $phoneBuyGold)
    {
        if ($this->Logic($phoneBuyGold)->phoneGrabOrder($id))
        {
            return redirect(route('phone_center'));
        } else {
            abort(500);
        }
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @see 手机充值详情
     */
    public function phoneDetails()
    {
        return view('czf.phonedetails');
    }


    /**
     * @param $type 1 开启 0关闭
     * @see 加入自动领取金币
     */
    public function addAutoGoldMembers(int $type, Member $member)
    {
        $id = userId();
        if (in_array($type, [0, 1])) {
            $this->Logic($member)->addAutoGoldMembers($id, $type);
            return $this->success();
        } else {
            return $this->params_error();
        }

    }

    /**
     * @see 手动获取金币
     */
    public function manualGiveGold(Member $member)
    {
        $this->Logic($member)->manualGiveGold();
        return $this->success();
    }

    /**
     * @see 金币兑换积分
     */
    public function ajaxIntegralToGold(Member $member)
    {
        // 100金币
        $aParams['gold'] = 100;
        // 900积分
        $aParams['integral'] = 900;
        $this->Logic($member)->IntegralToGold($aParams);
        return $this->success("积分兑换成功");
    }

    /**
     * @param MemberLogic $memberLogic
     * @param $oMdel
     * @see 获取逻辑
     */
    public function Logic($oModel)
    {
        return new MemberLogic($oModel);
    }


}
