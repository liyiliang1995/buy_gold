<?php

namespace App\Http\Controllers\Auth;
use App\Member;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\ResetsPasswords;


class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param Request $request
     * @see 重置密码
     */
    public function reset(Request $request,Member $member)
    {

        $request->validate($this->rules(), $this->validationErrorMessages());

        $phone = $request->post('phone');
        $code = $request->post('code');
        $pwd = $request->post('password');
        // 验证码
        if (false == comparisonCode( $code,$phone)) {
            return $this->sendCodeErrResponse($request);
        }

        $oMember = $member->where('phone',$phone)->first();
        $this->resetPassword($oMember,$pwd);
        return redirect($this->redirectPath());

    }

    /**
     * @param $user
     * @param $password
     */
    protected function resetPassword($user, $password)
    {
        $user->password = $password;

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

    /**
     * @return array
     */
    protected function rules()
    {
        return [
            'phone' => 'required|exists:member',
            'password' => 'required|confirmed|min:6',
        ];
    }

    /**
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
            'phone.required' => '手机号码不能为空',
            'phone.exists' => '未找到输入手机号信息！',
            'password.required' => '密码输入不能为空！',
            'password.confirmed' => '两次密码输入不一致！',
            'password.min' => '密码最小6个字符！',
        ];
    }

    /**
     * @param Request $request
     * @param $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return redirect()->back()
            ->withInput($request->only('phone'))
            ->withErrors(['phone' => trans($response)]);
    }


    /**
     * @param Request $request
     */
    public function sendCodeErrResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'code' => ["手机验证码输入不正确！"],
        ]);
    }
}
