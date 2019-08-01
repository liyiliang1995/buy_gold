<?php
/**
 * Created by PhpStorm.
 * User: youxingxiang
 * Date: 2019/7/24
 * Time: 10:04 AM
 */
namespace App\Logics;
use Illuminate\Support\Arr;
use App\Exceptions\CzfException;
use Illuminate\Support\Facades\DB;
class MemberLogic extends BaseLogic
{
    /**
     * @var
     */
    protected $member;

    /**
     * @param array $aParam
     * @return bool
     * @see 代理注册
     */
    public function agentRegisterLogic(array $aParam):bool
    {
        $this->agentRegisterValidate($aParam);
        $bRes = DB::transaction(function () use($aParam){
                $this->agentRegisterSave($aParam);
                $this->member = $this->member->addChildMember(Arr::only($aParam,['password','phone']));
                $this->agentRegisterFlow();
                $this->agentRegisterIncreaseAndDecrease();
                return true;
        });
        return $bRes;
    }

    /**
     * @param array $aParam
     */
    public function agentRegisterSave(array $aParam)
    {
        $this->model->user_id = $aParam['user_id'];
        $this->model->phone = $aParam['phone'];
        $this->model->save();
    }

    /**
     * @throws CzfException
     * @验证
     */
    public function agentRegisterValidate(array $aParam)
    {
        $this->member = new \App\Member;
        if (redis_idempotent() === false)
            throw new CzfException('请勿恶意提交订单，过2秒钟在尝试！');
        if ($this->model->checkPhoneOnly($aParam['phone']) || $this->member->isExistsPhone($aParam['phone']))
            throw new CzfException("注册手机号码已经存在！");
        if ($this->model->checkPwd($aParam['password']) === false)
            throw new CzfException("请输入不小于6个字符的密码！");
        if (\Auth::user()->gold < 100)
            throw new CzfException("代理注册需要个人金币数量大于100！");
    }
    /*
     * @see带注册以后给新用户转入100金币
     */
    public function agentRegisterFlow()
    {

        $this->getBuyGoldGoldFlowDetail(0,6,userId(),100,"代理注册扣除金币100");
        $this->getBuyGoldGoldFlowDetail(1,7,$this->member->id,100,"代理注册增加金币100");
    }

    /**
     * @代理扣除金币
     * @用户增加金币
     */
    public function agentRegisterIncreaseAndDecrease()
    {
        \Auth::user()->gold = bcsub(\Auth::user()->gold,100,2);
        $this->member->gold = bcadd($this->member->gold,100,2);
        $this->member->save();
        \Auth::user()->save();
    }

    /**
     * @param array $aParams
     * @see 后台充值
     */
    public function recharge(array $aParam):bool
    {
        $this->rechargeValidate($aParam);
        $bRes = DB::transaction(function () use($aParam){
            $this->rechargeFlow($aParam);
            $this->rechargeIncreaseAndDecrease($aParam);
            return true;
        });
        return $bRes;
    }

    /**
     * @param array $aParam
     * @throws \Exception
     */
    public function rechargeValidate(array $aParam)
    {
        if (redis_idempotent() === false)
            throw new \Exception('请勿恶意提交订单，过2秒钟在尝试！');
        if (!is_numeric($aParam['gold']))
            throw new \Exception('充值金额必须是一个数字！');
        if ($aParam['gold'] > 10000 || $aParam['gold'] <-10000)
            throw new \Exception('一次充值金额数量不能超过10000！');
    }

    /**
     * @see 流水
     * @用户增加
     * @金币池减少
     * @为负数 金币池增加 用户减少
     */
    public function rechargeFlow(array $aParam)
    {
        // 9 后台充值增加 10后台充值减少
        if ($aParam['gold'] > 0 ) {
            $isIncomde = 1;
            $iType =  9;
            $sOther = "后台充值增加";
        } else {
            $isIncomde = 0;
            $iType = 10;
            $sOther = "后台充值扣除";
        }
        $this->getBuyGoldGoldFlowDetail($isIncomde,$iType,$aParam['member_id'],abs($aParam['gold']),$sOther);
    }

    /**
     * @param array $aParam
     * @see 充值方减少增加
     * @see 金币池分减少增加
     */
    public function rechargeIncreaseAndDecrease(array $aParam)
    {
        $member = $this->find($aParam['member_id']);
        if ($aParam['gold'] < 0 && abs($aParam['gold']) > $member->gold)
            throw new \Exception('扣除金额大于用户实际金额！');
        if ($aParam['gold'] > 0 )
            $member->gold = bcadd($member->gold,$aParam['gold'],2);
        else
            $member->gold = bcsub($member->gold,abs($aParam['gold']),2);
        $member->save();
    }





}
