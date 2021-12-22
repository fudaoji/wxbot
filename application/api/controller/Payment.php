<?php
/**
 * Created by PhpStorm.
 * Script Name: Payment.php
 * Create: 2020/9/21 15:35
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\api\controller;

use app\api\helper\PaymentHelper;
use Exception;
use ky\ErrorCode;
use ky\Helper;
use ky\Payment as Pay;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\facade\Log;

class Payment extends Base
{
    /**
     * 初始化
     * Author: Jason<dcq@kuryun.cn>
     */
    public function initialize() {
        parent::initialize();
    }

    /**
     * 支付商品订单
     * @throws DataNotFoundException|ModelNotFoundException|DbException|Exception
     */
    public function payForGoodsOrder()
    {
        // 权限校验
        PaymentHelper::payForGoodsOrderValid();
        $orderInfo = Helper::$param['order'];
        if ($orderInfo['user_id'] !== $this->userInfo['id']) {
            Helper::error(ErrorCode::IlleglOperation, '非法操作');
        }

        // 微信支付（生成带签名支付信息）
        try {
            $params = [
                'openid'       => $this->userInfo['openid'],
                'body'         => '用户备注：' . $orderInfo['user_note'],
                'out_trade_no' => $orderInfo['order_no'],
                'total_fee'    => config('app_debug') ? 1 : $orderInfo['actual_price'],
                'notify_url'   => request()->domain() . url('api/onmessage/orderGoodsCallback')
            ];
            $paymentAPI = new Pay(get_pay_config());
            $parameters = $paymentAPI->pay($params);
        } catch (Exception $e) {
            Log::error('生成支付参数失败，失败原因：' . json_encode($e->getMessage(), JSON_UNESCAPED_UNICODE));
            $parameters = false;
        }

        if ($parameters !== false) {
            Helper::success('success', ['parameters' => $parameters]);
        }

        Helper::error(ErrorCode::BadParam, '系统繁忙，请稍后再试');
    }

    /**
     * 支付商城订单
     * Author: Jason<dcq@kuryun.cn>
     */
    public function payOrderGoodsPost() {
        PaymentHelper::payOrderGoodsPostValid();
        $order = Helper::$param['order'];
        if($order['user_id'] != $this->userInfo['id']) {
            Helper::error(ErrorCode::BadParam, '非法操作');
        }
        //更新订单支付方式
        model('OrderGoods')->updateOne(['id' => $order['id'], 'pay_way' => Helper::$param['pay_way']]);
        //余额支付
        if(Helper::$param['pay_way']) {
            $result = controller('Order', 'event')->writeOffOrderGoods($order);
            if($result) {
                Helper::success('success', ['result' => $result]);
            }else {
                Helper::error(ErrorCode::BadParam, '余额支付失败');
            }
        }else {
            //微信支付
            try{
                $params = [
                    'openid'        => $order['openid'],
                    'body'          => $order['body'],
                    'out_trade_no'  => $order['order_no'],
                    'total_fee'     => config('app_debug') ? 1 : $order['amount'],
                    'notify_url'    => request()->domain() . url('api/onmessage/orderGoodsCallback')
                ];

                $pay_wx_api = new Pay(get_pay_config());
                $parameters = $pay_wx_api->pay($params);
            }catch (\Exception $e) {
                Log::error('生成支付参数失败，失败原因：' . json_encode($e->getMessage(), JSON_UNESCAPED_UNICODE));
                $parameters = false;
            }

            if($parameters) {
                unset($order, $pay_wx_api, $params);
                Helper::success('success', ['parameters' => $parameters]);
            }else {
                Helper::error(ErrorCode::BadParam, '系统繁忙，请稍后再试');
            }
        }
    }
}