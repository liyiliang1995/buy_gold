<?php
use Illuminate\Support\Facades\Redis;

if (!function_exists('sendMsg' )) {
    /**
     * @param string $to
     */
    function sendMsg(string $to)
    {
        if (!phoneVerif($to))
            throw new Exception("手机号码格式不正确！");
        $rand = rand(100001, 999999);
        $params = array(
            'key' => config('czf.short_message')['key'],      //您申请的APPKEY
            'mobile' => $to,                                         //接受短信的用户手机号码
            'tpl_id' => config('czf.short_message')['tpl_id'],   //您申请的短信模板ID，根据实际情况修改
            'tpl_value' => '#code#=' . $rand                               //您设置的模板变量，根据实际情况修改
        );
        $ip = request()->getClientIp();

        $sToKey = config('czf.redis_key.s1') . $to;
        $sIpKey = config('czf.redis_key.s2') . $ip;
        if ($ip) {
            Redis::incr($sIpKey);
            Redis::expire($sIpKey, 180);
            if (Redis::get($sIpKey) && Redis::get($sIpKey) > 50)        // ip 三分钟超过50次 我们视为不正常操作
                throw new Exception("请勿恶意点击短信发送！");
        }
        Redis::incr($sToKey);
        Redis::expire($sToKey, 180);
        if (Redis::get($sToKey) && Redis::get($sToKey) > 3)             //三分钟只能发送三次
            throw new Exception("当前手机号短信发送过于频繁，三分钟以后在进行尝试！");

        $paramstring = http_build_query($params);
        $content = juheCurl("http://v.juhe.cn/sms/send", $paramstring);
        $result = json_decode($content, true);
        if ($result && $result['error_code'] == 0) {
            $sCodeKey = config('czf.redis_key.s3') . $to;
            Redis::set($sCodeKey, $rand);
            Redis::expire($sCodeKey, 300);                               // 验证码有效期5分钟
        } else if ($result && $result['reason']) {
            $sLogKey = config('czf.redis_key.s4');
            Redis::hset($sLogKey,$to.':'.date("Y-m-d H:i:s"),$content);   // 记录日志
            throw new Exception($result['reason']);
        } else {
            throw new Exception("短信发送异常！");
        }
    }
}
if (!function_exists('juheCurl' )) {
    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    function juheCurl($url, $params = false, $ispost = 0)
    {
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'JuheData');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }

}
if (!function_exists('phoneVerif')) {
    /**
     * @param $phone
     * @return bool
     */
    function phoneVerif($phone)
    {
        preg_match("/^1[349578]\d{9}$/", $phone, $mobile);
        return empty($mobile) ? false : true;
    }
}

if (!function_exists('comparisonCode' )) {
    /**
     * @param string $code
     * @param string $key
     * @return bool
     * @see 手机验证码比对
     */
    function comparisonCode(string $code,string $key = null):bool
    {
        $bData = false;
        $sCodeKey = config('czf.redis_key.s3') . $key;
        $sRedisCode = Redis::get($sCodeKey);
        if (!empty($sRedisCode) && $sRedisCode == $code) {
            Redis::del($sCodeKey);
            $bData = true;
        }
        return $bData;
    }
}
if (!function_exists('userId')) {

    function userId()
    {
        return Auth::guard()->id();
    }
}

if (!function_exists('getConfig')) {
    /**
     * @param int $iType
     * @param \App\Config $config
     * @return array
     * @see 根据类型获取不同配置
     */
    function getConfigByType(int $iType):array
    {
        return \App\Config::getConfigByType($iType) ?: [];
    }
}

if(!function_exists('czf_asset')) {
    /**
     * @param string $sPath
     * @param string $tmpPath
     * @return string
     * @see 上传图片路径
     */
    function czf_asset(string $sPath,$tmpPath = 'storage/'):string
    {
        return asset($tmpPath.$sPath);
    }
}

if (!function_exists('redis_idempotent')) {
    /**
     * @param $sKey
     * @param $time
     * @see 接口防护防止幂等
     */
    function redis_idempotent(string $sKey = null,array $aParam = null,int $time = 2):bool
    {
        $bRes = false;
        // 给一个默认key
        if (!$sKey) {
            $sSessionId = session()->getId();
            if ($sSessionId) {
                $sKey = $sSessionId;
            }
        }
        if (!$aParam) {
            $aParam = request()->input();
        }
        $sParam = $aParam ?  implode("-",$aParam) : "";

        if ($sKey && $sParam) {
            $bRes = Redis::sadd($sKey,$sParam);
            Redis::expire($sKey,$time);
        }
        return $bRes;
    }

}

if (!function_exists('sum_gold')) {
    /**
     * @param int $iNum
     * @param float $fAvgprice
     * @param float $fRate
     * @消耗总金币
     */
    function sum_gold(float $fPayGold,float $fBurnGold)
    {
        return bcadd($fPayGold,$fBurnGold,2);
    }
}
if (!function_exists("burn_gold")) {
    /**
     * @param float $fPayGold //支付金币数量
     * @param float $fRate // 消耗费率
     * @see返回金币池金币
     */
    function burn_gold(float $fPayGold,float $fRate = 0.05)
    {
        return bcmul($fPayGold,$fRate,2);
    }
}

if (!function_exists('pay_gold')) {
    /**
     * @param float $fAvgPrice 一个金币多少钱
     * @param float $fSumPrice 商品总价格
     * @return string
     * @商品价格转换为支付金币数量
     */
    function pay_gold(float $fAvgPrice,float $fSumPrice):float
    {
        return bcdiv($fSumPrice,$fAvgPrice,2);
    }
}

if (!function_exists('sum_price')) {
    /**
     * @param int $iNum 商品数量
     * @param float $fUnitPrice 商品单价
     * @return float
     * @返回商品价格
     */
    function sum_price(int $iNum,float $fUnitPrice):float
    {
        return bcmul($fUnitPrice,$iNum,2);
    }
}

if (!function_exists('unit_gold')) {
    /**
     * @param float $fPayGold
     * @param int $iNum
     * @return float
     * @每个商品单金币价格
     */
    function unit_gold(float $fPayGold,int $iNum):float
    {
        return bcdiv($fPayGold,$iNum,2);
    }
}
