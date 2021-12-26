<?php
/**
 * Created by PhpStorm.
 * Script Name: Wx.php
 * Create: 12/20/21 11:42 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\Bot;


class Wx extends Base
{
    protected $baseUri = 'http://116.62.202.87:8889/';

    public function __construct($options = [])
    {
        parent::__construct($options);
    }

    public function setAppKey($app_key = ''){
        $this->appKey = $app_key;
        return $this;
    }

    /**
     * 发送文本消息给好友
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendTextToFriend($params = []){
        $url = '/message/user';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'post',
            'headers' => ['AppKey' => $this->appKey],
            'data' => $params['data'] //{to: "", content:"", type:"text"}
        ]);
    }

    /**
     * 设置好友备注名
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function setFriendRemarkName($params = []){
        $url = '/user/setfriendremarkname';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'post',
            'headers' => ['AppKey' => $this->appKey],
            'data' => $params['data']
        ]);
    }

    /**
     * 获取当前用户的群组列表
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getGroups($params = []){
        $url = '/user/groups';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'get',
            'headers' => ['AppKey' => $this->appKey]
        ]);
    }

    /**
     * 获取当前用户的好友列表
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getFriends($params = []){
        $url = '/user/friends';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'get',
            'headers' => ['AppKey' => $this->appKey]
        ]);
    }

    /**
     * 获取当前用户信息
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getCurrentUser($params = []){
        $url = '/user/info';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'get',
            'headers' => ['AppKey' => $this->appKey]
        ]);
    }

    /**
     * 验证登录情况
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function checkLogin($params = []){
        $url = '/checklogin';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'post',
            'headers' => ['AppKey' => $this->appKey],
            'data' => $params['data']
        ]);
    }

    /**
     * 获取登录二维码
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getLoginCode(){
        $url = '/getlogincode';
        return $this->request([
            'url' => $url,
            'method' => 'get',
            'headers' => ['AppKey' => $this->appKey]
        ]);
    }
}