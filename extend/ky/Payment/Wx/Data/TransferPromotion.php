<?php
/**
 * Created by PhpStorm.
 * Script Name: TransferPromotion.php
 * Create: 2017/5/3 上午11:58
 * Description: 企业付款到零钱
 * Author: Doogie<461960962@qq.com>
 */
namespace ky\Payment\Wx\Data;
class TransferPromotion extends Base
{
    /**
     * 设置微信分配的公众账号ID
     * @param string $value
     **/
    public function SetMch_Appid($value)
    {
        $this->values['mch_appid'] = $value;
    }
    /**
     * 获取微信分配的公众账号ID的值
     * @return 值
     **/
    public function GetMch_Appid()
    {
        return $this->values['mch_appid'];
    }
    /**
     * 判断微信分配的公众账号ID是否存在
     * @return true 或 false
     **/
    public function IsMch_AppidSet()
    {
        return array_key_exists('mch_appid', $this->values);
    }


    /**
     * 设置微信支付分配的商户号
     * @param string $value
     **/
    public function SetMchid($value)
    {
        $this->values['mchid'] = $value;
    }
    /**
     * 获取微信支付分配的商户号的值
     * @return 值
     **/
    public function GetMchid()
    {
        return $this->values['mchid'];
    }
    /**
     * 判断微信支付分配的商户号是否存在
     * @return true 或 false
     **/
    public function IsMchidSet()
    {
        return array_key_exists('mchid', $this->values);
    }


    /**
     * 设置微信支付分配的终端设备号，与下单一致
     * @param string $value
     **/
    public function SetDevice_info($value)
    {
        $this->values['device_info'] = $value;
    }
    /**
     * 获取微信支付分配的终端设备号，与下单一致的值
     * @return 值
     **/
    public function GetDevice_info()
    {
        return $this->values['device_info'];
    }
    /**
     * 判断微信支付分配的终端设备号，与下单一致是否存在
     * @return true 或 false
     **/
    public function IsDevice_infoSet()
    {
        return array_key_exists('device_info', $this->values);
    }


    /**
     * 设置随机字符串，不长于32位。推荐随机数生成算法
     * @param string $value
     **/
    public function SetNonce_str($value)
    {
        $this->values['nonce_str'] = $value;
    }
    /**
     * 获取随机字符串，不长于32位。推荐随机数生成算法的值
     * @return 值
     **/
    public function GetNonce_str()
    {
        return $this->values['nonce_str'];
    }
    /**
     * 判断随机字符串，不长于32位。推荐随机数生成算法是否存在
     * @return true 或 false
     **/
    public function IsNonce_strSet()
    {
        return array_key_exists('nonce_str', $this->values);
    }

    /**
     * 设置商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
     * @param string $value
     **/
    public function SetPartner_trade_no($value)
    {
        $this->values['partner_trade_no'] = $value;
    }
    /**
     * 获取商户订单号
     * @return 值
     **/
    public function GetPartner_trade_no()
    {
        return $this->values['partner_trade_no'];
    }
    /**
     * 判断商户订单号是否存在
     * @return true 或 false
     **/
    public function IsPartner_trade_noSet()
    {
        return array_key_exists('partner_trade_no', $this->values);
    }


    /**
     * 设置接受方openid
     * @param string $value
     **/
    public function SetOpenid($value)
    {
        $this->values['openid'] = $value;
    }
    /**
     * 获取接收方openid
     * @return 值
     **/
    public function GetOpenid()
    {
        return $this->values['openid'];
    }
    /**
     * 判断接收方openid否存在
     * @return true 或 false
     **/
    public function IsOpenidSet()
    {
        return array_key_exists('openid', $this->values);
    }


    /**
     * 设置是否姓名校验
     * @param string $value
     **/
    public function SetCheck_name($value)
    {
        $this->values['check_name'] = $value;
    }
    /**
     * 获取是否姓名校验的值
     * @return 值
     **/
    public function GetCheck_name()
    {
        return $this->values['check_name'];
    }
    /**
     * 判断是否姓名校验的值是否存在
     * @return true 或 false
     **/
    public function IsCheck_nameSet()
    {
        return array_key_exists('check_name', $this->values);
    }


    /**
     * 设置用户真实姓名
     * @param string $value
     **/
    public function SetRe_user_name($value)
    {
        $this->values['re_user_name'] = $value;
    }
    /**
     * 获取用户真实姓名的值
     * @return 值
     **/
    public function GetRe_user_name()
    {
        return $this->values['re_user_name'];
    }
    /**
     * 判断真实姓名的值是否存在
     * @return true 或 false
     **/
    public function IsRe_user_nameSet()
    {
        return array_key_exists('re_user_name', $this->values);
    }


    /**
     * 设置打款金额
     * @param string $value
     **/
    public function SetAmount($value)
    {
        $this->values['amount'] = $value;
    }
    /**
     * 获取打款金额的值
     * @return 值
     **/
    public function GetAmount()
    {
        return $this->values['amount'];
    }
    /**
     * 判断打款金额的值是否存在
     * @return true 或 false
     **/
    public function IsAmountSet()
    {
        return array_key_exists('amount', $this->values);
    }


    /**
     * 设置企业付款操作说明信息
     * @param string $value
     **/
    public function SetDesc($value)
    {
        $this->values['desc'] = $value;
    }
    /**
     * 获取企业付款操作说明信息的值
     * @return 值
     **/
    public function GetDesc()
    {
        return $this->values['desc'];
    }
    /**
     * 判断企业付款操作说明信息是否存在
     * @return true 或 false
     **/
    public function IsDescSet()
    {
        return array_key_exists('desc', $this->values);
    }

    /**
     * 设置调用接口的机器Ip地址
     * @param string $value
     **/
    public function SetSpbill_create_ip($value)
    {
        $this->values['spbill_create_ip'] = $value;
    }
    /**
     * 获取调用接口的机器Ip地址的值
     * @return 值
     **/
    public function GetSpbill_create_ip()
    {
        return $this->values['spbill_create_ip'];
    }
    /**
     * 判断调用接口的机器Ip地址是否存在
     * @return true 或 false
     **/
    public function IsSpbill_create_ipSet()
    {
        return array_key_exists('spbill_create_ip', $this->values);
    }
}