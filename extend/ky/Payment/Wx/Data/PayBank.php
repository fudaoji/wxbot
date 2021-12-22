<?php
/**
 * Script name: PayBank.php
 * Created by PhpStorm.
 * Create: 2018-02-26 12:08
 * Description: 企业付款到银行卡
 * Author: Jason<1589856452@qq.com>
 */
namespace ky\Payment\Wx\Data;

class PayBank extends Base
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
     * 设置商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
     * @param string $value
     **/
    public function SetPartner_trade_no($value) {
        $this->values['partner_trade_no'] = $value;
    }

    /**
     * 获取商户订单号
     * @return 值
     **/
    public function GetPartner_trade_no() {
        return $this->values['partner_trade_no'];
    }

    /**
     * 判断商户订单号是否存在
     * @return true 或 false
     **/
    public function IsPartner_trade_noSet() {
        return array_key_exists('partner_trade_no', $this->values);
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
     * 设置收款方银行卡号
     * @param string $value
     **/
    public function SetEnc_bank_no($value) {
        $this->values['enc_bank_no'] = $value;
    }

    /**
     * 获取收款方银行卡号的值
     * @return 值
     **/
    public function GetEnc_bank_no() {
        return $this->values['enc_bank_no'];
    }

    /**
     * 判断收款方银行卡号的值是否存在
     * @return true 或 false
     **/
    public function IsEnc_bank_noSet() {
        return array_key_exists('enc_bank_no', $this->values);
    }

    /**
     * 设置收款方用户名
     * @param string $value
     **/
    public function SetEnc_true_name($value) {
        $this->values['enc_true_name'] = $value;
    }

    /**
     * 获取收款方用户名的值
     * @return 值
     **/
    public function GetEnc_true_name() {
        return $this->values['enc_true_name'];
    }

    /**
     * 判断收款方用户名的值是否存在
     * @return true 或 false
     **/
    public function IsEnc_true_nameSet() {
        return array_key_exists('enc_true_name', $this->values);
    }

    /**
     * 设置收款方开户行
     * @param string $value
     **/
    public function SetBank_code($value) {
        $this->values['bank_code'] = $value;
    }

    /**
     * 获取收款方开户行的值
     * @return 值
     **/
    public function GetBank_code() {
        return $this->values['bank_code'];
    }

    /**
     * 判断收款方开户行的值是否存在
     * @return true 或 false
     **/
    public function IsBank_code() {
        return array_key_exists('bank_code', $this->values);
    }

    /**
     * 设置打款金额
     * @param string $value
     **/
    public function SetAmount($value) {
        $this->values['amount'] = $value;
    }

    /**
     * 获取打款金额的值
     * @return 值
     **/
    public function GetAmount() {
        return $this->values['amount'];
    }

    /**
     * 判断打款金额的值是否存在
     * @return true 或 false
     **/
    public function IsAmountSet() {
        return array_key_exists('amount', $this->values);
    }

    /**
     * 设置企业付款操作说明信息
     * @param string $value
     **/
    public function SetDesc($value) {
        $this->values['desc'] = $value;
    }

    /**
     * 获取企业付款操作说明信息的值
     * @return 值
     **/
    public function GetDesc() {
        return $this->values['desc'];
    }

    /**
     * 判断企业付款操作说明信息是否存在
     * @return true 或 false
     **/
    public function IsDescSet() {
        return array_key_exists('desc', $this->values);
    }
}