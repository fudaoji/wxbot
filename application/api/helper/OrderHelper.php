<?php


namespace app\api\helper;


use app\api\controller\Order;
use Exception;
use ky\ErrorCode;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class OrderHelper extends BaseHelper
{
    /**
     * 创建订单参数校验
     * @throws Exception
     */
    public static function createOrderValid($userId)
    {
        if (!isset(self::$ajax['goods_list'], self::$ajax['user_note'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        if (empty(self::$ajax['goods_list']) || !is_array(self::$ajax['goods_list'])) {
            self::error(ErrorCode::InvalidParam, 'goods_list 参数非法' . self::$ajax['goods_list']);
        }

        foreach (self::$ajax['goods_list'] as $item) {
            if (!is_array($item) || !array_key_exists('sku_id', $item) ||
                !array_key_exists('quantity', $item) || !array_key_exists('cart_id', $item)
            ) {
                self::error(ErrorCode::InvalidParam, 'goods_list 参数非法' . json_encode(self::$ajax['goods_list']));
            }
        }

        self::checkAddressId($userId);

        // 获取商品信息
        $quantityKeyBySkuId = array_column(self::$ajax['goods_list'], 'quantity', 'sku_id');
        $skuIds = array_keys($quantityKeyBySkuId);
        $goodsUnitList = model('common/GoodsSku')->getAll([
            'where' => ['id' => ['in', $skuIds]]
        ]);
        if (empty($goodsUnitList) || count($quantityKeyBySkuId) !== count($goodsUnitList)) {
            self::error(ErrorCode::InvalidParam, 'goods_list 商品不存在' . json_encode($quantityKeyBySkuId));
        }

        // 计算订单金额（分）
        $orderPrice = 0;
        foreach ($goodsUnitList as $goodsUnit) {
            $quantity = $quantityKeyBySkuId[$goodsUnit['id']];
            $orderPrice += $quantity * $goodsUnit['price'];
        }

        // 校验优惠券是否可用
        self::checkCouponId($userId, $orderPrice);

        return true;
    }

    /**
     * 校验优惠券是否可用
     * @param int $userId
     * @param int $orderPrice
     * @return bool
     * @throws DbException|Exception
     */
    public static function checkCouponId($userId, $orderPrice)
    {
        if (!array_key_exists('coupon_id', self::$ajax) || empty(self::$ajax['coupon_id'])) {
            self::$ajax['coupon_id'] = null;
            return true;
        }

        // 检测优惠券是否可用
        $couponInfo = model('common/UserCoupon')->getOneByMap([
            'id'      => self::$ajax['coupon_id'],
            'status'  => 1,
            'user_id' => $userId,
        ], 'id, user_id, condition');

        if (empty($couponInfo)) {
            self::error(ErrorCode::InvalidParam, 'coupon_id 参数非法' . self::$ajax['coupon_id']);
        }

        // 检测优惠券是否满足使用条件（分）
        if ((int)$couponInfo['condition'] > (int)$orderPrice) {
            self::error(ErrorCode::InvalidParam, '优惠券不可用' . self::$ajax['coupon_id']);
        }

        return true;
    }

    /**
     * 校验收货地址是否可用
     * @param $userId
     * @return bool
     * @throws DataNotFoundException|DbException|ModelNotFoundException
     */
    public static function checkAddressId($userId)
    {
        if (!array_key_exists('address_id', self::$ajax) || empty(self::$ajax['address_id'])) {
            self::$ajax['address_id'] = null;
            return true;
        }

        $address = model('common/Address')->getOne(self::$ajax['address_id']);

        if (empty($address)) {
            self::error(ErrorCode::InvalidParam, '地址不存在');
        }

        if ($address['user_id'] !== $userId) {
            self::error(ErrorCode::IlleglOperation, '非法操作');
        }

        return true;
    }

    /**
     * 权限校验
     * @param $userId
     * @throws DataNotFoundException|DbException|ModelNotFoundException|Exception
     */
    public static function checkOrderId($userId)
    {
        if (!isset(self::$ajax['order_id'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        $orderInfo = model('common/Order')->getOne(self::$ajax['order_id']);
        if (empty($orderInfo)) {
            self::error(ErrorCode::InvalidParam, 'order_id 参数非法' . self::$ajax['order_id']);
        }

        if ($orderInfo['user_id'] !== $userId) {
            self::error(ErrorCode::IlleglOperation, '非法操作' . self::$ajax['order_id']);
        }
    }

    /**
     * @throws DataNotFoundException|DbException|ModelNotFoundException|Exception
     */
    public static function getOrderInfoListValid()
    {
        if (!isset(self::$ajax['type'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        self::pageValid();

        if (!in_array(self::$ajax['type'], Order::QUERY_SCENE, true)) {
            self::error(ErrorCode::InvalidParam, 'type 参数非法' . self::$ajax['type']);
        }
    }

}