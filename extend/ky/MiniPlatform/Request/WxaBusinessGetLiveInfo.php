<?php
/**
 * Created by PhpStorm.
 * Script Name: WxaBusinessGetLiveInfo.php
 * Create: 2020/09/29 14:12
 * Description: 获取直播间列表
 * @link https://developers.weixin.qq.com/miniprogram/dev/framework/liveplayer/studio-api.html
 * Author: fudaoji<fdj@kuryun.cn>
 */
namespace ky\MiniPlatform\Request;

use ky\MiniPlatform\RequestCheckUtil;

class WxaBusinessGetLiveInfo
{
    private $url = "https://api.weixin.qq.com/wxa/business/getliveinfo";
    private $action;
    private $roomId;
    private $start;
    private $limit;
    private $getParams = array();
    private $postParams = array();

    /**
     * Author: fudaoji<fdj@kuryun.cn>
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function setAction($action)
    {
        $this->action = $action;
        $this->postParams['action'] = $action;
    }

    /**
     * Author: fudaoji<fdj@kuryun.cn>
     * @return mixed
     */
    public function getRoomId()
    {
        return $this->roomId;
    }

    /**
     * @param mixed $roomId
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function setRoomId($roomId)
    {
        $this->roomId = $roomId;
        $this->postParams['room_id'] = $roomId;
    }


    /**
     * 获取请求url
     * @author fudaoji<fdj@kuryun.cn>
     */
    public function getUrl(){
        return $this->url;
    }

    /**
     * 设置请求地址
     * @param string $url
     * @author fudaoji<fdj@kuryun.cn>
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * 设置start
     * @param string $start
     * @author fudaoji<fdj@kuryun.cn>
     */
    public function setStart($start) {
        $this->start = $start;
        $this->postParams['start'] = $start;
    }

    /**
     * 获取 start
     * @author fudaoji<fdj@kuryun.cn>
     */
    public function getStart() {
        return $this->start;
    }

    /**
     * 设置 limit
     * @param string $limit
     * @author fudaoji<fdj@kuryun.cn>
     */
    public function setLimit($limit) {
        $this->limit = $limit;
        $this->postParams['limit'] = $limit;
    }

    /**
     * 获取 limit
     * @author fudaoji<fdj@kuryun.cn>
     */
    public function getLimit() {
        return $this->limit;
    }

    /**
     * get请求参数
     * @author fudaoji<fdj@kuryun.cn>
     */
    public function getParams() {
        return $this->getParams;
    }

    /**
     * post请求参数
     * @author fudaoji<fdj@kuryun.cn>
     */
    public function postParams() {
        return $this->postParams;
    }

    /**
     * 参数验证
     * @author fudaoji<fdj@kuryun.cn>
     */
    public function check() {
        RequestCheckUtil::checkMinValue($this->start, 0, 'start');
        RequestCheckUtil::checkMinValue($this->limit, 1, 'limit');
        RequestCheckUtil::checkNumberic($this->limit, 'limit');
    }
}