<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\CheckMbrException;

class AuthMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,...$guards)
    {
        $this->isMemberLock($guards);
        return $next($request);
    }

    /**
     * @see 用户是否处于交易被锁定
     */
    public function isMemberLock($guards)
    {
        if (\Auth::user()->status == 2 || \Auth::user()->status == 3) {
            session()->flash("lock", "还有订单交易未完成！无法继续交易");
            throw new CheckMbrException(
                '还有订单交易未完成！无法继续交易', $guards, route('trade_record', ['show' => \Auth::user()->status])
            );
        } else if (\Auth::user()->status == 4) {
            session()->flash("lock", "检测是否有未完成订单或者下级用户超过24小时未确认收款");
            throw new CheckMbrException(
                '检测是否有未完成订单或者下级用户超过24小时未确认收款！', $guards, route('myPartner')
            );
        }
    }
}
