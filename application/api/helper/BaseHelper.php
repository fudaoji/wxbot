<?php
/**
 * Created by PhpStorm.
 * Script Name: BaseHelper.php
 * Create: 2020/7/30 12:00
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\api\helper;

use Exception;
use ky\ErrorCode;
use ky\Helper;
use ky\Logger;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class BaseHelper extends Helper
{
    /**
     * 列表参数校验
     * @return bool
     * @throws DataNotFoundException|DbException|ModelNotFoundException|Exception
     */
    public static function pageValid()
    {
        if (!isset(Helper::$ajax['current_page'], Helper::$ajax['page_size'], Helper::$ajax['refresh'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        self::checkPage();
        self::checkRefresh();

        return true;
    }

    /**
     * 缓存参数验证
     * @return mixed
     * @author: Jason<1589856452@qq.com>
     */
    protected static function checkRefresh(){
        if(in_array(self::$ajax['refresh'], [0, 1])){
            self::$param['refresh'] = self::$ajax['refresh'];
        }else{
            abort(ErrorCode::InvalidParam, 'refresh参数非法');
        }
    }

    /**
     * 分页参数验证
     * @return mixed
     * @author: Jason<1589856452@qq.com>
     */
    protected static function checkPage(){
        $current_page = (int)self::$ajax['current_page'];
        $page_size = (int)self::$ajax['page_size'];

        if($current_page > 0) {
            self::$param['current_page'] = $current_page;
        }else {
            abort(ErrorCode::InvalidParam, 'current_page参数非法');
        }

        if($page_size > 0) {
            self::$param['page_size'] = $page_size;
        }else {
            abort(ErrorCode::InvalidParam, 'page_size参数非法');
        }
        unset($current_page,$page_size);
    }

    /**
     * 通用时间范围验证
     * @return mixed
     * @author Jason<dcq@kuryun.cn>
     */
    protected static function checkTimeRange() {
        if(self::checkTimestamp((int)self::$ajax['begin_time']) && self::checkTimestamp((int)self::$ajax['end_time'])) {
            if(self::$ajax['begin_time'] < self::$ajax['end_time']) {
                self::$param['begin_time'] = self::$ajax['begin_time'];
                self::$param['end_time'] = self::$ajax['end_time'];
            }else {
                self::error(ErrorCode::BadParam, '开始时间应小于结束时间');
            }
        }else {
            abort(ErrorCode::InvalidParam, 'begin_time或end_time非法');
        }
    }

    /**
     * 搜索关键词验证
     * @throws \Exception
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected static function checkSearchKey(){
        if(isset(self::$ajax['search_key'])){
            if(! self::$param['search_key'] = self::stringValid(self::$ajax['search_key'], 1, 20)){
                Logger::setMsgAndCode('search_key参数非法' . self::$ajax['search_key'], ErrorCode::InvalidParam);
            }
        }
    }
}