<?php
/**
 * Created by PhpStorm.
 * Script Name: Wx.php
 * Create: 12/20/21 11:42 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\WxBot\Driver;

use ky\WxBot\Base;

class Webgo extends Base
{
    private $uuid;
    const API_CHECK_LOGIN = '/checklogin'; //验证机器人是否登录
    const API_GET_LOGIN_CODE = '/getlogincode'; //获取登录码

    const API_SEND_VIDEO_MSG = '/message/video'; // 发送视频消息
    const API_SEND_IMG_MSG = '/message/img'; // 发送图片消息
    const API_SEND_FILE_MSG = '/message/file'; // 发送文件消息
    const API_SEND_TEXT_MSG = '/message/text'; // 发送文本消息

    const API_MODIFY_FRIEND_REMARK = '/user/setremarkname'; //修改好友备注

    const API_INVITE_IN_GROUP = '/user/addfriendsintogroup'; // 邀请好友入群
    const API_GET_GROUP_MEMBER = '/user/group/members'; //获取群成员列表
    const API_REMOVE_GROUP_MEMBER = '/user/group/removemembers'; //将好友移除群

    const API_GET_GROUP_LIST = '/user/groups'; //获取群列表
    const API_GET_FRIEND_LIST = '/user/friends'; //获取好友列表
    const API_GET_ROBOT_INFO = '/user/info'; //获取机器人信息

    const API_GET_FILE_FO_BASE64 = 'GetFileFoBase64'; //获取文件 返回该文件的Base64编码
    const API_ACCEPT_TRANSFER = 'AccepteTransfer';// 同意转账
    const API_REJECT_TRANSFER = 'RejectTransfer';// 拒绝转账

    public function __construct($options = [])
    {
        parent::__construct($options);
        $this->uuid = $options['uuid'];
    }

    /**
     * 邀请好友入群
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function inviteInGroup($params = []){
        $params['group'] = $params['group_wxid'];
        $params['friends'] = is_string($params['friend_wxid']) ? [$params['friend_wxid']] : $params['friend_wxid'];
        return $this->doRequest(self::API_INVITE_IN_GROUP, $params);
    }

    /**
     * 群发文本
     * @param array $data
     * @return array|bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendTextToFriends($data = [])
    {
        $res = ['code' => 1, 'res' => 'success'];
        $to_wxid = is_array($data['to_wxid']) ? $data['to_wxid'] : explode(',', $data['to_wxid']);
        foreach($to_wxid as $id){
            $data['to'] = $id;
            $res = $this->doRequest(self::API_SEND_TEXT_MSG, $data);
            $this->sleep();
        }
        return $res;
    }

    /**
     * 发送文本
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendTextToFriend($params = []){
        $params['to'] = $params['to_wxid'];
        return $this->doRequest(self::API_SEND_TEXT_MSG, $params);
    }


    /**
     * 群发文件
     * @param array $data
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendFileToFriends($data = []){
        $res = ['code' => 1, 'res' => 'success'];
        $to_wxid = is_array($data['to_wxid']) ? $data['to_wxid'] : explode(',', $data['to_wxid']);
        foreach($to_wxid as $id){
            $data['to'] = $id;
            $res = $this->doRequest(self::API_SEND_FILE_MSG, $data);
            $this->sleep();
        }
        return $res;
    }

    /**
     * 发送文件给好友
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendFileMsg($params = []){
        $params['to'] = $params['to_wxid'];
        return $this->doRequest(self::API_SEND_FILE_MSG, $params);
    }

    /**
     * 群发图片
     * @param array $data
     * @return array|bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendImgToFriends($data = [])
    {
        $res = ['code' => 1, 'res' => 'success'];
        $data['msg'] = $data['path'];
        $to_wxid = is_array($data['to_wxid']) ? $data['to_wxid'] : explode(',', $data['to_wxid']);
        foreach($to_wxid as $id){
            $data['to'] = $id;
            $res = $this->doRequest(self::API_SEND_IMG_MSG, $data);
            $this->sleep();
        }
        return $res;
    }

    /**
     * 发送图片给好友
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendImgToFriend($params = []){
        $params['to'] = $params['to_wxid'];
        $params['msg'] = $params['path'];
        return $this->doRequest(self::API_SEND_IMG_MSG, $params);
    }

    /**
     * 发送视频
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendVideoMsg($params = [])
    {
        $params['to'] = $params['to_wxid'];
        return $this->doRequest(self::API_SEND_VIDEO_MSG, $params);
    }

    /**
     * 群发视频
     * @param array $data
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendVideoToFriends($data = []){
        $res = ['code' => 1, 'res' => 'success'];
        $to_wxid = is_array($data['to_wxid']) ? $data['to_wxid'] : explode(',', $data['to_wxid']);
        foreach($to_wxid as $id){
            $data['to'] = $id;
            $res = $this->doRequest(self::API_SEND_VIDEO_MSG, $data);
            $this->sleep();
        }
        return $res;
    }

    /**
     * 移除群成员
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function removeGroupMember($params = []){
        $params['group'] = $params['group_wxid'];
        $params['members'] = is_string($params['to_wxid']) ? [$params['to_wxid']] : $params['to_wxid'];
        return $this->doRequest(self::API_REMOVE_GROUP_MEMBER, $params);
    }

    /**
     * 设置好友备注名
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function setFriendRemarkName($params = []){
        $params['to'] = $params['to_wxid'];
        $params['remark_name'] = $params['note'];
        return $this->doRequest(self::API_MODIFY_FRIEND_REMARK, $params);
    }

    /**
     * 拉取群成员
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getGroupMembers($params = []){
        return $this->doRequest(self::API_GET_GROUP_MEMBER, ['group' => $params['group_wxid']]);
    }

    /**
     * 获取好友列表
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getFriends($params = []){
        return $this->doRequest(self::API_GET_FRIEND_LIST, $params, 'get');
    }

    /**
     * 获取群组列表
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getGroups($params = []){
        return $this->doRequest(self::API_GET_GROUP_LIST, $params, 'get');
    }

    /**
     * 验证登录情况
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function checkLogin($params = []){
        $this->uuid = $params['uuid'];
        return $this->doRequest(self::API_CHECK_LOGIN, $params);
    }

    /**
     * 获取当前用户信息
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getCurrentUser($params = []){
        return $this->doRequest(self::API_GET_ROBOT_INFO, [], 'get');
    }

    private function doRequest($api = '', $params = [],  $method='post'){
        $data = [
            'url' => $api . '?uuid=' . $this->uuid,
            'method' => $method,
            'headers' => ['AppKey' => $this->appKey]
        ];
        !empty($params) && $data['data'] = $params;
        return $this->request($data);
    }

    /**
     * 获取登录二维码
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getLoginCode(){
        return $this->doRequest(self::API_GET_LOGIN_CODE, [], 'get');
    }

    public function dealRes($res){
        if(empty($res['code'])){
            $res['code'] = 0;
            $res['errmsg'] = $res['msg'];
        }
        return $res;
    }

    public function forwardMsg($params = [])
    {
        // TODO: Implement forwardMsg() method.
    }

    public function forwardMsgToFriends($params = [])
    {
        // TODO: Implement forwardMsgToFriends() method.
    }

    public function sendMusicLinkMsg($params = [])
    {
        // TODO: Implement sendMusicLinkMsg() method.
    }

    public function sendShareLinkToFriends($params = [])
    {
        // TODO: Implement sendShareLinkToFriends() method.
    }

    public function sendShareLinkMsg($params = [])
    {
        // TODO: Implement sendShareLinkMsg() method.
    }

    public function sendLinkMsg($params = [])
    {
        // TODO: Implement sendLinkMsg() method.
    }

    public function sendCardMsg($params = [])
    {
        // TODO: Implement sendCardMsg() method.
    }

    public function deleteFriend($params = [])
    {
        return $this->apiUnSupport();
    }

    public function agreeFriendVerify($params = [])
    {
        // TODO: Implement agreeFriendVerify() method.
    }

    public function searchAccount($params = [])
    {
        // TODO: Implement searchAccount() method.
    }

    public function addFriendBySearch($params = [])
    {
        // TODO: Implement addFriendBySearch() method.
    }

    public function sendGroupMsgAndAt($params = [])
    {
        // TODO: Implement sendGroupMsgAndAt() method.
    }

    public function getGroupMemberInfo($params = [])
    {
        // TODO: Implement getGroupMemberInfo() method.
    }

    public function quitGroup($params = [])
    {
        return $this->apiUnSupport();
    }

    public function setGroupName($params = [])
    {
        return $this->apiUnSupport();
    }

    public function setGroupNotice($params = [])
    {
        // TODO: Implement setGroupNotice() method.
    }

    public function getGuest($content = [], $field = '')
    {
        // TODO: Implement getGuest() method.
    }

    public function sendMsgAtAll($params = [])
    {
        return $this->apiUnSupport();
    }

    public function getMemberInfo($params = [])
    {
        // TODO: Implement getMemberInfo() method.
    }

    public function cleanChatHistory($params = [])
    {
        return $this->apiUnSupport();
    }

    public function sendCardToFriend($params = [])
    {
        // TODO: Implement sendCardToFriend() method.
    }

    public function sendCardToFriends($params = [])
    {
        // TODO: Implement sendCardToFriends() method.
    }

    public function buildingGroup($params = [])
    {
        // TODO: Implement buildingGroup() method.
    }

    public function getMoments($params = [])
    {
        // TODO: Implement getMoments() method.
    }

    public function getFriendMoments($params = [])
    {
        // TODO: Implement getFriendMoments() method.
    }

    public function likeMoments($params = [])
    {
        // TODO: Implement likeMoments() method.
    }

    public function commentMoments($params = [])
    {
        // TODO: Implement commentMoments() method.
    }

    public function sendMomentsText($params = [])
    {
        // TODO: Implement sendMomentsText() method.
    }

    public function sendMomentsImg($params = [])
    {
        // TODO: Implement sendMomentsImg() method.
    }

    public function sendMomentsVideo($params = [])
    {
        // TODO: Implement sendMomentsVideo() method.
    }

    public function sendMomentsLink($params = [])
    {
        // TODO: Implement sendMomentsLink() method.
    }

    public function sendMomentsXml($params = [])
    {
        // TODO: Implement sendMomentsXml() method.
    }

    public function favoritesMsg($params = [])
    {
        // TODO: Implement favoritesMsg() method.
    }

    public function getFavorites($params = [])
    {
        // TODO: Implement getFavorites() method.
    }

    public function sendFavoritesMsg($params = [])
    {
        // TODO: Implement sendFavoritesMsg() method.
    }

    public function downloadFile($params = [])
    {
        return $this->apiUnSupport();
    }


    public function acceptTransfer($params = [])
    {
        return $this->apiUnSupport();
    }

    public function rejectTransfer($params = [])
    {
        return $this->apiUnSupport();
    }

    public function sendXml($params = [])
    {
        return $this->apiUnSupport();
    }

    public function sendXmlToFriends($params = [])
    {
        return $this->apiUnSupport();
    }
}