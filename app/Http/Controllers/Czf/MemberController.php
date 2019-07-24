<?php

namespace App\Http\Controllers\Czf;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    use \App\Traits\Restful;


    public function getUserSet()
    {
        return view('czf.userset');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @see 发送短信验证码
     */
    public function sendMsg()
    {
        $to = request()->post('phone');
        try {
            sendMsg($to);
            return $this->success("发送成功！");
        } catch (\Exception $e) {
            return $this->params_error($e->getMessage());
        }

    }
}
