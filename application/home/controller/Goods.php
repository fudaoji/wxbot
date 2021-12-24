<?php
namespace app\home\controller;
use think\Controller;
use app\admin\model\Goods as GoodM;

class Goods extends Base
{
    public function Index(){
        echo "Goods";
    }
    /**
     * 各大榜单
     * eliteId
     * API接口说明：返回京推推实时领券榜、30天销量榜、30天收益榜及总领券榜，可通过eliteId值去选择想要的榜单
     * API使用建议：默认实时领券榜，若不使用分类则为总类目领券榜，可使用一级分类构建分类榜单；默认返回榜单前100商品，可使用分页获取更多商品
     * eliteId：频道ID discountReal实时爆单榜；inOrderCount30Days 30天销量榜；inOrderComm30Days 30天收益榜；discountCount总领券
     * goods_type：京推推商品一级类目： 1居家日用；2食品；3生鲜；4图书；5美妆个护；6母婴；7数码家电；8内衣；9配饰；10女装；11男装；12鞋品；
     * 13家装家纺；14文娱车品；15箱包；16户外运动（支持多类目筛选，如1,2获取类目为居家日用、食品的所有商品，请用英文都好隔开，不传则为全部商品）
     */
    public function getTodayTop(){
        $input = input();
        $param = [
            'appid' => $this->appid,
            'appkey' => $this->appkey,
            'v' => $this->version,
            'pageIndex' => $input['pageIndex']??1,
            'pageSize' => $input['pageSize']??100,
            'eliteId' => $input['eliteId']??'',
            'goods_type' => $input['goods_type']??'',
            'goods_second_type' => $input['goods_second_type']??'',

        ];
        $url = 'http://japi.jingtuitui.com/api/today_top';
        $res = http_post($url, $param);
        echo $res;
    }

    /**
     * 精选好货
     * 
     */
    public function getGoodsList(){
        $input = input();
        $param = [
            'appid' => $this->appid,
            'appkey' => $this->appkey,
            'v' => $this->version,
            'pageIndex' => $input['pageIndex']??1,
            'pageSize' => $input['pageSize']??100,
            'keyword' => $input['keyword']??'', 
            'goods_type' => $input['goods_type']??'',
            'goods_second_type' => $input['goods_second_type']??'',
            'sortName' => $input['sortName']??'',
            'sort' => $input['sort']??'',
            

        ];
        $url = 'http://japi.jingtuitui.com/api/today_top';
        $res = http_post($url, $param);
        echo $res;
    }

}