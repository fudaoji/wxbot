<?php
/**
 * Created by PhpStorm.
 * Script Name: Qcloud.php
 * Create: 2017/8/23 下午3:12
 * Description:
 * Author: Doogie<461960962@qq.com>
 */
namespace ky\Sms;
use ky\Logger;
use ky\Sms\Qcloud\MultiSender;
use ky\Sms\Qcloud\SingleSender;

class Qcloud
{
    private $appId;
    private $appKey;
    private $error;

    /**
     * Qcloud constructor.
     * @param string $appid
     * @param string $appkey
     */
    function __construct($appid='', $appkey='') {
        $this->appId = $appid;
        $this->appKey = $appkey;
    }

    /**
     * 发送短信
     * @param string $mobile
     * @param string $content
     * @return mixed
     * @author: Doogie<461960962@qq.com>
     */
    public function send($mobile='', $content=''){
        $singleSender = new MultiSender($this->appId, $this->appKey);
        // 普通单发
        $result = $singleSender->send(0, "86", (array)$mobile, $content);
        $rsp = json_decode($result, true);
        if($rsp['result'] == 0){
            return true;
        }else{
            $this->setError($rsp['result']);
            Logger::write(json_encode($rsp));
            return false;
        }
    }

    /**
     * 错误码对照表
     * @param null $code
     * @author: Doogie<461960962@qq.com>
     */
    private function setError($code = null){
        $list = [
            1001 => 'sig校验失败',
            1002 => '短信/语音内容中含有敏感词',
            1009 => '请求ip不在白名单中',
            1012 => '签名格式错误或者签名未审批',
            1013 => '下发短信/语音命中了频率限制策略',
            1014 => '模版未审批或请求的内容与审核通过的模版内容不匹配',
            1015 => '手机号在黑名单库中,通常是用户退订或者命中运营商黑名单导致的',
            1016 => '手机号格式错误',
            1017 => '请求的短信内容太长',
            1022 => '业务短信日下发条数超过设定的上限',
			1031 => '套餐包余量不足,请购买套餐包'
        ];
        $this->error = isset($list[$code]) ? $list[$code] : '未知错误';
    }

    /**
     * 返回错误信息
     * @return mixed
     * @author: Doogie<461960962@qq.com>
     */
    public function getError(){
        return $this->error;
    }

}
