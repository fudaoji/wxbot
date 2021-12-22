<?php


namespace app\api\helper;

use Exception;
use ky\ErrorCode;

class CartHelper extends BaseHelper
{
    /**
     * 校验数量
     * @throws Exception
     */
    protected static function checkQuantity()
    {
        if (!isset(self::$ajax['quantity'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        if (!is_numeric(self::$ajax['quantity']) || self::$ajax['quantity'] < 0) {
            self::error(ErrorCode::InvalidParam, 'quantity 参数非法' . self::$ajax['quantity']);
        }

        return true;
    }

    /**
     * @throws Exception
     */
    protected static function checkCartId()
    {
        if (!isset(self::$ajax['cart_id'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        if (!is_numeric(self::$ajax['cart_id']) || self::$ajax['cart_id'] < 0) {
            self::error(ErrorCode::InvalidParam, 'cart_id 参数非法' . self::$ajax['cart_id']);
        }

        return true;
    }

    /**
     * @throws Exception
     */
    protected static function checkSkuId()
    {
        if (!isset(self::$ajax['sku_id'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        if (!is_numeric(self::$ajax['sku_id']) || self::$ajax['sku_id'] < 0) {
            self::error(ErrorCode::InvalidParam, 'sku_id 参数非法' . self::$ajax['sku_id']);
        }

        return true;
    }

    /**
     * 校验用户购物车添加商品
     * @throws Exception
     */
    public static function addUserCartGoodsValid()
    {
        self::checkQuantity();
        self::checkSkuId();
    }

    /**
     * 校验用户购物车减少商品
     * @throws Exception
     */
    public static function decreaseUserCartGoodsValid()
    {
        self::checkQuantity();
        self::checkSkuId();
    }

    /**
     * 校验用户购物车删除商品
     * @throws Exception
     */
    public static function deleteUserCartGoodsValid()
    {
        if (!isset(self::$ajax['sku_ids'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        if (!is_array(self::$ajax['sku_ids']) || count(self::$ajax['sku_ids']) < 0) {
            self::error(ErrorCode::InvalidParam, 'sku_sku_idsid 参数非法');
        }

        return true;
    }
}