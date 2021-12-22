<?php
/**
 * Created by PhpStorm.
 * Script Name: Demo.php
 * Create: 2020/10/16 18:08
 * Description:
 * Author: Jason<dcq@kuryun.cn>
 */

namespace app\api\helper;


use ky\ErrorCode;

class UserHelper extends BaseHelper
{
    /**
     * 获取我的优惠券列表参数验证
     * Author: Jason<dcq@kuryun.cn>
     */
    public static function listMyCouponPostValid() {
        if(! isset(self::$ajax['current_page'], self::$ajax['page_size'], self::$ajax['refresh'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        self::checkPage();
        self::checkRefresh();

        return true;
    }

    /**
     * 获取用户信息参数验证
     * Author: Jason<dcq@kuryun.cn>
     */
    public static function getUserPostValid() {
        if(! isset(self::$ajax['refresh'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        self::checkRefresh();
        return true;
    }

    /**
     * 会员充值参数验证
     * Author: Jason<dcq@kuryun.cn>
     */
    public static function userRechargePostValid() {
        if(! isset(self::$ajax['recharge_id'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        self::$param['recharge'] = self::checkRechargeId();
        return true;
    }

    /**
     * 领取好友赠送的优惠券
     * Author: Jason<dcq@kuryun.cn>
     */
    public static function getShareCouponPostValid() {
        if(!isset(self::$ajax['user_coupon_id'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        if($user_coupon = model('UserCoupon')->getOne(self::$ajax['user_coupon_id'])) {
            if($user_coupon['used']) {
                self::error(ErrorCode::BadParam, '优惠券已被使用');
            }
            self::$param['user_coupon'] = $user_coupon;
        }else {
            self::success('优惠券已被领取');
        }

        unset($user_coupon);
        return true;
    }

    /**
     * 充值类目id验证
     * Author: Jason<dcq@kuryun.cn>
     */
    private static function checkRechargeId() {
        if($recharge = model('Recharge')->getOne(self::$ajax['recharge_id'])) {
            return $recharge;
        }
        logger('recharge_id非法', ErrorCode::InvalidParam);
    }
}