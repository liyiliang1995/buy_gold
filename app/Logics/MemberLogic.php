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
     * @var
     */
    protected $agentRegisterGold;
    /**
     * @var
     */
    protected $in_tmp_pool;

    /**
     * @param array $aParam
     * @return bool
     * @see 代理注册
     */
    public function agentRegisterLogic(array $aParam):bool
    {
        $this->agentRegisterValidate($aParam);
        $this->agentRegisterGold = $this->getRegisterGold();
        $bRes = DB::transaction(function () use($aParam){
                //$this->agentRegisterSave($aParam);
                $this->member = $this->member->addChildMember(Arr::only($aParam,['password','phone','name']));
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
        if (empty($aParam['name']))
            throw new CzfException("注册真实姓名不能为空！");
        if ($this->model->checkPwd($aParam['password']) === false)
            throw new CzfException("请输入不小于6个字符的密码！");
//        if (false == comparisonCode( $aParam['code'],$aParam['phone']))
//            throw new CzfException("手机验证码不正确！");
        if ($this->model->checkPhoneOnly($aParam['phone']) || $this->member->isExistsPhone($aParam['phone']))
            throw new CzfException("注册手机号码已经存在！");
        if (\Auth::user()->gold < $this->agentRegisterGold)
            throw new CzfException("代理注册需要个人金币数量大于".$this->agentRegisterGold."！");
    }
    /*
     * @see带注册以后给新用户转入100金币
     */
    public function agentRegisterFlow()
    {
        $this->getBuyGoldGoldFlowDetail(0,6,userId(),$this->agentRegisterGold,"代理注册扣除金币".$this->agentRegisterGold);
        $this->getBuyGoldGoldFlowDetail(1,7,$this->member->id,$this->agentRegisterGold,"代理注册增加金币".$this->agentRegisterGold);
    }

    /**
     * @see 注册时，老会员扣除价值200元的金币，同时新会员获赠价值200元的金币；
     */
    public function getRegisterGold():float
    {
        $HourAvgPriceModel = new HourAvgPrice;
        $fBestNewAvgGold = $HourAvgPriceModel->getBestNewAvgPrice();
        return bcdiv(200,$fBestNewAvgGold,2);
    }

    /**
     * @代理扣除金币
     * @用户增加金币
     */
    public function agentRegisterIncreaseAndDecrease()
    {
        \Auth::user()->decrement('gold',$this->agentRegisterGold);
        $this->member->increment('gold',$this->agentRegisterGold);
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
        $bRes = $this->update(userId(),$aParam);
        // $this->model->addChildUserNum();
        return $bRes;

    }

    /**
     * @param int $id
     * @return bool
     * @see 增加自动领取金币的用户
     * @redis hash "{\"gold\":0,\"day\":1,\"time\":1800,\"id\":1,\"date\":\"2019-08-12\",\"is_auto\":1,\"next_auto_time\":1565595006}"
     */
    public function addAutoGoldMembers(int $id,int $type)
    {
        if (\Auth::user()->energy == 0 && $type == 1)
            throw new CzfException("能量值不够无法开启自动领取！");
        $aParam['id'] = $id;
        $aParam['is_auto'] = $type;
        set_receive_gold_member_info($aParam);
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
    public function receiveGold(array $aParam)
    {
        get_gold_pool();
        $id = $aParam['id'];
        $fRnum = $aParam['gold'];
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
                set_receive_gold_member_info(['id'=>$id,'is_auto'=>1,'gold'=>$fNum]);
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
        return compute_autogold($fSumGold,$this->model->gold);

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
        $this->getBuyGoldGoldFlowDetail(1,4,$id,$fNum,"领取获得金币");
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

    /**
     * @see
     * @see 手动领取
     * @see 上一次领取时间
     *
     */
    public function manualGiveGold()
    {
        get_gold_pool();
        $this->model = \Auth::user();
        $id = $this->model->id;
        // 已经领取数量
        $result = redis_hget(config("czf.redis_key.h1"),$id);
        $fRes = $result['gold'] ?? 0;
        // 本次领取数量
        $fNum = $this->getReceiveGoldNum();
        // 验证
        $this->manualGiveGoldValidate($fRes,$fNum,$result);
        DB::transaction(function () use($fNum,$id) {
            // 明细
            $this->getBuyGoldGoldFlowDetail(1, 4, $id, $fNum, "领取获得金币");
            // 增加
            $this->model->increment('gold', $fNum);
        });
        // 用户领取金币数量 redis
        set_receive_gold_member_info(['id'=>$id,'is_auto'=>0,'gold'=>$fNum]);
        // 金币池变化
        set_gold_pool($fNum,false);
    }

    /**
     * @param $fRes
     * @param $fNum
     * @throws CzfException
     */
    public function manualGiveGoldValidate($fRes,$fNum,$aInfo)
    {
        if (redis_sismember(config('czf.redis_key.set1'),$this->model->id))
            throw new CzfException("用户当前处于冻结状态，请完成交易在来领取！");
        if (!empty($aInfo['next_time']) &&  $aInfo['next_time'] > time())
            throw new CzfException("不能多次领取，请在下一个时间段在领取！");
        if (!$this->receiveGoldValidate($fRes,$fNum))
            throw new CzfException("今日领取金额已经达到上限！");

    }

    /**
     * @see 获取15天未登录的用户
     */
    public function getNotLoginMembers()
    {
        $date = date("Y-m-d H:i:s",strtotime("-15 day"));
        $aMembers = $this->model->where('login_at','<',$date)->get();
        return $aMembers;
    }

    /**
     * @see 15天未登录逻辑处理
     * @每天减少10%
     * @不足10金币 全部返回币池
     */
    public function notLoginMembersLogic()
    {
        $aMembers = $this->getNotLoginMembers();
        if ($aMembers) {
            foreach ($aMembers as $member) {
                // 减少10%
                if ($member->gold > 10) {
                    $reduceGold = bcmul($member->gold,0.1,2);
                } else {
                    $reduceGold = $member->gold;
                }
                DB::transaction(function () use($reduceGold,$member) {
                    $this->notLoginMembersFlow($reduceGold, $member->id);
                    $member->decrement('gold', $reduceGold);
                });
            }
        }
    }

    /**
     * @param float $reduceGold
     * @see 115天未登录逻辑处理
     */
    public function notLoginMembersFlow(float $reduceGold,int $id)
    {
        $this->getBuyGoldGoldFlowDetail(0,8,$id,$reduceGold,"15天未登录每次扣除10%");
        $this->getBuyGoldGoldFlowDetail(1,14,0,$reduceGold,"15天未登录金币流向金币池");
    }

    /**
     * @param $aParams
     * @积分转金币
     */
    public function IntegralToGold(array $aParams)
    {
        $this->IntegralToGoldValidate();
        DB::transaction(function () use ($aParams){
            $this->IntegralToGoldFlow($aParams);
            $this->IntegralToGoldIncreaseAndDecrease($aParams);
        });
    }

    /**
     * @积分转金币验证
     * @see 持比量200不得兑换积分
     */
    public function IntegralToGoldValidate():void
    {
        if (redis_idempotent('',['IntegralToGold']) === false)
            throw new CzfException("操作过于频繁！");
        if (redis_sismember(config('czf.redis_key.set1'),userId()))
            throw new CzfException("用户当前处于冻结状态，请完成交易在来兑换！");
        if (\Auth::user()->gold < 200)
            throw new CzfException("持有金币数量低于200不能兑换积分！");
    }

    /**
     * @param array $aParams
     * @see 积分兑换金币流水
     * @see 积分兑换流水 20% 流向平台发 80% 流向币池
     */
    public function IntegralToGoldFlow(array $aParams)
    {
        $this->getBuyGoldGoldFlowDetail(0,15,userId(),$aParams['gold'],"积分兑换扣除金币");
        $this->getBuyGoldIntegralFlowDetail(3,userId(),$aParams['integral'],"金币兑换获得积分");
        $this->stockholderShareGold($aParams);

    }

    /**
     * @param array $aParams
     * @see 股东分成
     */
    public function stockholderShareGold(array $aParams)
    {
        $aStockholder = $this->model->where('is_admin' ,1)->where('rate','>',0)->get();

        $this->in_tmp_pool = $in_tmp_pool = $aParams['gold'];
        // 是否分配了股东分成
        if(count($aStockholder) > 0) {
            foreach ($aStockholder as $item) {
                $fStockholderGold  = $this->getGoldByRate($item->rate,$in_tmp_pool);
                $in_tmp_pool -= $fStockholderGold;
                // 股东增加金币
                $item->increment('gold',$fStockholderGold);
                // 股东奖励
                $this->getBuyGoldGoldFlowDetail(1,17,$item['id'],$fStockholderGold,"积分兑换金币流向股东");
            }
            // 购物金币流向金币池 0代表系统 这个操作归属用户为系统
            $this->getBuyGoldGoldFlowDetail(1,16,0,$in_tmp_pool,"金币兑换积分金币流向金币池");
        }
        // 没有股东金币全部流入币池
        else {
            // 购物金币流向金币池 0代表系统 这个操作归属用户为系统
            $this->getBuyGoldGoldFlowDetail(1,16,0,$in_tmp_pool,"金币兑换积分金币流向金币池");
        }
    }

    /**
     * @param float $fRate
     * @param float $fGold
     * @return float
     * @see 股东分成比列计算
     */
    public function getGoldByRate(float $fRate,float $fGold):float
    {
        $fTrueRate = bcmul(config('czf.stockholders_rate2'),$fRate,2);
        $fTmp = bcmul($fGold,$fTrueRate,2);
        $fStockholderGold = bcdiv($fTmp,100,2);
        return $fStockholderGold ?? 0.00;
    }

    /**
     * @param array $aParams
     */
    public function IntegralToGoldIncreaseAndDecrease(array $aParams)
    {
        \Auth::user()->decrement('gold',$aParams['gold']);
        \Auth::user()->increment('integral',$aParams['integral']);
        set_gold_pool($this->in_tmp_pool);
    }






}
