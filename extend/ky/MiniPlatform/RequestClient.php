<?php
/**
 * Created by PhpStorm.
 * Script Name: RequestClient.php
 * Create: 2018/8/30 14:26
 * Description: 小程序开放平台请求入口
 * Author: Jason<dcq@kuryun.cn>
 */
namespace ky\MiniPlatform;

use ky\ErrorCode;
use ky\Logger;

class RequestClient
{
    public $appid;
    public $appSecretkey;
    public $connectTimeout;
    public $readTimeout;
    public $checkRequest = true;

    /**
     * 设置checkRequest
     * @param bool $flag
     * Author: fudaoji<fdj@kuryun.cn>
     * @return RequestClient
     */
    public function setCheckRequest($flag = true){
        $this->checkRequest = $flag;
        return $this;
    }

    /**
     * curl请求入口
     * @param string $url
     * @param string $postFields
     * @return object
     * @throws \Exception
     * @author Jason<dcq@kuryun.cn>
     */
    public function curl($url, $postFields=null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(stripos($url, "https://") != false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        if(is_array($postFields) && 0 < count($postFields)) {
            $postBodyString = "";
            $postMultipart = false;
            foreach($postFields as $k => $v) {
                if(is_int($k) && count($postFields) == 1){ //例如发布小程序就是这种情况
                    $postBodyString = '{}';
                    break;
                }
                if("@" != @substr($v, 0, 1)) { // 判断是不是文件上传
                    $postBodyString = json_encode($postFields, JSON_UNESCAPED_UNICODE);
                }else {
                    //文件上传用multipart/form-data, 否则用www-form-urlencode
                    $postMultipart = true;
                }
            }
            unset($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);
            if($postMultipart) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            }else {
                //curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
                // 发送模版消息时，对转换为json后的数据，不能截取掉最后一个字符
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postBodyString);
            }
        }

        $response = curl_exec($ch);

        if(curl_errno($ch)) {
            $wxCode = ErrorCode::CurlError;
            $wxMsg  = curl_error($ch);
            Logger::setMsgAndCode('curl发生错误，code: ' . $wxCode . ' msg: ' . $wxMsg, ErrorCode::CurlError);
        }else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 != $httpStatusCode) {
                Logger::setMsgAndCode('http返回错误，code: ' . $httpStatusCode . 'msg: ' . var_export($response, true));
            }
        }
        curl_close($ch);
        return $response;
    }

    /**
     * 执行请求
     * @param string $request
     * @param string $accessToken
     * @param bool $download
     * @return object
     * @throws \Exception
     * @author Jason<dcq@kuryun.cn>
     */
    public function execute($request, $accessToken = null, $download = false) {
        if($this->checkRequest) {
            $request->check();
        }

        //组装get参数
        $getParams = $request->getParams();
        $requestUrl = $request->getUrl() . "?";

        //拼装accessToken
        if($accessToken) {
            $requestUrl .= "access_token=" . $accessToken . "&";
        }

        foreach($getParams as $k => $v) {
            $requestUrl = $requestUrl . $k . "=" . $v . "&";
        }
        $requestUrl = substr($requestUrl, 0, -1);

        $postParams = $request->postParams();
        $resp = $this->curl($requestUrl, $postParams);

        if($download) {
            return $resp;
        }

        $respWellFormed = false;
        $respObject = json_decode($resp, true);
        if(null !== $respObject) {
            $respWellFormed = true;
            if(isset($respObject["errcode"]) && $respObject["errcode"] >= 0) {
                //错误信息中文翻译
                $respObject["errmsg"] = ErrorMsg::getErrorMsg($respObject['errcode']) ? ErrorMsg::getErrorMsg($respObject['errcode']) : $respObject['errmsg'];
                return $respObject;
            }
            if(isset($respObject["errcode"]) && $respObject["errcode"] == -1) {
                $wxCode = $respObject["errcode"];
                $wxMsg  = $respObject["errmsg"];
                Logger::setMsgAndCode('微信返回错误, code: '. $wxCode . ' msg: ' . $wxMsg . ' respObject: ' . var_export($respObject, true));
            }
        }

        if(false === $respWellFormed) {
            Logger::setMsgAndCode('收消息: 收到的格式不是合法的json格式, resp: ' . var_export($respObject, true));
        }

        return $respObject;
    }
}