<?php

namespace App\Console\Commands;
use App\Member;
use App\Logics\MemberLogic;
use Illuminate\Console\Command;
class AutoGold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'script:auto_gold';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自动领取金币';

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
        //$this->checkAutoTime();
        $this->monitor();
    }
    // 监听需要领取的用户
    public function monitor()
    {
        while (1) {
                $oMemberModel = new Member;
                $oMemberLogic = new MemberLogic($oMemberModel);
                $sKey = config('czf.redis_key.h1');
                $members = redis_hgetall($sKey);
                \Log::channel('script')->info('脚本正在运行', ['自动领取的member_id' => $members]);
                foreach ($members as $val) {
                    $aInfo = json_decode($val,true);

                    if (
                        // 设置为自动领取
                        $aInfo['is_auto'] == 1
                        // 时间判断
                        && time() >= $aInfo['next_time']
                        //冻结的不领取
                        && !redis_sismember(config('czf.redis_key.set1'),$aInfo['id'])
                    ) {
                        if ($aInfo['date'] != date('Y-m-d',time())) {
                            set_receive_gold_member_info(['id'=>$aInfo['id'],'is_auto'=>$aInfo['is_auto'],'gold'=>0]);
                        }
                        $oMemberLogic->receiveGold($aInfo);
                    }
                }
            // 获取下一次领取的时间
            sleep(1);
        }
    }

}
