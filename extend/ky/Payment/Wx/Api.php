<?php
/**
 * 微信支付API
 * 接口访问类，包含所有微信支付API列表的封装，类中方法为static方法，
 * 每个接口有默认超时时间（除提交被扫支付为10s，上报超时时间为1s外，其他均为6s）
 * @author Doogie<461960962@qq.com>
 */
namespace ky\Payment\Wx;

use ky\ErrorCode;
use ky\Logger;
use ky\Payment\Wx\Data\GetPublicKey;
use ky\Payment\Wx\Data\PayBank;
use ky\Payment\Wx\Data\PayJsApiPay;
use ky\Payment\Wx\Data\PayOrderQuery;
use ky\Payment\Wx\Data\PayRefund;
use ky\Payment\Wx\Data\PayReport;
use ky\Payment\Wx\Data\PayResults;
use ky\Payment\Wx\Data\TransferPromotion;

class Api
{
    /**
     * 配置参数
     * @var array
     */
    public $config = [
       	'appid'             => 'wx80a31b73e1a7e7ca',
        'appsecret'         => '394a4a39672774417f66b54bb733d174',
        'mchid'             => '1465001002', //商户号
        'key'               => 'Tm44FMGt484Y44TjV34V3VG2ZEG4pf4f', //API秘钥
        'sslcert_path'      => 'cert/apiclient_cert.pem', //证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载
        'sslkey_path'       => 'cert/apiclient_key.pem', //证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载
        'curl_proxy_host'   => '0.0.0.0', //这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0
        'curl_proxy_port'   => 0, //代理机器端口
        'report_level'      => 1, //信息上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
        'notify_url'        => 'NOTIFY_URL',
		'rsa_path'          => '/mydata/www/kyframework/extend/ky/Payment/Wx/cert/public.pem', //RSA加密公钥路径
    ];

    /**
     * 接口URL统一管理
     * @var array
     */
    private $apiUrl = [
        'orderquery'        => 'https://api.mch.weixin.qq.com/pay/orderquery',
        'report'            => 'https://api.mch.weixin.qq.com/payitil/report',
        'unifiedorder'      => "https://api.mch.weixin.qq.com/pay/unifiedorder",
        'refund'            => "https://api.mch.weixin.qq.com/secapi/pay/refund",
		'transferpromotion' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers',
		'paybank'           => 'https://api.mch.weixin.qq.com/mmpaysptrans/pay_bank',
		'getpublickey'      => 'https://fraud.mch.weixin.qq.com/risk/getpublickey',
    ];

    /**
     * 交易类型
     * @var array
     */
    private $tradeType = [
        'JSAPI'     => 'WX_JSAPI',
        'NATIVE'    => 'WX_NATIVE',
        'APP'       => 'WX_APP'
    ];

    public function __construct($config = []) {
        $config && $this->config = array_merge($this->config, $config);
        if(empty($this->config)){
            Logger::setMsgAndCode('缺少微信支付配置参数');
        }
    }

    /**
     * 使用 $this->name 获取配置
     * @param  string $name 配置名称
     * @return mixed    配置值
     */
    public function __get($name) {
        return $this->config[$name];
    }

