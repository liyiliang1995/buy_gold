<?php

namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Member extends Model implements AuthenticatableContract,CanResetPasswordContract
{
    use Authenticatable,CanResetPassword;
    /**
     * @var string
     */
    protected $table = 'member';
    /**
     * @var array
     */
    protected $fillable = ['name','password','phone','parent_user_id'];

    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * @param string $phone
     * @return int
     * @see 检测手机号码是否存在
     */
    public function isExistsPhone(string $phone):int
    {
        return $this->where('phone',$phone)->count() ?? 0;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @see 代理注册一对一
     */
    public function agent_register()
    {
        return $this->hasOne('App\AgentRegister','user_id');
    }

    /**
     * @param array $aData
     * @see 增加下属会员
     */
    public function addChildMember(array $aData)
    {
        $aData['parent_user_id'] = userId();
        $this->create($aData);
        $this->addChildUserNum();
    }

    /**
     * 增加数量
     */
    public function addChildUserNum(int $iNum = 1)
    {
        $this->find(userId())->increment('child_user_num',$iNum);
    }

    /**
     * @param array $aData
     * @插入之前的钩子
     */
    public function beforeInsert(array $aData)
    {
        dd($aData);
    }

    /**
     * @return string
     */
    public function getStatAttribute():string
    {
        if ($this->status == 0)
            $sRes = '<a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_primary" style="background: #9E9E9E">未注册</a>';
        else if($this->status == 1)
            $sRes = '<a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_primary" style="background: #07C160">正常</a>';
        else
            $sRes = '<a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_primary" style="background: #F44336">锁定</a>';
        return $sRes;
    }
}
