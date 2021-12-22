<?php
/**
 * Script name: GetPublicKey.php
 * Created by PhpStorm.
 * Create: 2018-02-26 17:08
 * Description: 获取RSA加密公钥
 * Author: Jason<1589856452@qq.com>
 */
namespace ky\Payment\Wx\Data;

class GetPublicKey extends Base
{
    /**
     * 设置微信支付分配的商户号
     * @param string $value
     **/
    public function SetMch_id($value) {
        $this->values['mch_id'] = $value;
    }

    /**
     * 获取微信支付分配的商户号的值
     * @return 值
     **/
    public function GetMch_id() {
        return $this->values['mch_id'];
    }
    /**
     * 判断微信支付分配的商户号是否存在
     * @return true 或 false
     **/
    public function IsMch_idSet() {
        return array_key_exists('mch_id', $this->values);
    }

    /**
     * 设置随机字符串，不长于32位。推荐随机数生成算法
     * @param string $value
     **/
    public function SetNonce_str($value) {
        $this->values['nonce_str'] = $value;
    }

    /**
     * 获取随机字符串，不长于32位。推荐随机数生成算法的值
     * @return 值
     **/
    public function GetNonce_str() {
        return $this->values['nonce_str'];
    }

    /**
     * 判断随机字符串，不长于32位。推荐随机数生成算法是否存在
     * @return true 或 false
     **/
    public function IsNonce_strSet() {
        return array_key_exists('nonce_str', $this->values);
    }

    /**
     * 设置签名类型
     * @param string $value
     **/
    public function SetSign_type($value) {
        $this->values['sign_type'] = $value;
    }

    /**
     * 获取签名类型的值
     * @return 值
     **/
    public function GetSign_type() {
        return $this->values['sign_type'];
    }

    /**
     * 判断签名类型的值是否存在
     * @return true 或 false
     **/
    public function IsSign_typeSet() {
        return array_key_exists('sign_type', $this->values);
    }
}