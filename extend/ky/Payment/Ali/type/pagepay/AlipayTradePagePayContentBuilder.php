<?php
/**
 * Script name: AlipayTradePagePayContentBuilder.php
 * Created by PhpStorm.
 * Create: 2017-11-01 16:09
 * Description: 支付宝电脑网站支付(alipay.trade.page.pay)接口业务参数封装
 * Author: Jason<1589856452@qq.com>
 */
namespace ky\Payment\Ali\type\pagepay;

class AlipayTradePagePayContentBuilder
{
    // 订单描述，可以对交易或商品进行一个详细地描述，比如填写"购买商品2件共15.00元"
    private $body;

    // 订单标题，粗略描述用户的支付目的。
    private $subject;

    // 商户订单号.
    private $outTradeNo;

    // (推荐使用，相对时间) 该笔订单允许的最晚付款时间，逾期将关闭交易。取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）。 该参数数值不接受小数点， 如 1.5h，可转换为 90m
    private $timeExpress;

    // 订单总金额，整形，此处单位为元，精确到小数点后2位，不能超过1亿元
    private $totalAmount;

    // 产品标示码，固定值：QUICK_WAP_PAY
    private $productCode;

    private $bizContentarr = array();

    private $bizContent = NULL;

    public function getBizContent() {
        if(!empty($this->bizContentarr)){
            $this->bizContent = json_encode($this->bizContentarr,JSON_UNESCAPED_UNICODE);
        }
        return $this->bizContent;
    }

    public function __construct() {
        $this->bizContentarr['product_code'] = "FAST_INSTANT_TRADE_PAY";
    }

    public function AlipayTradeWapPayContentBuilder() {
        $this->__construct();
    }

    public function getBody() {
        return $this->body;
    }

    public function setBody($body) {
        $this->body = $body;
        $this->bizContentarr['body'] = $body;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
        $this->bizContentarr['subject'] = $subject;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function getOutTradeNo() {
        return $this->outTradeNo;
    }

    public function setOutTradeNo($outTradeNo) {
        $this->outTradeNo = $outTradeNo;
        $this->bizContentarr['out_trade_no'] = $outTradeNo;
    }

    public function setTimeExpress($timeExpress) {
        $this->timeExpress = $timeExpress;
        $this->bizContentarr['timeout_express'] = $timeExpress;
    }

    public function getTimeExpress() {
        return $this->timeExpress;
    }

    public function setTotalAmount($totalAmount) {
        $this->totalAmount = $totalAmount;
        $this->bizContentarr['total_amount'] = $totalAmount;
    }

    public function getTotalAmount() {
        return $this->totalAmount;
    }
}