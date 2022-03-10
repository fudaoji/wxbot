<?php
/**
 * Created by PhpStorm.
 * Script Name: Wx.php
 * Create: 12/20/21 11:42 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\Bot;


class Xp extends Base
{
    const MSG_TEXT = 1;
    const MSG_IMG= 3;
    const MSG_APP = 4;

    const EVENT_USER_LIST = 5000;
    const EVENT_PERSONAL_INFO = 6500;

    public function __construct($options = [])
    {
        parent::__construct($options);
    }

    public function getAppKey(){
        return $this->appKey;
    }

    /**
     * 拉取群成员
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function pullGroupMembers($params = []){
        $url = '/user/group/members';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'post',
            'headers' => ['AppKey' => $this->appKey],
            'data' => $params['data']
        ]);
    }

    /**
     * 群发视频
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendVideoBatch($params = []){
        $url = '/message/batch/video';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'post',
            'headers' => ['AppKey' => $this->appKey],
            'data' => $params['data']
        ]);
    }

    /**
     * 群发文件
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendFileBatch($params = []){
        $url = '/message/batch/file';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'post',
            'headers' => ['AppKey' => $this->appKey],
            'data' => $params['data']
        ]);
    }

    /**
     * 群发图片
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendImgBatch($params = []){
        $url = '/message/batch/img';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'post',
            'headers' => ['AppKey' => $this->appKey],
            'data' => $params['data']
        ]);
    }

    /**
     * 群发文本
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendTextBatch($params = []){
        $url = '/message/batch';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'post',
            'headers' => ['AppKey' => $this->appKey],
            'data' => $params['data']
        ]);
    }

    /**
     * 移除群成员
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function removeMembersFromGroup($params = []){
        $url = '/user/group/removemembers';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'post',
            'headers' => ['AppKey' => $this->appKey],
            'data' => $params['data']
        ]);
    }

    /**
     * 邀请好友入群
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function addFriendsIntoGroup($params = []){
        $url = '/user/addfriendsintogroup';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'post',
            'headers' => ['AppKey' => $this->appKey],
            'data' => $params['data'] //{to: "", content:"", type:"image"}
        ]);
    }

    /**
     * 发送文件给群聊
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendFileToGroup($params = []){
        $url = '/message/group/file';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'post',
            'headers' => ['AppKey' => $this->appKey],
            'data' => $params['data'] //{to: "", content:"", type:"image"}
        ]);
    }

    /**
     * 发送文件给好友
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendFileToFriend($params = []){
        $url = '/message/user/file';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'post',
            'headers' => ['AppKey' => $this->appKey],
            'data' => $params['data'] //{to: "", content:"", type:"image"}
        ]);
    }

    /**
     * 发送图片给群聊
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendImgToGroup($params = []){
        $url = '/message/group/img';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'post',
            'headers' => ['AppKey' => $this->appKey],
            'data' => $params['data'] //{to: "", content:"", type:"image"}
        ]);
    }

    /**
     * 发送图片给好友
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendImgToFriend($params = []){
        $url = '/message/user/img';
        return $this->request([
            'url' => $url,
            'method' => 'post',
            'headers' => ['AppKey' => $this->appKey],
            'data' => $params['data'] //{to: "", content:"", type:"image"}
        ]);
    }

    /**
     * 发送文本消息给群聊
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendTextToGroup($params = []){
        $url = '/message/group';
        return $this->request([
            'url' => $url . '?uuid=' . $params['uuid'],
            'method' => 'post',
            'headers' => ['AppKey' => $this->appKey],
            'data' => $params['data'] //{to: "", content:"", type:"text"}
        ]);
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
            'url' => $url,
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
     * 获取当前用户的好友列表
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getFriends($params = []){
        $url = '/user/friends';
        return $this->request([
            'url' => $url,
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
            'url' => $url,
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

    /**
     * 优化结果
     * @param $res
     * @return mixed
     */
    public function dealRes($res){
        return $res;
    }
}