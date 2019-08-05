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
            $auto_time = $this->getNextAutoTime();
            // 领取金币
            if ($auto_time['next_auto_time'] <= time())
            {
                $oMemberModel = new Member;
                $oMemberLogic = new MemberLogic($oMemberModel);
                $sKey = config('czf.redis_key.h1');
                $members = redis_hgetall($sKey);
                \Log::channel('script')->info('脚本正在运行', ['自动领取的member_id' => $members]);
                foreach ($members as $key => $val) {
                    $val = json_decode($val,true);
                    if ($val['is_auto'] == 1)
                        $oMemberLogic->receiveGold($key,$val['gold']);
                }
                // 时间换了一天
                if ($auto_time['date'] != date('Y-m-d',time())) {
                    $day = $auto_time['day'] + 1;
                } else {
                    $day = $auto_time['day'];
                }
                $this->setAutoInfo(get_auto_gold_time($day),$day);
            }
            // 获取下一次领取的时间
            sleep(5);
        }
    }

    /**
     * @see 判断是不是自动领取的第一天
     * @判断 领取的时间天数进行了变化
     */
    public function getNextAutoTime()
    {
        $auto_time = redis_get(config('czf.redis_key.s6'));
        if (!$auto_time) {
            $time = get_auto_gold_time();
            $auto_time = $this->setAutoInfo($time);
        }
        return $auto_time;
    }
    public function setAutoInfo($time,$day = 1)
    {
        $auto_time  = [
            // 下一次领取时间
            'next_auto_time' => strtotime("+".$time."second"),
            // 领取的第多少天
            'day' => $day,
            // 领取间隔时间
            'time' => $time,
            // 领取的日期
            'date' => date('Y-m-d'),
        ];
        \Log::channel('script')->info('脚本正在运行', ['下一次领取的信息' => $auto_time]);
        redis_set(config('czf.redis_key.s6'),$auto_time);
        return $auto_time;
    }
}
