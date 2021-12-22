<?php
/**
 * Created by PhpStorm.
 * Script Name: Onmessage.php
 * Create: 2020/8/4 16:37
 * Description: 回调
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\api\controller;

use Exception;
use ky\Payment;
use think\Controller;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\facade\Log;
use think\Queue;

class Onmessage extends Controller
{
    /**
     * @throws DataNotFoundException|ModelNotFoundException|DbException
     */
    public function initialize()
    {
        parent::initialize();
        model('common/setting')->settings();
    }

    /**
     * 支付商品订单回调
     * @throws Exception
     */
    public function orderGoodsCallback()
    {
        // 支付结果回调接收器
        /** @var Payment\Wx\Api $payment */
        $payment = new Payment(get_pay_config());

//         $dataJson = <<<JSON
//             {
//                 "appid": "wxba2edfe8bee80a92",
//                 "bank_type": "OTHERS",
//                 "cash_fee": "1",
//                 "fee_type": "CNY",
//                 "is_subscribe": "N",
//                 "mch_id": "1611225168",
//                 "nonce_str": "3td51mozju10n80ikj5v0oga2jzudfya",
//                 "openid": "oNG4m5TP0dIvgMuuC7bkhnpX6p6c",
//                 "out_trade_no": "202107211622523780367825",
//                 "result_code": "SUCCESS",
//                 "return_code": "SUCCESS",
//                 "sign": "194E007646CDF26593D667E61FC4F906",
//                 "time_end": "20210721162306",
//                 "total_fee": "1",
//                 "trade_type": "JSAPI",
//                 "transaction_id": "4200001152202107216987803267",
//                 "ky_pay_result": true,
//                 "channel": "WX_JSAPI"
//             }
// JSON;
        $data = $payment->notify();

        // 回复通知内容
        if ($data['ky_pay_result'] === false) {
            Log::error('支付失败，失败原因：' . $data['return_msg']);
            $payment->replyNotify($data);
            return;
        }
        Log::write('支付成功::' . json_encode($data));

        Db::startTrans();
        try {

            // 获取订单最新状态
            $order = model('common/Order')->getOneByMap([
                'order_no' => $data['out_trade_no']
            ]);

            // 更新订单状态
            if ($order['pay_status'] === 0) {

                $mainOrderUpdateInfo = [
                    'id'         => $order['id'],
                    'pay_time'   => time(),
                    'status'     => 2,
                    'pay_status' => 1,
                ];

                // 判断是否有拆分子订单
                $subOrderIds = model('common/Order')->getAll([
                    'main_order_id' => $order['id'],
                ]);
                if (!empty($subOrderIds)) {
                    // 隐藏主订单
                    $mainOrderUpdateInfo['displace'] = 0;

                    // 显示门店子订单
                    model('common/Order')->updateByMap(
                        ['main_order_id' => $order['id']],
                        [
                            'displace'   => 1,
                            'pay_time'   => time(),
                            'status'     => 2,
                            'pay_status' => 1,
                        ]
                    );
                }

                // 更新主订单
                model('common/Order')->updateOne($mainOrderUpdateInfo);

                // 记录支付日志
                model('common/PayLog')->insert([
                    'order_no'  => $order['order_no'],
                    'user_id'   => $order['user_id'],
                    'status'    => 1,
                    'subject'   => $data['openid'],
                    'trade_no'  => $data['transaction_id'],
                    'pay_price' => $data['total_fee'],
                    'pay_time'  => time(),
                ]);
            }

            Db::commit();
        } catch (Exception $e) {
            Log::error('修改订单状态出错，错误信息：' . json_encode($e->getMessage(), JSON_UNESCAPED_UNICODE));
            Db::rollback();
        }

        // 回复通知内容
        $payment->replyNotify($data);
    }

    /**
     * 支付商城订单回调
     * Author: Jason<dcq@kuryun.cn>
     * @throws \Exception
     */
    public function orderGoodsCallbackOld()
    {
        $pay_api = new Payment(get_pay_config());
        $data = $pay_api->notify();
        if ($data['ky_pay_result'] === true) {
            Log::write('支付成功::' . json_encode($data));
            Db::startTrans();
            try {
                //更新订单状态
                $order = model('OrderGoods')->getOneByMap(['order_no' => $data['out_trade_no']]);
                if ($order['paid'] >= 1) {
                    $pay_api->replyNotify($data); //防止重复核销
                }
                model('OrderGoods')->updateOne([
                    'id'             => $order['id'],
                    'paid'           => 1,
                    'pay_time'       => time(),
                    'status'         => 2,  //已支付待核销
                    'transaction_id' => $data['transaction_id']
                ]);
                //来源于购物车商品，则清除购物车
                $extend_info = json_decode($order['extend_info'], true);
                if (isset($extend_info['cart_ids'])) {
                    model('ShoppingCart')->delBatch($extend_info['cart_ids']);
                }
                //异步核算会员等级
                Queue::push('app\\api\\job\\UserUpJob', ['user_id' => $order['user_id']], 'book');
                unset($order);
                Db::commit();
            } catch (\Exception $e) {
                Log::error('修改订单状态出错，错误信息：' . json_encode($e->getMessage(), JSON_UNESCAPED_UNICODE));
                Db::rollback();
            }

            $pay_api->replyNotify($data);
        } else {
            Log::error('支付失败，失败原因：' . $data['return_msg']);
            $pay_api->replyNotify($data);
        }
    }
}