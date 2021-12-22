<?php
/**
 * Script name: AlipayTradeRefundContentBuilder.php
 * Created by PhpStorm.
 * Create: 2017-11-01 16:09
 * Description: 支付宝电脑网站支付退款接口(alipay.trade.refund)接口业务参数封装
 * Author: Jason<1589856452@qq.com>
 */
namespace ky\Payment\Ali\type\pagepay;

class AlipayTradeRefundContentBuilder
{
    // 商户订单号.
    private $outTradeNo;

    // 支付宝交易号
    private $tradeNo;

    // 退款的金额
    private $refundAmount;

    // 退款原因说明
    private $refundReason;

    // 标识一次退款请求号，同一笔交易多次退款保证唯一，部分退款此参数必填
    private $outRequestNo;

    private $bizContentarr = array();

    private $bizContent = NULL;

    public function getBizContent() {
        if(!empty($this->bizContentarr)){
            $this->bizContent = json_encode($this->bizContentarr,JSON_UNESCAPED_UNICODE);
        }
        return $this->bizContent;
    }

    public function getTradeNo() {
        return $this->tradeNo;
    }

    public function setTradeNo($tradeNo) {
        $this->tradeNo = $tradeNo;
        $this->bizContentarr['trade_no'] = $tradeNo;
    }

    public function getOutTradeNo() {
        return $this->outTradeNo;
    }

    public function setOutTradeNo($outTradeNo) {
        $this->outTradeNo = $outTradeNo;
        $this->bizContentarr['out_trade_no'] = $outTradeNo;
    }

    public function getRefundAmount() {
        return $this->refundAmount;
    }

    public function setRefundAmount($refundAmount) {
        $this->refundAmount = $refundAmount;
        $this->bizContentarr['refund_amount'] = $refundAmount;
    }

    public function getRefundReason() {
        return $this->refundReason;
    }

    public function setRefundReason($refundReason) {
        $this->refundReason = $refundReason;
        $this->bizContentarr['refund_reason'] = $refundReason;
    }

    public function getOutRequestNo() {
        return $this->outRequestNo;
    }

    public function setOutRequestNo($outRequestNo) {
        $this->outRequestNo = $outRequestNo;
        $this->bizContentarr['out_request_no'] = $outRequestNo;
    }
}