    /**
     * 设置配置值
     * @param $name
     * @param $value
     * @Author  Doogie<461960962@qq.com>
     */
    public function __set($name,$value){
        if(isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    /**
     * 直接输出xml
     * @param bool $result 成功或失败
     * @param bool $needSign
     */
    public function replyNotify($result, $needSign = true){
        $pay_notify = new PayNotify();
        echo $pay_notify->replyNotifyData($result, $needSign);
    }

    /**
     * 支付结果回调接收器
     * 直接回调函数使用方法: notify(you_function);
     * 回调类成员函数方法:notify(array($this, you_function));
     * @return array $data 根据ky_pay_result来判断是否成功支付
     * @throws \Exception
     */
    public function notify() {
        //获取通知的数据
        //$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $xml = file_get_contents("php://input");
        //如果返回成功则验证签名
        try {
            $result = PayResults::Init($xml, $this->config['key']);
        } catch (\Exception $e){
            Logger::setMsgAndCode(json_encode($e->getMessage()), ErrorCode::WxpayException);
        }

        $data = $result;
        if(!array_key_exists("transaction_id", $data)){
            Logger::setMsgAndCode("输入参数不正确", ErrorCode::WxpayException);
        }
        //查询订单，判断订单真实性
        $result = $this->orderQuery($data);

        if(array_key_exists("return_code", $result) && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
            $data['ky_pay_result'] = true;
        }else{
            $data['ky_pay_result'] = false;
        }
        $data['channel'] = $this->tradeType[$data['trade_type']];
        return $data;
    }

    /**
     *
     * 获取jsapi支付的参数
     * @param \ky\Payment\Wx\Data\PayUnifiedOrder $unified_order_result
     * @throws \Exception
     * @return array
     */
    public function getJsApiParameters($unified_order_result) {
        if(!array_key_exists("appid", $unified_order_result)
            || !array_key_exists("prepay_id", $unified_order_result)
            || $unified_order_result['prepay_id'] == "") {
            Logger::setMsgAndCode("参数错误", ErrorCode::WxpayException);
        }
        $jsapi = new PayJsApiPay();
        $jsapi->SetAppid($unified_order_result["appid"]);
        $timeStamp = time();
        $jsapi->SetTimeStamp("$timeStamp");
        $jsapi->SetNonceStr(self::getNonceStr());
        $jsapi->SetPackage("prepay_id=" . $unified_order_result['prepay_id']);
        $jsapi->SetSignType("MD5");
        $jsapi->SetPaySign($jsapi->MakeSign($this->config['key']));
        return $jsapi->GetValues();
    }

    /**
     *
     * 统一下单，WxPayUnifiedOrder中out_trade_no、body、total_fee、trade_type必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param \ky\Payment\Wx\Data\PayUnifiedOrder $inputObj
     * @param int $timeOut
     * @throws \Exception
     * @return 成功时返回，其他抛异常
     */
    public function unifiedOrder($inputObj, $timeOut = 6) {
        //检测必填参数
        if(!$inputObj->IsOut_trade_noSet()) {
            Logger::setMsgAndCode("缺少统一支付接口必填参数out_trade_no！", ErrorCode::WxpayException);
        }else if(!$inputObj->IsBodySet()){
            Logger::setMsgAndCode("缺少统一支付接口必填参数body！", ErrorCode::WxpayException);
        }else if(!$inputObj->IsTotal_feeSet()) {
            Logger::setMsgAndCode("缺少统一支付接口必填参数total_fee！", ErrorCode::WxpayException);
        }else if(!$inputObj->IsTrade_typeSet()) {
            Logger::setMsgAndCode("缺少统一支付接口必填参数trade_type！", ErrorCode::WxpayException);
        }

        //关联参数
        if($inputObj->GetTrade_type() == "JSAPI" && !$inputObj->IsOpenidSet()){
            Logger::setMsgAndCode("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！", ErrorCode::WxpayException);
        }
        if($inputObj->GetTrade_type() == "NATIVE" && !$inputObj->IsProduct_idSet()){
            Logger::setMsgAndCode("统一支付接口中，缺少必填参数product_id！trade_type为JSAPI时，product_id为必填参数！", ErrorCode::WxpayException);
        }

        //异步通知url未设置，则使用配置文件中的url
        if(!$inputObj->IsNotify_urlSet()){
            $inputObj->SetNotify_url($this->config['notify_url']);//异步通知url
        }

        $inputObj->SetAppid($this->config['appid']);//公众账号ID
        $inputObj->SetMch_id($this->config['mchid']);//商户号
        $inputObj->SetSpbill_create_ip(self::getClientIp());//终端ip
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetTime_start(date("YmdHis"));
        $inputObj->SetTime_expire(date("YmdHis", time() + 600));
        //签名
        $inputObj->SetSign($this->config['key']);
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $this->apiUrl['unifiedorder'], false, $timeOut);
        $result = PayResults::Init($response, $this->config['key']);
        self::reportCostTime($this->apiUrl['unifiedorder'], $startTimeStamp, $result);//上报请求花费时间
        if(strtolower($result['return_code']) === 'fail'){
            Logger::setMsgAndCode("通信失败，失败原因：".$result['return_msg'], ErrorCode::WxpayException);
        }
        if(strtolower($result['result_code']) === 'fail'){
            Logger::setMsgAndCode("统一下单失败，失败原因：".$result['err_code_des'], ErrorCode::WxpayException);
        }
        return $result;
    }

    /**
     * 以post方式提交xml到对应的接口url
     *
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws \Exception
     * @return  array
     */
    private function postXmlCurl($xml, $url, $useCert = false, $second = 30) {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        //如果有配置代理这里就设置代理
        if($this->config['curl_proxy_host'] != "0.0.0.0" && $this->config['curl_proxy_port'] != 0){
            curl_setopt($ch,CURLOPT_PROXY, $this->config['curl_proxy_host']);
            curl_setopt($ch,CURLOPT_PROXYPORT, $this->config['curl_proxy_port']);
        }
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($useCert == true){//dump(dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->config['sslcert_path']);exit;
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, $this->config['sslcert_path']);
            //curl_setopt($ch,CURLOPT_SSLCERT, dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->config['sslcert_path']);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
	    curl_setopt($ch,CURLOPT_SSLKEY, $this->config['sslkey_path']);
	    //curl_setopt($ch,CURLOPT_SSLKEY, dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->config['sslkey_path']);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            Logger::setMsgAndCode("curl出错，错误码:$error", ErrorCode::WxpayException);
        }
    }

