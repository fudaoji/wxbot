<?php
/**
 * Created by PhpStorm.
 * Script Name: XmlMini.php
 * Create: 2023/4/19 7:45
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;

use ky\Xml;

class XmlMini extends Xml
{
    static $instance = null;

    public function __construct($xml = '')
    {
        parent::__construct($xml);
    }

    static function getInstance($xml = ''){
        if(is_null(self::$instance)){
            self::$instance = new self($xml);
        }
        return self::$instance;
    }

    function decodeObject()
    {
        return ($this->object->appmsg);
    }

    /**
     * 获取小程序名称
     * @return string
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function getSourceDisplayName(){
        return (string)$this->object->appmsg->sourcedisplayname;
    }

    /**
     * 获取标题
     * @return string
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function getTitle(){
        return (string)$this->object->appmsg->title;
    }

    /**
     * 获取封面
     * @return string
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function getThumbRawUrl(){
        return (string)$this->object->appmsg->weappinfo->weapppagethumbrawurl;
    }

    /**
     * 获取小程序信息
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function getWeAppInfo(){
        /**
         * <pagepath>packagesD/pages/goods/sharePages.html?goodsId=804922752908791808&amp;superId=807035211991879680</pagepath>
        <username>gh_a306e7f4ff8e@app</username>
        <appid>wxf856a911604ec0ad</appid>
        <version>9</version>
        <type>2</type>
        <weappiconurl>http://mmbiz.qpic.cn/mmbiz_png/YIQQgLwmsmicCWTXnCuQVB24tvZ7IYmTiclXLjDDJYAGLibJOMqjD7qicOxAJiboRXEPReys9VDcLjBUicPe12mImn2Q/640?wx_fmt=png&amp;wxfrom=200</weappiconurl>
         */
        $weappinfo = $this->object->appmsg->weappinfo;
        $data = [
            'pagepath' => (string)$weappinfo->pagepath,
            'username' => (string)$weappinfo->username,
            'appid' => (string)$weappinfo->appid,
            'weappiconurl' => (string)$weappinfo->weappiconurl,
        ];
        return $data;
    }

    /**
     * 获取pagepath中的参数
     * @param string $key
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function getPathParams($key = ''){
        $path = $this->getWeAppInfo()['pagepath'];
        $params_str = substr($path, strpos($path, '?') + 1);
        $params = [];
        if($params_str){
            $query = explode('&', $params_str);
            foreach ($query as $item){
                list($k1, $v1) = explode('=', $item);
                if($k1 == $key){
                    return $v1;
                }
                $params[$k1] = $v1;
            }
        }
        return $params;
    }

    function getObject(){
        return $this->object;
    }
}