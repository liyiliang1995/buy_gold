<?php

namespace App;

use App\Exceptions\CzfException;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Member extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword,SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'member';
    /**
     * @var array
     */
    protected $fillable = ['name', 'password', 'phone', 'parent_user_id'];

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
    public function isExistsPhone(string $phone): int
    {
        return $this->where('phone', $phone)->count() ?? 0;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @see 代理注册一对一
     */
    public function agent_register()
    {
        return $this->hasOne('App\AgentRegister', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ship_address()
    {
        return $this->hasOne('App\MemberShipAddress', 'member_id');
    }

    /**
     * @param array $aData
     * @see 增加下属会员
     */
    public function addChildMember(array $aData)
    {
        $aData['parent_user_id'] = userId();
        $member = $this->create($aData);
        $this->addChildUserNum();
        return $member;
    }

    /**
     * 增加数量
     */
    public function addChildUserNum(int $iNum = 1)
    {
        $this->find(userId())->increment('child_user_num', $iNum);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function buy_gold()
    {
        return $this->hasOne('App\BuyGold','user_id');
    }
    /**
     * @param array $aData
     * @插入之前的钩子
     */
    public function beforeInsert(array $aData)
    {

    }

    /**
     * @param object $oData
     * @更新
     */
    public function beforeUpdate(object $oData)
    {

    }

    /**
     * @param array $aData
     * @验证
     */
    public function userVerify(array $aData)
    {
        phoneVerif($aData['phone1']);
    }


    /**
     * @return string
     */
    public function getStatAttribute(): string
    {
        if ($this->status == 0) {
            $sRes = '<a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_primary" style="background: #9E9E9E">未注册</a>';
        } else {
            if ($this->status == 1) {
                $sRes = '<a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_primary" style="background: #07C160">正常</a>';
            } else {
                $sRes = '<a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_primary" style="background: #F44336">锁定</a>';
            }
        }
        return $sRes;
    }

    /**
     * @return bool
     * @see 查看用户状态是否正常
     */
    public function isNormalMember():bool
    {
        return $this->status == 1;
    }

    /**
     * @param int $iGold 出售金币数量
     * @return bool
     * @see 检测出售数量是否超过持有数量的50%
     */
    public function checkMemberOneHalfGold(float $fGold):bool
    {
        $fHaveGold = bcmul($this->gold,0.5,2);
        return $fHaveGold > $fGold;
    }

    /**
     * @param float $fGold
     * @return bool
     * @see 检测出售金币是否含有同等数量的金币
     */
    public function checkMemberIntegral(float $fGold):bool
    {
        return $this->integral > $fGold;
    }

    /**
     * @return int
     * @查看用户代理注册了几个用户
     */
    public function getChildMemberNum():int
    {
        return $this->child_user_num ?? 0;
    }

    /**
     * @return float
     */
    public function getAllMemberGold():float
    {
        return $this->sum('gold') ?? 0.00;
    }


}
