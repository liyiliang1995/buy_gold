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
    protected $fillable = ['name', 'password', 'phone', 'parent_user_id','status'];

    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) :$value;
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

    public function parentuser()
    {
        return $this->hasOne('App\Member', 'id','parent_user_id');
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
        $this->where('id',userId())->increment('child_user_num', $iNum);
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
            $sRes = '<a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_primary" style="background: #9E9E9E">待激活</a>';
        } else {
            if ($this->status == 1) {
                $sRes = '<a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_primary" style="background: #07C160">正常</a>';
            } else {
                $sRes = '<a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_primary" style="background: #F44336">冻结</a>';
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
        // 在用户手上的
        $all_gold = $this->sum('gold') ?? 0.00;
        // 用户挂单未成交的
        $phone_gold  =  (new PhoneBuyGold)->getNotclinchGold();
        return bcadd($all_gold,$phone_gold,2);
    }

    /**
     * @return float
     * @see 所有能量值
     */
    public function getAllMemberEnergy():float
    {
        return $this->sum('energy') ?? 0.00;
    }

    /**
     * @return float
     */
    public function getAllMemberIntegral():float
    {
        return $this->sum('integral') ?? 0.00;
    }

    /**
     * @see 自己+代理注册人金币总数量
     */
    public function getSelfAndChildGoldAttribute():float
    {
        $selfGold = $this->gold;
        $childsGold = $this->where('parent_user_id',$this->id)->sum('gold');
        $fSumGold = bcadd($selfGold,$childsGold,2);
        return $fSumGold;
    }

    /**
     * @see 是否自动领取
     */
    public function getIsAutoAttribute():int
    {
        $val = redis_hget(config('czf.redis_key.h1'),$this->id);
        return $val['is_auto'] ?? 0;
    }

    /**
     * 金币池金额
     */
    public function getGoldPoolAttribute()
    {
        return get_gold_pool();
    }
    /**
     * @see 下一次领取时间还剩多少秒
     */
    public function getNextAutoGoldTimeAttribute():int
    {
        $aInfo = redis_hget(config('czf.redis_key.h1'),$this->id);
        // 没有领取过 可以立马领取
        if (empty($aInfo))
            $iRes = 0;
        else {
            $next_time = $aInfo['next_time'];
            // 领取过但是当前时间大于下一次时间 可以领取
            if (time() >= $next_time)
                $iRes = 0;
            else {
                $tmpTime = $next_time - time();
                $iRes = $tmpTime;
            }
        }
        return $iRes;
    }

    /**
     * @return int
     * @下一次领取金额
     */
    public function getNextGoldAttribute():float
    {
        return compute_autogold($this->self_and_child_gold,$this->gold);
    }

    /**
     * @return int
     * @see 挂单数量
     */
    public function getPhoneOrderNumAttribute():int
    {
        $phoneModel = new PhoneBuyGold;
        $iNum = $phoneModel->where(['status'=>0,'is_show'=>1])->count();
        return $iNum ?? 0;
    }

    /**
     * @return float
     */
    public function getGoldSumAttribute():string
    {
        $oGoldFlowModel = new GoldFlow;
        $burnGold = $oGoldFlowModel->getBurnGoldSum() ?? 0.00;
        return bcsub(2000000000,$burnGold,2);
    }



}
