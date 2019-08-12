<?php

namespace App\Console\Commands;

use App\BuyGold;
use App\Member;
use App\Logics\TradeLogic;
use Illuminate\Console\Command;

class MemberCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crontab:check_member';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '24小时未确认收款双方推荐人锁定,72小时未成交,自动撤单 15天未登陆金币池金额每天自动减少10%';
    /**
     * @var
     */
    protected $buy_gold_model;
    /**
     * @var
     */
    protected $member_model;
    /**
     * @var
     */
    protected $start_time_oneday;
    /**
     * @var
     */
    protected $start_time_twoday;
    /**
     * @var
     */
    protected $end_time;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->buy_gold_model = new BuyGold;
        $this->member_model = new Member;
        $this->start_time_oneday = date("Y-m-d H:i:s",strtotime("-1 day"));
        $this->start_time_twoday = date("Y-m-d H:i:s",strtotime("-2 day"));
        $this->end_time = date("Y-m-d H:i:s");
        // 72小时没人下单的订单撤销
        $this->CancelOrder();
        $this->lockParent();
//        $notReceiptBuyGold = $this->getNotReceiptBuyGold();
//        $notSellBuyGold = $this->getNotSellBuyGold();
    }

    /**
     * @see 获取没有收款的求购订单
     */
    public function getNotReceiptBuyGold()
    {
       $aData = $this->buy_gold_model
           ->whereBetween('created_at',[$this->start_time_oneday,$this->end_time])
           ->where('status',0)
           ->whereNotNull('seller_id')
           ->get();
       return $aData;
    }

    /**
     * @see 获取72小时没有卖家下单的订单
     */
    public function getNotSellBuyGold()
    {
        $aData = $this->buy_gold_model
            ->where('created_at','<',$this->start_time_twoday)
            ->where('status',0)
            ->whereNull('seller_id')
            ->get();
        return $aData;
    }

    /**
     * @see 撤销订单
     */
    public function CancelOrder()
    {
        $order = $this->getNotSellBuyGold();
        $logic = new TradeLogic(null);
        if ($order) {
            foreach ($order as $item) {
                $logic->releaseLock([$item->id]);
                $item->delete();
            }
        }
    }

    /**
     * @see 24小时没有收款锁定双方上级代理
     */
    public function lockParent()
    {
        $order = $this->getNotReceiptBuyGold();
        if ($order) {
            foreach ($order as $item) {
                // 增加到领取金币锁定用户
                redis_sadd(config("czf.redis_key.set1"),$item->seller->parent_user_id);
                redis_sadd(config("czf.redis_key.set1"),$item->member->parent_user_id);
                // 自身状态改变
                $this->member_model->where('id',$item->seller->parent_user_id)->increment('time',1,['status'=>4]);
                $this->member_model->where('id',$item->member->parent_user_id)->increment('time',1,['status'=>4]);
            }
        }
    }


}
