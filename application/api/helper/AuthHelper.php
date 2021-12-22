<?php
/**
 * Created by PhpStorm.
 * Script Name: AuthorHelper.php
 * Create: 2020/10/13 16:52
 * Description: 用户认证帮助类
 * Author: Jason<dcq@kuryun.cn>
 */

namespace app\api\helper;


use ky\ErrorCode;

class AuthHelper extends BaseHelper
{
    /**
     * 获取token参数验证
     * Author: Jason<dcq@kuryun.cn>
     */
    public static function tokenPostValid() {
        if(! isset(self::$ajax['openid'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        self::checkOpenid();
        return true;
    }

    /**
     * 获取微信授权信息
     * Author: Jason<dcq@kuryun.cn>
     */
    public static function wxAuthPostValid() {
        if(! isset(self::$ajax['nickname'], self::$ajax['sex'], self::$ajax['country'],
            self::$ajax['province'], self::$ajax['city'], self::$ajax['headimgurl'],
            self::$ajax['mobile'], self::$ajax['username']
        )){
            logger('参数错误', ErrorCode::ErrorParam);
        }

        if(self::$ajax['headimgurl'] && ! self::$param['headimgurl'] = self::checkUrl(self::$ajax['headimgurl'])){
            self::error('headimgurl参数非法' . self::$ajax['headimgurl'], ErrorCode::InvalidParam);
        }
        self::$param['sex'] = intval(self::$ajax['sex']);
        self::$param['country'] = self::doString(self::$ajax['country']);
        self::$param['province'] = self::doString(self::$ajax['province']);
        self::$param['city'] = self::doString(self::$ajax['city']);
        self::$param['nickname'] = filter_emoji(self::$ajax['nickname']);
        self::$param['mobile'] = self::doString(self::$ajax['mobile']);
        self::$param['username'] = self::doString(self::$ajax['username']);

        return true;
    }

    /**
     * openid验证
     * Author: Jason<dcq@kuryun.cn>
     */
    private static function checkOpenid() {
        if($openid = self::stringValid(self::$ajax['openid'], 16, 64)) {
            self::$param['openid'] = $openid;
        }else {
            logger('openid非法', ErrorCode::InvalidParam);
        }
        unset($openid);
    }
}