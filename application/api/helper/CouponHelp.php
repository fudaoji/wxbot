<?php


namespace app\api\helper;


use app\api\controller\Coupon;
use Exception;
use ky\ErrorCode;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class CouponHelp extends BaseHelper
{
    /**
     * @throws DataNotFoundException|ModelNotFoundException|DbException|Exception
     */
    public static function getUserCouponsValid()
    {
        if (!isset(self::$ajax['type'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        self::pageValid();

        if (!in_array(self::$ajax['type'], Coupon::QUERY_SCENE, true)) {
            self::error(ErrorCode::InvalidParam, 'type 参数非法' . self::$ajax['type']);
        }
    }
}