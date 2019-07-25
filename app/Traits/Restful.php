<?php
/**
 * Created by PhpStorm.
 * User: youxingxiang
 * Date: 2019/7/24
 * Time: 10:26 AM
 */
namespace App\Traits;
trait Restful
{
    /**
     * @var array
     */
    protected $HttpCode = [
        'success' => 200,
        'unautherror' => 401,
        'paramserror' => 400,
        'servererror' => 500,
        'notfounderror' => 404
        ];

    /**
     * @param int $iCode
     * @param string $sMessage
     * @param array $aData
     * @return \Illuminate\Http\JsonResponse
     */
    public function restful_result(int $iCode,string $sMessage,array $aData = null):\Illuminate\Http\JsonResponse
    {
        $aResult = [
            'code' => $iCode,
            'message' => $sMessage,
        ];
        $aData && $aResult['data'] = $aData;
        return response()->json($aResult);
    }

    /**
     * @param string $sMessage
     * @param array $aData
     * @return \Illuminate\Http\JsonResponse
     */
    public function success(string $sMessage = '',array $aData = []):\Illuminate\Http\JsonResponse
    {
        return $this->restful_result($this->HttpCode['success'],$sMessage ?: "请求成功！",$aData);
    }

    /**
     * @param string $sMessage
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauth_error(string $sMessage = ''):\Illuminate\Http\JsonResponse
    {
        return $this->restful_result($this->HttpCode['unauth_error'],$sMessage ?: "没有权限！");
    }

    /**
     * @param string $sMessage
     * @return \Illuminate\Http\JsonResponse
     */
    public function params_error(string $sMessage = ''):\Illuminate\Http\JsonResponse
    {
        return $this->restful_result($this->HttpCode['paramserror'],$sMessage ?: "输入参数错误！");
    }

    /**
     * @param string $sMessage
     * @return \Illuminate\Http\JsonResponse
     */
    public function server_error(string $sMessage = ''):\Illuminate\Http\JsonResponse
    {
        return $this->restful_result($this->HttpCode['servererror'],$sMessage ?: "服务器内部错误！");
    }

    /**
     * @param string $sMessage
     * @return \Illuminate\Http\JsonResponse
     */
    public function notfound_error(string $sMessage = ''):\Illuminate\Http\JsonResponse
    {
        return $this->restful_result($this->HttpCode['notfounderror'],$sMessage ?: "未找到查询结果！");
    }

}