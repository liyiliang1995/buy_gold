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
            session()->flash("lock", "当前账户处于冻结状态！");
            throw new CheckMbrException(
                '当前账户处于冻结状态！', $guards, route('member_index')
            );
        }
    }
}
