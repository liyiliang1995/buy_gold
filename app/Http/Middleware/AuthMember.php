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
        if (in_array(\Auth::user()->status,[2,3,4])) {
            session()->flash("lock", "请检查是否自己有未完成的交易订单,或者您的代理用户有未确认收款订单导致您冻结");
            throw new CheckMbrException(
                '请检查是否自己有未完成的交易订单,或者您的代理用户有未确认收款订单导致您冻结！', $guards, route('member_index')
            );
        }
    }
}
