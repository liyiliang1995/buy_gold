<?php
/**
 * Created by PhpStorm.
 * User: youxingxiang
 * Date: 2019/7/24
 * Time: 10:04 AM
 */
namespace App\Logics;
use App\HourAvgPrice;
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
        \Auth::user()->decrement('gold',100);
        $this->member->increment('gold',100);
    }

    /**
     * @param array $aParams
     * @see 后台充值
     */
    public function recharge(array $aParam):bool
    {
        // 首先计算之前让redis 金币池有值 防止最后统计的时候重复统计
        get_gold_pool();
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
        if ($aParam['gold'] > 0 ) {
            $member->increment('gold',abs($aParam['gold']));
            set_gold_pool(abs($aParam['gold']),false);
        } else {
            $member->decrement('gold',abs($aParam['gold']));
            set_gold_pool(abs($aParam['gold']));
        }
        $member->save();
    }

    /**
     * @param array $aParam
     */
    public function setUser(array $aParam):bool
    {
        if (phoneVerif($aParam['phone']) === false)
            throw new  CzfException("手机号码格式不正确");
        if (\Auth::user()->phone != $aParam['phone'] && $this->model->isExistsPhone($aParam['phone']))
            throw new CzfException("注册手机号码已经存在！");
        if (\Auth::user()->status == 0)
            $aParam['status'] = 1;
        return $this->update(userId(),$aParam);

    }

    /**
     * @param int $id
     * @return bool
     * @see 增加自动领取金币的用户
     */
    public function addAutoGoldMembers(int $id,int $type)
    {
        member_is_auto_gold($type,$id);
    }

    /**
     * @param float $fRnum 以领取的金币数量
     * @see 领取金币逻辑
     * @see 自己+代理注册人金币总数量 $sum
     * $sum < 1000 领取千分之一
     * 1000 <= $sum < 5000 领取千分之一点一
     * 5000 <= $sum 20000 千分之一点二
     * 20000 <= $sum 领取千分之一点三
     * @see 金币池数量为10亿  每减少一亿 领取数量减少10%
     * @see 每日最高领取上限当日日均价价值500元
     */
    public function receiveGold(int $id,float $fRnum)
    {
        get_gold_pool();
        $this->model = $this->model->findOrFail($id);
        if ($this->model->energy >= 1) {

            // 本次领取数量
            $fNum = $this->getReceiveGoldNum();
            // 验证
            $bRes = $this->receiveGoldValidate($fRnum,$fNum);

            if ($bRes) {
                DB::transaction(function () use($fNum,$id) {
                    $this->receiveGoldFlow($fNum, $id);
                    $this->IncreaseAndDecrease($fNum);
                });
                // 用户领取金币数量 redis
                member_is_auto_gold(1,$id,$fNum);
                // 金币池变化
                set_gold_pool($fNum,false);
            }
        }
    }

    /**
     * @return float
     * @see  获得领取金币数量
     */
    public function getReceiveGoldNum():float
    {
        // 自己和代理总金币数量
        $fSumGold = $this->model->self_and_child_gold;
        // $sum < 1000 领取千分之一
        if ($fSumGold < 1000)
            $rate = 0.0010;
        // 1000 <= $sum < 5000 领取千分之一点一
        else if (1000 <= $fSumGold &&  $fSumGold < 5000)
            $rate = 0.0011;
        // 5000 <= $sum 20000 千分之一点二
        else if (5000 <= $fSumGold && $fSumGold < 20000)
            $rate = 0.0012;
        else
            $rate = 0.0013;
        // 领取数量
        $fNum = bcmul($fSumGold,$rate,2);
        // 金币池每减少1亿减少百分10
        $gold_pole = get_gold_pool();
        // 金币池减少了多少个一亿
        $iReduceBillion = 10 - ceil($gold_pole/100000000);
        $iReduceGold = bcmul($fNum,0.1*$iReduceBillion,2);
        return bcsub($fNum,$iReduceGold,2);

    }

    /**
     * @param float $fRnum 以领取金币数
     * @param float $fNum 本次领取金币数量
     * @return float
     * @see 领取金币验证
     */
    public function receiveGoldValidate(float $fRnum,float $fNum):bool
    {
        //每日最高领取上限当日日均价价值500元
        $oHourAvgPriceModel = new HourAvgPrice;
        $gold_unit_price = $oHourAvgPriceModel->getBestNewAvgPrice();
        $fLimitNum = bcdiv(500,$gold_unit_price,2);
        $fTmp = bcadd($fNum,$fRnum,2);
        return $fLimitNum > $fTmp;
    }

    /**
     * @param float $fNum 领取数量
     * @see 领取金币流水表
     */
    public function receiveGoldFlow(float $fNum,int $id)
    {
        // 自动领取一次扣除 1能量值
        $this->getBuyGoldGoldFlowDetail(1,4,$id,$fNum,"自动领取获得金币");
        $this->getBuyGoldEnergyFlowDetail(1, $id, 1,'自动领取金币消耗能量值');
    }

    /**
     * @param float $fNum
     * @param int $id
     */
    public function IncreaseAndDecrease(float $fNum)
    {
        $this->model->increment('gold',$fNum);
        $this->model->decrement('energy',1);

    }





}
