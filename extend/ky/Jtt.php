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
        $url = "http://japi.jingtuitui.com/api/universal?appid={$this->appId}&appkey={$this->appKey}&v={$this->version}";
        $url .= '&'.http_build_query($params);
        $body = file_get_contents($url);
        //Logger::error($body);
        $res = json_decode($body, true);
        if(!$res){
            $res = json_decode(substr($body, 5), true);
        }
        if(isset($res['return']) && $res['return'] == 0){
            return $res['result'];
        }
        return false;
    }
}