<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\CheckMbrException;

class CheckMember
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
        $this->isActivate($guards,$guards);
        return $next($request);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function isActivate($request,$guards)
    {
        if (\Auth::user()->status == 0) {
            throw new CheckMbrException(
                '请先完善用户信息', $guards, route('userset',['url'=>url()->full()])
            );
        }
    }
}
