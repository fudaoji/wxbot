<?php
// +----------------------------------------------------------------------
// | [KyPHP System] Copyright (c) 2020 http://www.kuryun.com/
// +----------------------------------------------------------------------
// | [KyPHP] 并不是自由软件,你可免费使用,未经许可不能去掉KyPHP相关版权
// +----------------------------------------------------------------------
// | Author: fudaoji <fdj@kuryun.cn>
// +----------------------------------------------------------------------

/**
 * Created by PhpStorm.
 * Script Name: Payment.php
 * Create: 2017/06/15 下午3:19
 * Description: 各渠道支付
 * Author: Doogie<461960962@qq.com>
 */

namespace ky;

use ky\Payment\Wx\Api as WxApi;
use ky\Payment\Ali\Api as AliApi;
use ky\Payment\Wx\Data\PayUnifiedOrder;
use ky\Payment\Ali\type\pagepay\AlipayTradePagePayContentBuilder;

class Payment
{
    const WX_JSAPI = 'WX_JSAPI';
    const WX_NATIVE = 'WX_NATIVE';
    const WX_APP = 'WX_APP';
    const ALI_PAGE = 'ALI_PAGE';
    const ALI_WAP = 'ALI_WAP';

    protected $api;
    protected $driver;
    protected $error;

    /**
     * 交易类型
     * @var array
     */
    private $tradeType = [
        'WX_JSAPI'  => 'JSAPI',
        'WX_NATIVE' => 'NATIVE',
        'WX_APP'    => 'APP',
        'ALI_PAGE'  => 'PAGEPAY',
        'ALI_WAP'   => 'WAPPAY',
    ];

    public function __construct($config=[], $driver='')
    {
        empty($driver) && $driver = config('pay_type') ? config('pay_type') : 'wx';
        $this->driver = ucfirst(strtolower($driver));
        $class = '\\ky\\Payment\\' . $this->driver . '\\Api';
        $this->api = new $class($config);
        if(!$this->api){
            throw new \Exception("不存在支付驱动：{$driver}");
        }
        return $this->api;
    }

    /**
     * 支付
     * @param $params
     * @return array
     * Author: Doogie<fdj@kuryun.cn>
     * @throws \Exception
     */
    public function pay($params) {
        $channel = empty($params['channel']) ? self::WX_JSAPI : $params['channel'];
        $channel = strtoupper($channel);
        switch ($channel){
            case self::WX_NATIVE: //原生扫码支付
                $mode = empty($params['mode']) ? '2' : $params['mode'];
                if($mode == 2) {
                    //扫码模式二
                    if(! isset($params['body'], $params['out_trade_no'], $params['total_fee'], $params['product_id'], $params['notify_url'])) {
                        logger('支付参数不完整', ErrorCode::WxpayException);
                    }
                    $input = new PayUnifiedOrder();
                    $input->setBody($params['body']);
                    $input->SetProduct_id($params['product_id']);
                    $input->SetOut_trade_no($params['out_trade_no']);
                    $input->SetTotal_fee($params['total_fee']);
                    $input->SetNotify_url($params['notify_url']);
                    $input->SetTrade_type($this->tradeType[$channel]);
                    if(isset($params['attach'])){
                        $input->SetAttach($params['attach']);
                    }
                    $res = $this->api->unifiedOrder($input);
                }else {
                    //扫码模式一
                }
                break;
            case self::WX_APP: //微信APP
                break;
            case self::ALI_PAGE: //支付宝电脑网站支付
                if(! isset($params['out_trade_no'], $params['total_amount'], $params['subject'], $params['body'], $params['return_url'], $params['notify_url'])) {
                    logger('业务请求参数不完整', ErrorCode::AlipayException);
                }
                $input = new AlipayTradePagePayContentBuilder();

                //业务请求参数构建
                $input->setOutTradeNo($params['out_trade_no']);
                $input->setTotalAmount($params['total_amount']);
                $input->setSubject($params['subject']);
                $input->setBody($params['body']);

                //发起电脑网站支付
                $res = $this->api->pagePay($input, $params['return_url'], $params['notify_url']);
                break;
            case self::ALI_WAP: //支付宝手机网站支付
                break;
            default:
                if(!isset($params['body'], $params['out_trade_no'], $params['total_fee'], $params['notify_url'], $params['openid'])){
                    logger('支付参数不完整', ErrorCode::WxpayException);
                }
                $input = new PayUnifiedOrder();
                $input->SetBody($params['body']);
                // 附加数据，在查询API和支付通知中原样返回，可作为自定义参数使用
                $input->SetOut_trade_no($params['out_trade_no']);
                $input->SetTotal_fee($params['total_fee']);
                $input->SetNotify_url($params['notify_url']);
                $input->SetTrade_type($this->tradeType[$channel]);
                $input->SetOpenid($params['openid']);
                if(isset($params['attach'])){
                    $input->SetAttach($params['attach']);
                }
                $order = $this->api->unifiedOrder($input);
                $res = $this->api->GetJsApiParameters($order);
                break;
        }

        return $res;
    }

