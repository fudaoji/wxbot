<?php
/**
 * Created by PhpStorm.
 * Script Name: PaymentHelper.php
 * Create: 2020/9/21 15:35
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\api\helper;

use Exception;
use ky\ErrorCode;

class PaymentHelper extends BaseHelper
{
    /**
     * 支付会员充值订单参数验证
     * Author: Jason<dcq@kuryun.cn>
     */
    public static function payUserRechargePostValid() {
        if(! isset(self::$ajax['id'])) {
            abort(ErrorCode::ErrorParam, '参数错误');
        }

        if($order = model('OrderRecharge')->getOne(self::$ajax['id'])) {
            self::$param['order'] = $order;
        }else {
            abort(ErrorCode::InvalidParam, 'id非法');
        }

        if($order['paid'] != 0) {
            self::error(ErrorCode::ErrorParam, '无效订单');
        }

        unset($order);
        return true;
    }

    /**
     * @throws Exception
     */
    public static function payForGoodsOrderValid() {
        if(! isset(self::$ajax['order_id'])) {
            logger(ErrorCode::ErrorParam, '参数错误');
        }

        if($order = model('common/Order')->getOne(self::$ajax['order_id'])) {
            self::$param['order'] = $order;
        }else {
            abort(ErrorCode::InvalidParam, 'id非法');
        }

        if ($order['pay_status'] !== 0 || $order['status'] !== 1) {
            self::error(ErrorCode::BadParam, '无效订单');
        }
    }

    /**
     * 支付商城订单参数验证
     * Author: Jason<dcq@kuryun.cn>
     */
    public static function payOrderGoodsPostValid() {
        if(! isset(self::$ajax['id'], self::$ajax['pay_way'])) {
            abort(ErrorCode::ErrorParam, '参数错误');
        }

        if($order = model('OrderGoods')->getOne(self::$ajax['id'])) {
            self::$param['order'] = $order;
        }else {
            abort(ErrorCode::InvalidParam, 'id非法');
        }
        if($order['paid'] != 0 || $order['status'] == 10) {
            self::error(ErrorCode::BadParam, '无效订单');
        }
        //0：微信支付；1：余额支付
        if(in_array(self::$ajax['pay_way'], [0, 1])) {
            if(self::$ajax['pay_way']) {
                //判断余额是否充足
                $user_money = model('UserMoney')->getOne($order['user_id']);
                if(($user_money['money'] * 100) < $order['amount']) {
                    self::error(ErrorCode::BadParam, '余额不足，请充值后支付或选择微信支付');
                }
            }
            self::$param['pay_way'] = (int)self::$ajax['pay_way'];
        }else {
            abort(ErrorCode::InvalidParam, 'pay_way非法');
        }

        unset($order);
        return true;
    }

    /**
     * 支付预约订单参数验证
     * Author: Jason<dcq@kuryun.cn>
     */
    public static function payOrderBookPostValid() {
        if(! isset(self::$ajax['parent_no'], self::$ajax['pay_way'])) {
            abort(ErrorCode::ErrorParam, '参数错误');
        }

        $order_list = model('OrderBook')->getAll([
            'where' => ['parent_no' => self::$ajax['parent_no']],
            'order' => ['id' => 'desc']
        ]);
        if($order_list) {
            self::$param['order_list'] = $order_list;
        }else {
            abort(ErrorCode::InvalidParam, 'parent_no非法');
        }
        foreach ($order_list as $item) {
            if($item['paid'] != 0 || $item['status'] == 10) {
                self::error(ErrorCode::ErrorParam, '无效订单');
            }
        }
        //0：微信支付；1：余额支付
        if(in_array(self::$ajax['pay_way'], [0, 1])) {
            if(self::$ajax['pay_way']) {
                $amount = 0;
                foreach ($order_list as $item) {
                    $amount += $item['amount'];
                }
                //判断余额是否充足
                $user_money = model('UserMoney')->getOne($order_list[0]['user_id']);
                if(($user_money['money'] * 100) < $amount) {
                    self::error(ErrorCode::BadParam, '余额不足，请充值后支付或选择微信支付');
                }
                unset($amount, $user_money);
            }
            self::$param['pay_way'] = (int)self::$ajax['pay_way'];
        }else {
            abort(ErrorCode::InvalidParam, 'pay_way非法');
        }

        unset($order_list);
        return true;
    }
}