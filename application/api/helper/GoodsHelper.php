<?php


namespace app\api\helper;

use app\api\controller\Goods;
use Exception;
use ky\ErrorCode;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class GoodsHelper extends StoreHelper
{
    /**
     * 校验商品 ID
     * @throws Exception
     */
    public static function checkGoodsId()
    {
        if (!isset(self::$ajax['goods_id'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        if (!is_numeric(self::$ajax['goods_id']) || self::$ajax['goods_id'] < 0) {
            self::error(ErrorCode::InvalidParam, 'goods_id 参数非法' . self::$ajax['goods_id']);
        }

        return true;
    }

    /**
     * 精品推荐校验
     * @throws DataNotFoundException|ModelNotFoundException|DbException|Exception
     */
    public static function getRecommendGoodsValid()
    {
        self::pageValid();
    }

    /**
     * 商品列表校验
     * @throws DataNotFoundException|ModelNotFoundException|DbException|Exception
     */
    public static function getGoodsListValid()
    {
        // Optional: 商品标题模糊搜索
        if (!array_key_exists('title', self::$ajax)) {
            self::$ajax['title'] = null;
        } else if (empty(self::$ajax['title'])) {
            self::error(ErrorCode::InvalidParam, 'title 参数非法' . self::$ajax['title']);
        }

        // Optional: 精品商品
        if (!array_key_exists('recommend', self::$ajax)) {
            self::$ajax['recommend'] = null;
        } else if (empty(self::$ajax['recommend'])) {
            self::error(ErrorCode::InvalidParam, 'recommend 参数非法' . self::$ajax['recommend']);
        }

        // Optional: 根据商品类别搜索
        if (!array_key_exists('category_id', self::$ajax)) {
            self::$ajax['category_id'] = null;
        } else if (empty(self::$ajax['category_id'])) {
            self::error(ErrorCode::InvalidParam, 'category_id 参数非法' . self::$ajax['category_id']);
        }

        // Optional: 排序
        if (array_key_exists('order_by', self::$ajax)) {
            if (!in_array(self::$ajax['order_by'], Goods::GOODS_ORDER_BY, true)) {
                self::error(ErrorCode::InvalidParam, 'order_by 参数非法' . self::$ajax['order_by']);
            }
        } else {
            self::$ajax['order_by'] = null;
        }

        if (!array_key_exists('ascend', self::$ajax)) {
            self::$ajax['ascend'] = null;
        }

        self::pageValid();
    }

    /**
     * 模糊搜索商品校验
     * @throws DataNotFoundException|ModelNotFoundException|DbException|Exception
     */
    public static function getGoodsByNameValid()
    {
        if (!array_key_exists('title', self::$ajax)) {
            self::$ajax['title'] = null;
            return;
        }

        if (!isset(self::$ajax['title'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        self::pageValid();
    }

    /**
     * 根据商品类别分页获取商品校验
     * @throws DataNotFoundException|DbException|ModelNotFoundException|Exception
     */
    public static function getGoodsByCategoryValid()
    {
        if (!isset(self::$ajax['category_id'])) {
            logger('参数错误', ErrorCode::ErrorParam);
        }

        if (!is_numeric(self::$ajax['category_id']) || self::$ajax['category_id'] < 0) {
            self::error(ErrorCode::InvalidParam, 'category_id 参数非法' . self::$ajax['category_id']);
        }

        $result = model('common/GoodsCate')->getOne(self::$ajax['category_id']);
        if (empty($result)) {
            self::error(ErrorCode::InvalidParam, '不存在该商品分类' . self::$ajax['category_id']);
        }

        self::pageValid();
    }
}