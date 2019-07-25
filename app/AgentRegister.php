<?php

namespace App;
use App\Exceptions\CzfException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentRegister extends Model
{

    /**
     * 软删除
     */
    use SoftDeletes;
    /**
     * @var array
     */
    protected $dates = ['delete_at'];
    /**
     * @var string
     */
    protected $table = 'agent_register';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @see 用户表的一对一关系
     */
    public function member()
    {
        return $this->belongsTo('App\Member','id');
    }

    /**
     * @param array $aData
     * @插入之前的钩子
     */
    public function beforeInsert(array $aData)
    {
        $member = new \App\Member;
        if ($this->checkPhoneOnly($aData['phone']) || $member->isExistsPhone($aData['phone'])) {
             throw new CzfException("注册手机号码已经存在！");
        }
        $pwd = request()->post('password') ?? '';
        $this->checkPwd($pwd);
        $member->addChildMember(['phone'=>$aData['phone'],'password'=>$pwd]);
    }

    /**
     * @param $pwd
     * @see 检测密码
     */
    public function checkPwd($pwd)
    {
        if (empty($pwd) || strlen($pwd) < 6)
            throw new CzfException("请输入不小于6个字符的密码！");
    }


    /**
     * @param string $sPhone
     * @return bool
     * @see 检测电话号码是否注册
     */
    public function checkPhoneOnly(string $sPhone):bool
    {
        $iRes = $this->where('phone',$sPhone)->count();
        return $iRes ? true : false;
    }
}
