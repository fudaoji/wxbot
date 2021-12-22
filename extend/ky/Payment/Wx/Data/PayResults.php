<?php
/**
 * Created by PhpStorm.
 * Script Name: PayResults.php
 * Create: 2017/4/28 下午2:44
 * Description: 接口调用结果类
 * Author: Doogie<461960962@qq.com>
 */
namespace ky\Payment\Wx\Data;

use ky\ErrorCode;
use ky\Logger;

class PayResults extends Base
{
    /**
     * 检测签名
     * @param string $key
     * @return bool
     * @throws \Exception
     * @author: Doogie<461960962@qq.com>
     */
    public function CheckSign($key)
    {
        //fix异常
        if(!$this->IsSignSet()){
            Logger::setMsgAndCode("签名错误！", ErrorCode::WxpayException);
        }

        $sign = $this->MakeSign($key);
        if($this->GetSign() == $sign){
            return true;
        }
        Logger::setMsgAndCode("签名错误！", ErrorCode::WxpayException);
    }

    /**
     *
     * 使用数组初始化
     * @param array $array
     */
    public function FromArray($array)
    {
        $this->values = $array;
    }

    /**
     * 使用数组初始化对象
     * @param $array
     * @param bool $noCheckSign
     * @param string $key
     * @return WxPayResults
     * @author: Doogie<461960962@qq.com>
     */
    public static function InitFromArray($array, $noCheckSign = false, $key = '')
    {
        $obj = new self();
        $obj->FromArray($array);
        if($noCheckSign == false){
            $obj->CheckSign($key);
        }
        return $obj;
    }

    /**
     *
     * 设置参数
     * @param string $key
     * @param string $value
     */
    public function SetData($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * 将xml转为array
     * @param string $key
     * @param $xml
     * @return array
     * @throws WxPayException
     * @author: Doogie<461960962@qq.com>
     */
    public static function Init($xml, $key)
    {
        $obj = new self();
        $obj->FromXml($xml);
        //fix bug 2015-06-29
        if($obj->values['return_code'] != 'SUCCESS'){
            return $obj->GetValues();
        }
        $obj->CheckSign($key);
        return $obj->GetValues();
    }
}