<?php
/**
 * Created by PhpStorm.
 * Script Name: Jtt.php
 * Create: 1/24/22 11:47 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky;


class Jtt
{
    private $appId;
    private $appKey;
    private $version = "v2";

    public function __construct($options = [])
    {
        $this->appId = $options['appid'];
        $this->appKey = $options['appkey'];
    }

    /**
     * 查询联盟的商品信息
     * @param array $params
     * @return bool
     * @link http://www.jingtuitui.com/api_item?id=3
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function jdBugGoodsQuery($params = [])
    {
        !empty($params['v']) && $this->version = $params['v'];
        $url = "http://japi.jingtuitui.com/api/get_goods_list?eliteId=bugGoods&appid={$this->appId}&appkey={$this->appKey}&v={$this->version}";

        $res = json_decode(http_post($url, $params), true);
        if (isset($res['return']) && $res['return'] == 0) {
            return $res['result'];
        }
        return false;
    }

    /**
     * 查询联盟的商品信息
     * @param array $params
     * @return bool
     * @link http://www.jingtuitui.com/api_item?id=3
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function jdGoodsQuery($params = [])
    {
        !empty($params['v']) && $this->version = $params['v'];
        $url = "http://japi.jingtuitui.com/api/jd_goods_query?appid={$this->appId}&appkey={$this->appKey}&v={$this->version}";

        $res = json_decode(http_post($url, $params), true);
        if (isset($res['return']) && $res['return'] == 0) {
            return $res['result'];
        }
        return false;
    }

    /**
     * 创建推广位
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function newPosition($params = []){
        $this->version = "v1";
        $url = "http://japi.jingtuitui.com/api/new_positionid?appid={$this->appId}&appkey={$this->appKey}&v={$this->version}";
        $url .= '&'.http_build_query($params);
        $body = file_get_contents($url);
        $res = json_decode($body, true);
        if(!$res){
            $res = json_decode(substr($body, 5), true);
        }
        Logger::error(json_encode($res, JSON_UNESCAPED_UNICODE));
        if(isset($res['return']) && $res['return'] == 0){
            return $res['result'];
        }
        return false;
    }

    /**
     * 智能转链
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function universal($params = []){
        !empty($params['v']) && $this->version = $params['v'];
        $url = "http://japi.jingtuitui.com/api/universal?appid={$this->appId}&appkey={$this->appKey}&v={$this->version}";
        $url .= '&'.http_build_query($params);
        $body = file_get_contents($url);

        $res = json_decode($body, true);
        if(!$res){
            $res = json_decode(substr($body, 5), true);
        }
        if(isset($res['return']) && $res['return'] == 0){
            return $res['result'];
        }
        Logger::error($body);
        return false;
    }
}