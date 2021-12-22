<?php


namespace app\api\helper;


use Exception;
use ky\ErrorCode;
use ky\Logger;

class HomeHelper extends BaseHelper
{
    /**
     * 获取精品推荐列表的参数验证
     * @throws Exception
     */
    public static function getRecommendGoodsValid()
    {
        if (!isset(self::$ajax['current_page'], self::$ajax['page_size'])) {
            Logger::setMsgAndCode('参数错误', ErrorCode::ErrorParam);
        }

        self::checkPage();

        return true;
    }
}