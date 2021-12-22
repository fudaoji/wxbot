<?php


namespace app\api\helper;


use app\common\enum\AddressEnum;
use Exception;
use ky\ErrorCode;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class AddressHelp extends BaseHelper
{
    /**
     * 校验新增用户收货地址
     * @throws Exception
     */
    public static function addUserAddressValid()
    {
        if (!isset(
            self::$ajax['mobile'], self::$ajax['name'], self::$ajax['province'], self::$ajax['default'],
            self::$ajax['province_name'], self::$ajax['city'], self::$ajax['city_name'], self::$ajax['area'],
            self::$ajax['area_name'], self::$ajax['address']
        )) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        if (self::checkTel(self::$ajax['mobile']) === false) {
            self::error(ErrorCode::InvalidParam, 'mobile参数非法' . self::$ajax['mobile']);
        }

        self::checkDefault();
    }

    /**
     * 校验更新用户收货地址
     * @throws Exception
     */
    public static function updateUserAddressValid($userId)
    {
        if (!isset(
            self::$ajax['mobile'], self::$ajax['name'], self::$ajax['province'], self::$ajax['address'],
            self::$ajax['province_name'], self::$ajax['city'], self::$ajax['city_name'], self::$ajax['area'],
            self::$ajax['area_name'], self::$ajax['address_id'], self::$ajax['default']
        )) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        if (self::checkTel(self::$ajax['mobile']) === false) {
            self::error(ErrorCode::InvalidParam, 'mobile参数非法' . self::$ajax['mobile']);
        }

        self::checkAddressId(self::$ajax['address_id'], $userId);
    }

    /**
     * 权限校验
     * @param $addressId
     * @param $userId
     * @throws DataNotFoundException|ModelNotFoundException|DbException
     */
    protected static function checkAddressId($addressId, $userId)
    {
        $address = model('common/Address')->getOne($addressId);

        if (empty($address)) {
            self::error(ErrorCode::InvalidParam, '地址不存在');
        }

        if ($address['user_id'] !== $userId) {
            self::error(ErrorCode::IlleglOperation, '非法操作');
        }
    }

    protected static function checkDefault()
    {
        if (!in_array(self::$ajax['default'], [AddressEnum::DEFAULT_ADDRESS, AddressEnum::NOT_DEFAULT_ADDRESS], true)) {
            self::error(ErrorCode::InvalidParam, 'default参数非法' . self::$ajax['default']);
        }
    }

    /**
     * 校验设置用户默认收货地址
     * @throws Exception
     */
    public static function setUserDefaultAddressValid($userId)
    {
        if (!isset(self::$ajax['address_id'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        self::checkAddressId(self::$ajax['address_id'], $userId);
    }

    /**
     * @param $userId
     * @throws DataNotFoundException|DbException|ModelNotFoundException|Exception
     */
    public static function deleteUserAddressValid($userId)
    {
        if (!isset(self::$ajax['address_id'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        $address = model('common/Address')->getOne(self::$ajax['address_id']);
        if (empty($address)) {
            return;
        }

        if ($address['user_id'] !== $userId) {
            self::error(ErrorCode::IlleglOperation, '非法操作');
        }
    }
}