    /**
     * 获取毫秒级别的时间戳
     */
    private static function getMillisecond(){
        //获取毫秒的时间戳
        $time = explode ( " ", microtime () );
        $time = $time[1] . ($time[0] * 1000);
        $time2 = explode( ".", $time );
        $time = $time2[0];
        return $time;
    }

    /**
     *
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return string 产生的随机字符串
     */
    public static function getNonceStr($length = 32) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }

    /**
     *
     * 上报数据， 上报的时候将屏蔽所有异常流程
     * @param string $url
     * @param int $startTimeStamp
     * @param array $data
     */
    private function reportCostTime($url, $startTimeStamp, $data) {
        //如果不需要上报数据
        if($this->config['report_level'] == 0){
            return;
        }
        //如果仅失败上报
        if($this->config['report_level'] == 1 &&
            array_key_exists("return_code", $data) &&
            $data["return_code"] == "SUCCESS" &&
            array_key_exists("result_code", $data) &&
            $data["result_code"] == "SUCCESS")
        {
            return;
        }

        //上报逻辑
        $endTimeStamp = self::getMillisecond();
        $objInput = new PayReport();
        $objInput->SetInterface_url($url);
        $objInput->SetExecute_time_($endTimeStamp - $startTimeStamp);
        //返回状态码
        if(array_key_exists("return_code", $data)){
            $objInput->SetReturn_code($data["return_code"]);
        }
        //返回信息
        if(array_key_exists("return_msg", $data)){
            $objInput->SetReturn_msg($data["return_msg"]);
        }
        //业务结果
        if(array_key_exists("result_code", $data)){
            $objInput->SetResult_code($data["result_code"]);
        }
        //错误代码
        if(array_key_exists("err_code", $data)){
            $objInput->SetErr_code($data["err_code"]);
        }
        //错误代码描述
        if(array_key_exists("err_code_des", $data)){
            $objInput->SetErr_code_des($data["err_code_des"]);
        }
        //商户订单号
        if(array_key_exists("out_trade_no", $data)){
            $objInput->SetOut_trade_no($data["out_trade_no"]);
        }
        //设备号
        if(array_key_exists("device_info", $data)){
            $objInput->SetDevice_info($data["device_info"]);
        }

        try{
            $this->report($objInput);
        } catch (\Exception $e){
            //不做任何处理
        }
    }

    /**
     *
     * 查询订单，WxPayOrderQuery中out_trade_no、transaction_id至少填一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param array $params
     * @param int $timeOut
     * @throws \Exception
     * @return mixed 成功时返回，其他抛异常
     */
    public function orderQuery($params = [], $timeOut = 6) {
        //检测必填参数
        $inputObj = new PayOrderQuery();
        if(! empty($params["transaction_id"])){
            $inputObj->SetTransaction_id($params['transaction_id']);
        }elseif (! empty($params["out_trade_no"])){
            $inputObj->SetOut_trade_no($params['out_trade_no']);
        }else{
            Logger::setMsgAndCode("订单查询接口中，out_trade_no、transaction_id至少填一个！", ErrorCode::WxpayException);
        }

        $inputObj->SetAppid($this->config['appid']);//公众账号ID
        $inputObj->SetMch_id($this->config['mchid']);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign($this->config['key']);//签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $this->apiUrl['orderquery'], false, $timeOut);
        $result = PayResults::Init($response, $this->config['key']);
        self::reportCostTime($this->apiUrl['orderquery'], $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }

    /**
     *
     * 测速上报，该方法内部封装在report中，使用时请注意异常流程
     * WxPayReport中interface_url、return_code、result_code、user_ip、execute_time_必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param \ky\Payment\Wx\Data\PayReport $inputObj
     * @param int $timeOut
     * @throws \Exception
     * @return array 成功时返回，其他抛异常
     */
    public function report($inputObj, $timeOut = 1) {
        //检测必填参数
        if(!$inputObj->IsInterface_urlSet()) {
            Logger::setMsgAndCode("接口URL，缺少必填参数interface_url！", ErrorCode::WxpayException);
        } if(!$inputObj->IsReturn_codeSet()) {
            Logger::setMsgAndCode("返回状态码，缺少必填参数return_code！", ErrorCode::WxpayException);
        } if(!$inputObj->IsResult_codeSet()) {
            Logger::setMsgAndCode("业务结果，缺少必填参数result_code！", ErrorCode::WxpayException);
        } if(!$inputObj->IsUser_ipSet()) {
            Logger::setMsgAndCode("访问接口IP，缺少必填参数user_ip！", ErrorCode::WxpayException);
        } if(!$inputObj->IsExecute_time_Set()) {
            Logger::setMsgAndCode("接口耗时，缺少必填参数execute_time_！", ErrorCode::WxpayException);
        }
        $inputObj->SetAppid($this->config['appid']);//公众账号ID
        $inputObj->SetMch_id($this->config['mchid']);//商户号
        $inputObj->SetUser_ip(get_client_ip());//终端ip
        $inputObj->SetTime(date("YmdHis"));//商户上报时间
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign($this->config['key']);//签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond();//请求开始时间
        return self::postXmlCurl($xml, $this->apiUrl['report'], false, $timeOut);
    }

    /**
     *
     * 关闭订单，WxPayCloseOrder中out_trade_no必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayCloseOrder $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function closeOrder($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/pay/closeorder";
        //检测必填参数
        if(!$inputObj->IsOut_trade_noSet()) {
            throw new WxPayException("订单查询接口中，out_trade_no必填！");
        }
        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign(WxPayConfig::KEY);//签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::Init($response, WxPayConfig::KEY);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }

    /**
     *
     * 申请退款，WxPayRefund中out_trade_no、transaction_id至少填一个且
     * out_refund_no、total_fee、refund_fee、op_user_id为必填参数
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param array $params
     * @param int $timeOut
     * @throws \Exception
     * @return mixed 成功时返回，其他抛异常
     */
    public function refund($params = [], $timeOut = 6)
    {
        //检测必填参数
        $inputObj = new PayRefund();
        if(isset($params['out_trade_no'])){
            $inputObj->SetOut_trade_no($params['out_trade_no']);
        }elseif(isset($params['transaction_id'])){
            $inputObj->SetTransaction_id($params['transaction_id']);
        }else{
            Logger::setMsgAndCode('退款申请接口中，out_trade_no、transaction_id至少填一个！', ErrorCode::WxpayException);
        }

        if(!isset($params['out_refund_no'], $params['total_fee'], $params['refund_fee'])){
            Logger::setMsgAndCode('缺少必填参数', ErrorCode::WxpayException);
        }

        $inputObj->SetOut_refund_no($params['out_refund_no']);
        $inputObj->SetTotal_fee($params['total_fee']);
        $inputObj->SetRefund_fee($params['refund_fee']);
        $inputObj->SetOp_user_id($this->config['mchid']);
        $inputObj->SetAppid($this->config['appid']);//公众账号ID
        $inputObj->SetMch_id($this->config['mchid']);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign($this->config['key']);//签名
        $xml = $inputObj->ToXml();
        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $this->apiUrl['refund'], true, $timeOut);
        $result = PayResults::Init($response, $this->config['key']);
        self::reportCostTime($this->apiUrl['refund'], $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }

    /**
     *
     * 查询退款
     * 提交退款申请后，通过调用该接口查询退款状态。退款有一定延时，
     * 用零钱支付的退款20分钟内到账，银行卡支付的退款3个工作日后重新查询退款状态。
     * WxPayRefundQuery中out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayRefundQuery $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function refundQuery($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/pay/refundquery";
        //检测必填参数
        if(!$inputObj->IsOut_refund_noSet() &&
            !$inputObj->IsOut_trade_noSet() &&
            !$inputObj->IsTransaction_idSet() &&
            !$inputObj->IsRefund_idSet()) {
            throw new WxPayException("退款查询接口中，out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个！");
        }
        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign(WxPayConfig::KEY);//签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::Init($response, WxPayConfig::KEY);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }

    /**
     * 下载对账单，WxPayDownloadBill中bill_date为必填参数
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayDownloadBill $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function downloadBill($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/pay/downloadbill";
        //检测必填参数
        if(!$inputObj->IsBill_dateSet()) {
            throw new WxPayException("对账单接口中，缺少必填参数bill_date！");
        }
        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign(WxPayConfig::KEY);//签名
        $xml = $inputObj->ToXml();

        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        if(substr($response, 0 , 5) == "<xml>"){
            return "";
        }
        return $response;
    }

    /**
     * 提交被扫支付API
     * 收银员使用扫码设备读取微信用户刷卡授权码以后，二维码或条码信息传送至商户收银台，
     * 由商户收银台或者商户后台调用该接口发起支付。
     * WxPayWxPayMicroPay中body、out_trade_no、total_fee、auth_code参数必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayWxPayMicroPay $inputObj
     * @param int $timeOut
     */
    public static function micropay($inputObj, $timeOut = 10)
    {
        $url = "https://api.mch.weixin.qq.com/pay/micropay";
        //检测必填参数
        if(!$inputObj->IsBodySet()) {
            throw new WxPayException("提交被扫支付API接口中，缺少必填参数body！");
        } else if(!$inputObj->IsOut_trade_noSet()) {
            throw new WxPayException("提交被扫支付API接口中，缺少必填参数out_trade_no！");
        } else if(!$inputObj->IsTotal_feeSet()) {
            throw new WxPayException("提交被扫支付API接口中，缺少必填参数total_fee！");
        } else if(!$inputObj->IsAuth_codeSet()) {
            throw new WxPayException("提交被扫支付API接口中，缺少必填参数auth_code！");
        }

        $inputObj->SetSpbill_create_ip($_SERVER['REMOTE_ADDR']);//终端ip
        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign(WxPayConfig::KEY);//签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::Init($response, WxPayConfig::KEY);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }

    /**
     *
     * 撤销订单API接口，WxPayReverse中参数out_trade_no和transaction_id必须填写一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayReverse $inputObj
     * @param int $timeOut
     * @throws WxPayException
     */
    public static function reverse($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/secapi/pay/reverse";
        //检测必填参数
        if(!$inputObj->IsOut_trade_noSet() && !$inputObj->IsTransaction_idSet()) {
            throw new WxPayException("撤销订单API接口中，参数out_trade_no和transaction_id必须填写一个！");
        }

        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign(WxPayConfig::KEY);//签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, true, $timeOut);
        $result = WxPayResults::Init($response, WxPayConfig::KEY);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }

    /**
     *
     * 生成二维码规则,模式一生成支付二维码
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayBizPayUrl $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function bizpayurl($inputObj, $timeOut = 6)
    {
        if(!$inputObj->IsProduct_idSet()){
            throw new WxPayException("生成二维码，缺少必填参数product_id！");
        }

        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetTime_stamp(time());//时间戳
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign(WxPayConfig::KEY);//签名

        return $inputObj->GetValues();
    }

    /**
     *
     * 转换短链接
     * 该接口主要用于扫码原生支付模式一中的二维码链接转成短链接(weixin://wxpay/s/XXXXXX)，
     * 减小二维码数据量，提升扫描速度和精确度。
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayShortUrl $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function shorturl($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/tools/shorturl";
        //检测必填参数
        if(!$inputObj->IsLong_urlSet()) {
            throw new WxPayException("需要转换的URL，签名用原串，传输需URL encode！");
        }
        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign(WxPayConfig::KEY);//签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::Init($response, WxPayConfig::KEY);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }

    /**
     * 企业付款到零钱
     * @param array $params
     * @param int $timeOut
     * @return array
     * Author: Doogie<fdj@kuryun.cn>
     * @throws \Exception
     */
	public function transferPromotion($params = [], $timeOut = 6){
		//检测必填参数
		$inputObj = new TransferPromotion();
		if(isset($params['check_name']) && $params['check_name']){
			$inputObj->SetCheck_name('FORCE_CHECK');
		}else{
			$inputObj->SetCheck_name('NO_CHECK');
		}
		if($inputObj->GetCheck_name() == 'FORCE_CHECK' && isset($params['re_user_name'])){
			$inputObj->SetRe_user_name($params['re_user_name']);
		}else{
		//	Logger::setMsgAndCode('退款申请接口中，check_name为FORCE_CHECK时re_user_name必填！', ErrorCode::WxpayException);
		}

		if(!isset($params['partner_trade_no'], $params['openid'], $params['amount'], $params['desc'])){
			Logger::setMsgAndCode('缺少必填参数', ErrorCode::WxpayException);
		}

		$inputObj->SetMch_Appid($this->config['appid']);//公众账号ID
		$inputObj->SetMchid($this->config['mchid']);//商户号
		$inputObj->SetPartner_trade_no($params['partner_trade_no']);
		$inputObj->SetOpenid($params['openid']);
		$inputObj->SetAmount($params['amount']);
		$inputObj->SetDesc($params['desc']);
		$inputObj->SetSpbill_create_ip(self::getClientIp());
		$inputObj->SetNonce_str(self::getNonceStr());//随机字符串

		$inputObj->SetSign($this->config['key']);//签名
		$xml = $inputObj->ToXml();
		$startTimeStamp = self::getMillisecond();//请求开始时间
		$response = self::postXmlCurl($xml, $this->apiUrl['transferpromotion'], true, $timeOut);
		$result = $inputObj->FromXml($response);
		self::reportCostTime($this->apiUrl['transferpromotion'], $startTimeStamp, $result);//上报请求花费时间

		return $result;
	}

    /**
     * 企业付款到银行卡
     * @param array $params
     * @param int $timeOut
     * @return array
     * @throws \Exception
     * @author Jason<1589856452@qq.com>
     */
	public function payBank($params = [], $timeOut = 6){
		//检测必填参数
		$inputObj = new PayBank();
		if(!isset($params['partner_trade_no'], $params['enc_bank_no'], $params['enc_true_name'], $params['bank_code'], $params['amount'], $params['desc'])){
			Logger::setMsgAndCode('缺少必填参数', ErrorCode::WxpayException);
		}

		$inputObj->SetMch_id($this->config['mchid']);//商户号
		$inputObj->SetPartner_trade_no($params['partner_trade_no']);
		$inputObj->SetEnc_bank_no(self::getRsaEncrypt($params['enc_bank_no']));
		$inputObj->SetEnc_true_name(self::getRsaEncrypt($params['enc_true_name']));
		$inputObj->SetBank_code($params['bank_code']);
		$inputObj->SetAmount($params['amount']);
		$inputObj->SetDesc($params['desc']);
		$inputObj->SetNonce_str(self::getNonceStr());//随机字符串

		$inputObj->SetSign($this->config['key']);//签名
		$xml = $inputObj->ToXml();
		$startTimeStamp = self::getMillisecond();//请求开始时间
		$response = self::postXmlCurl($xml, $this->apiUrl['paybank'], true, $timeOut);
		$result = $inputObj->FromXml($response);
		self::reportCostTime($this->apiUrl['paybank'], $startTimeStamp, $result);//上报请求花费时间

		return $result;
	}

	/**
	 * 获取客户端IP地址
	 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
	 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
	 * @return mixed
	 */
	public static function getClientIp($type = 0, $adv=false) {
		$type       =  $type ? 1 : 0;
		static $ip  =   NULL;
		if ($ip !== NULL) return $ip[$type];
		if($adv){
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
				$pos    =   array_search('unknown',$arr);
				if(false !== $pos) unset($arr[$pos]);
				$ip     =   trim($arr[0]);
			}elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
				$ip     =   $_SERVER['HTTP_CLIENT_IP'];
			}elseif (isset($_SERVER['REMOTE_ADDR'])) {
				$ip     =   $_SERVER['REMOTE_ADDR'];
			}
		}elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$ip     =   $_SERVER['REMOTE_ADDR'];
		}
		// IP地址合法验证
		$long = sprintf("%u",ip2long($ip));
		$ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
		return $ip[$type];
	}

	/**
	 * 获取RSA加密公钥
	 * @param int $timeOut
	 * @return string $result
	 * @author Jason<1589856452@qq.com>
	 */
	public function getPublicKey($timeOut = 6) {
		$inputObj = new GetPublicKey();

		$inputObj->SetMch_id($this->config['mchid']);//商户号
		$inputObj->SetSign_type('MD5');
		$inputObj->SetNonce_str(self::getNonceStr());//随机字符串

		$inputObj->SetSign($this->config['key']);//签名
		$xml = $inputObj->ToXml();
		$startTimeStamp = self::getMillisecond();//请求开始时间
		$response = self::postXmlCurl($xml, $this->apiUrl['getpublickey'], true, $timeOut);
		$result = $inputObj->FromXml($response);
		self::reportCostTime($this->apiUrl['getpublickey'], $startTimeStamp, $result);//上报请求花费时间

		return $result;
	}

	/**
	 * 获取以RSA公钥加密并转base64之后的密文
	 * @param string $str
	 * @return string
	 * @author Jason<1589856452@qq.com>
	 */
	public function getRsaEncrypt($str) {
		$pub_key = openssl_pkey_get_public(file_get_contents($this->config['rsa_path']));  //读取公钥内容
		$encrypted_block = '';
		$encrypted = '';
		//用标准的RSA加密库对敏感信息进行加密，选择RSA_PKCS1_OAEP_PADDING填充模式
		openssl_public_encrypt($str, $encrypted_block, $pub_key,OPENSSL_PKCS1_OAEP_PADDING);
		//得到进行rsa加密并转base64之后的密文
		$str_base64 = base64_encode($encrypted.$encrypted_block);

		return $str_base64;
	}
}