    /**
     * 订单查询
     * trade_state 的以下状态
     * SUCCESS—支付成功
     * REFUND—转入退款
     * NOTPAY—未支付
     * CLOSED—已关闭
     * REVOKED—已撤销（刷卡支付）
     * USERPAYING--用户支付中
     * PAYERROR--支付失败(其他原因，如银行返回失败)
     * @param $params
     * Author: Doogie<fdj@kuryun.cn>
     * @return
     */
    public function orderQuery($params){
        return $this->api->orderQuery($params);
    }

    /**
     * 支付结果回调接收器
     * @return mixed
     * @author: Doogie<461960962@qq.com>
     */
    public function notify(){
        return $this->api->notify();
    }

    /**
     * 告知微信服务器接收情况
     * @param $data
     * @author: Doogie<461960962@qq.com>
     */
    public function replyNotify($data){
        switch($data['channel']){
            case self::WX_JSAPI:
                if($data['ky_pay_result'] === true){
                    $this->api->replyNotify(true);
                }else{
                    $this->api->replyNotify(false, false);
                }
            break;
        }
    }

    /**
     * 申请退款
     * @param $params
     * @return mixed
     * Author: Doogie<fdj@kuryun.cn>
     */
    public function refund($params){
        switch($params['channel']){
            case self::WX_JSAPI:
                $res = $this->api->refund($params);
                if($res['result_code'] === 'SUCCESS' && $res['return_code'] === 'SUCCESS'){
                    $res['ky_refund_result'] = true;
                }else{
                    $res['ky_refund_result'] = false;
                }
                break;
        }
        return $res;
    }

	/**
	 * 企业打款
	 * @param $params
	 * @return mixed
	 * Author: Doogie<fdj@kuryun.cn>
	 */
	public function transfer($params){
		$res = $this->api->transferPromotion($params);
		if($res['result_code'] === 'SUCCESS' && $res['return_code'] === 'SUCCESS'){
			$res['ky_transfer_result'] = true;
		}else{
			$res['ky_transfer_result'] = false;
		}
		return $res;
	}

    /**
     * 验证支付宝的返回信息
     * @param array $param 支付宝异步返回信息
     * @return boolean
     * @author Jason<1589856452@qq.com>
     */
    public function check($param) {
        return $this->api->check($param);
    }

	/**
	 * 获取RSA加密公钥
	 * @return mixed
	 * @author Jason<1589856452@qq.com>
	 */
	public function getPublicKey() {
		return $this->api->getPublicKey();
	}

	/**
	 * 企业打款至银行卡
	 * @param $params
	 * @return mixed
	 * @author Jason<1589856452@qq.com>
	 */
	public function payBank($params) {
		$result = $this->api->payBank($params);
		if($result['result_code'] === 'SUCCESS' && $result['return_code'] === 'SUCCESS'){
			$result['ky_pay_bank_result'] = true;
		}else{
			$result['ky_pay_bank_result'] = false;
		}
		return $result;
	}
}
