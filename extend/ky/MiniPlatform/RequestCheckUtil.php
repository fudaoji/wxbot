<?php
/**
 * Created by PhpStorm.
 * Script Name: RequestCheckUtil.php
 * Create: 2018/8/30 11:48
 * Description: 请求验证
 * Author: Jason<dcq@kuryun.cn>
 */
namespace ky\MiniPlatform;

use ky\Logger;

class RequestCheckUtil
{
    /**
     * 校验字段filedName的值$value非空
     * @param string $value
     * @param string $fieldName
     * @return boolean
     * @throws \Exception
     * @author Jason<dcq@kuryun.cn>
     */
    public static function checkNotNull($value, $fieldName) {
        if(self::checkEmpty($value)) {
            Logger::setMsgAndCode("缺少必要参数: " .$fieldName);
        }

        return true;
    }

    /**
     * 校验filedName值value的长度
     * @param string $value
     * @param int $maxLength
     * @param string $fieldName
     * @return boolean
     * @throws \Exception
     * @author Jason<dcq@kuryun.cn>
     */
    public static function checkMaxLength($value, $maxLength, $fieldName) {
        if(!self::checkEmpty($value) && mb_strlen($value, "UTF-8") > $maxLength) {
            Logger::setMsgAndCode("参数: " .$fieldName . " 的长度应小于 " . $maxLength);
        }
        return true;
    }

    /**
     * 校验fieldName的值value的最大列表长度
     * @param string $value
     * @param int $maxSize
     * @param string $fieldName
     * @return boolean
     * @throws \Exception
     * @author Jason<dcq@kuryun.cn>
     */
    public static function checkMaxListSize($value, $maxSize, $fieldName) {
        if (self::checkEmpty($value)) return false;

        $list = preg_split("/,/", $value);
        if(count($list) > $maxSize) {
            Logger::setMsgAndCode("参数 ". $fieldName . " 的值的最大列表长度,应小于 " . $maxSize);
        }

        return true;
    }

    /**
     * 校验字段fieldName的值value的最大值
     * @param string $value
     * @param int $maxValue
     * @param string $fieldName
     * @return boolean
     * @throws \Exception
     * @author Jason<dcq@kuryun.cn>
     */
    public static function checkMaxValue($value, $maxValue, $fieldName) {
        if (self::checkEmpty($value)) return false;
        self::checkNumberic($value, $fieldName);

        if ($value > $maxValue) {
            Logger::setMsgAndCode("参数 " . $fieldName . " 应小于 " . $maxValue);
        }

        return true;
    }

    /**
     * 校验字段fieldName的值value的最小值
     * @param string $value
     * @param int $minValue
     * @param string $fieldName
     * @return boolean
     * @throws \Exception
     * @author Jason<dcq@kuryun.cn>
     */
    public static function checkMinValue($value, $minValue, $fieldName) {
        if (self::checkEmpty($value)) return false;
        self::checkNumberic($value, $fieldName);

        if ($value < $minValue) {
            Logger::setMsgAndCode("参数 " . $fieldName . " 应大于 " . $minValue);
        }

        return true;
    }

    /**
     * 校验字段filedName的值value是否是number
     * @param string $value
     * @param string $fieldName
     * @return boolean
     * @throws \Exception
     * @author Jason<dcq@kuryun.cn>
     */
    public static function checkNumberic($value, $fieldName) {
        if (!is_numeric($value)) {
            Logger::setMsgAndCode("参数 " . $fieldName . " 的值不是数字 : " . $value);
        }

        return true;
    }

    /**
     * 校验字段filedName的值value是否是array
     * @param array $value
     * @param string $fieldName
     * @return boolean
     * @throws \Exception
     * @author Jason<dcq@kuryun.cn>
     */
    public static function checkArray($value, $fieldName) {
        if(! is_array($value)) {
            Logger::setMsgAndCode("参数 " . $fieldName . " 的值不是数组 : " . $value);
        }

        return true;
    }

    /**
     * 校验$value是否非空
     * @param string $value
     * @return boolean
     * @author Jason<dcq@kuryun.cn>
     */
    public static function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (is_string($value) && trim($value) === "")
            return true;

        return false;
    }

    /**
     * 校验fieldName的值是否是合法范围内的值
     * @param string $value
     * @param string $range
     * @return boolean
     * @author Jason<dcq@kuryun.cn>
     */
    public static function checkIn($value, $range) {
        if(self::checkEmpty($value)) return false;
        if(in_array($value, $range)) {
            return true;
        }
        return false;
    }

    /**
     * 校验时间格式
     * @param $date
     * @param $fieldName
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \Exception
     */
    public static function checkDate($date = '', $fieldName = ''){
        $timestamp = strtotime($date);
        if($timestamp !== strtotime(date('YmdHis', $timestamp))){
            Logger::setMsgAndCode("参数 " . $fieldName . " 的值合法的时间格式 : " . $date);
        }
        return  true;
    }
}