<?php

namespace App\Console\Commands;

use App\BuyGold;
use App\Member;
use App\PhoneBuyGold;
use App\Logics\TradeLogic;
use App\Logics\MemberLogic;
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
     * @var
     */
    protected $phone_buy_gold_model;
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
        $this->phone_buy_gold_model = new PhoneBuyGold;
        $this->start_time_oneday = date("Y-m-d H:i:s",strtotime("-1 day"));
        $this->start_time_twoday = date("Y-m-d H:i:s",strtotime("-3 day"));
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
           ->where('created_at',"<",$this->start_time_oneday)
           ->where('status',0)
           ->where('is_statistical',0)
           ->whereNotNull('seller_id')
           ->get();
       return $aData;
    }

    /**
     * @return mixed
     * @see 手机充值没有统计的
     */
    public function getNotReceiptPhoneBuyGold()
    {
        $aData = $this->phone_buy_gold_model
            ->where('created_at',"<",$this->start_time_oneday)
            ->where('status',0)
            ->where('is_statistical',0)
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
     * @return mixed
     * @see 72小时没有抢单的下单
     */
    public function getNotSellPhoneBuyGold()
    {
        $aData = $this->phone_buy_gold_model
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
        $phone_order = $this->getNotSellPhoneBuyGold();
        $member_logic = new MemberLogic($this->phone_buy_gold_model);
        if ($phone_order) {
            foreach ($phone_order as $item) {
                // 撤销流水
                $member_logic->applyCancelOrderFlow($item);
                // 撤销返回金币
                $item->member->increment('gold',$item->gold);
                release_lock($item->user_id);
                $item->delete();
            }
        }

        if ($order) {
            foreach ($order as $item) {
                release_lock($item->user_id);
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
        $phone_order = $this->getNotReceiptPhoneBuyGold();
        if ($order) {
            foreach ($order as $item) {
                freeze_member($item->seller->parent_user_id,4);
                freeze_member($item->member->parent_user_id,4);
                // 统计过的不再统计
                $item->is_statistical = 1;
                $item->save();
            }
        }
        if ($phone_order) {
            foreach ($phone_order as $item) {
                freeze_member($item->seller->parent_user_id,4);
                freeze_member($item->member->parent_user_id,4);
                // 统计过的不再统计
                $item->is_statistical = 1;
                $item->save();
            }
        }
    }


}
