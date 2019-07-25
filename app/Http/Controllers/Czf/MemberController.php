<?php

namespace App\Http\Controllers\Czf;
use App\Member;
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
        $this->middleware('auth')->except('getUserSet','sendMsg');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @see 获取用户设置
     */
    public function getUserSet()
    {
        return view('czf.userset');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @see 发送短信验证码
     */
    public function sendMsg(Request $request,Member $member)
    {
        $to = $request->post('phone');
        if(
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
    public function myPartner()
    {
        return view('czf.partner');
    }
}
