<?php


namespace app\api\helper;

use Exception;
use ky\ErrorCode;

class StoreHelper extends BaseHelper
{
    /**
     * 校验门店 ID
     * @throws Exception
     */
    public static function checkStoreId()
    {
        if (!isset(self::$ajax['store_id'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        if (!is_numeric(self::$ajax['store_id']) || self::$ajax['store_id'] < 0) {
            self::error(ErrorCode::InvalidParam, 'store_id 参数非法' . self::$ajax['store_id']);
        }

        return true;
    }

    /**
     * 切换门店校验
     * @throws Exception
     */
    public static function setDefaultStoreValid()
    {
        self::checkStoreId();
    }

    /**
     * @throws Exception
     */
    public static function getStoreWithDistance()
    {
        if (!isset(self::$ajax['latitude'], self::$ajax['longitude'], self::$ajax['title'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        if (!is_numeric(self::$ajax['latitude'])) {
            self::error(ErrorCode::InvalidParam, 'latitude 参数非法' . self::$ajax['latitude']);
        }

        if (!is_numeric(self::$ajax['longitude'])) {
            self::error(ErrorCode::InvalidParam, 'longitude 参数非法' . self::$ajax['longitude']);
        }

        if (array_key_exists('city', self::$ajax) && !empty(self::$ajax['city'])) {
            if (!is_numeric(self::$ajax['city']) || self::$ajax['city'] < 0) {
                self::error(ErrorCode::InvalidParam, 'city 参数非法' . self::$ajax['city']);
            }
        }else{
            self::$ajax['city'] = null;
        }
    }
}