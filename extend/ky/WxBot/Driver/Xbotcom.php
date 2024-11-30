<?php
/**
 * Created by PhpStorm.
 * Script Name: Xbot.php
 * Create: 11/25/22 9:48 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\WxBot\Driver;


use app\constants\Bot as BotConst;
use ky\Logger;
use ky\WxBot\Base;

class Xbotcom extends Base
{
    const MSG_TEXT = 2; //文本消息
    const MSG_IMG = 101; //图片消息

    const API_CDN_UPLOAD_BY_URL = 'WW_CDN_UPLOAD_BY_URL_MSG';
    const API_SEND_IMG = 'MT_SEND_IMGMSG_BY_URL'; //发送图片
    const API_SEND_TEXT = 'WW_SEND_TEXT_V2_MSG'; //发送文本
    const API_FORWARD_MSG = 'MT_FORWARD_ANY_MSG'; //转发消息
    const API_SEND_VIDEO_MSG = 'WW_SEND_VIDEO_MSG'; // 发送视频消息
    const API_SEND_FILE_MSG = 'WW_SEND_FILE_MSG_BY_URL'; // 发送文件消息，只支持pro版
    const API_DOWNLOAD_FILE = 'DownloadFile'; //下载文件到机器人服务器本地，只支持pro版
    const API_SEND_MUSIC_LINK_MSG = 'SendMusicLinkMsg'; //发送一条可播放的歌曲链接
    const API_SEND_SHARE_LINK_MSG = 'MT_SEND_LINKMSG'; //发送普通分享链接
    const API_SEND_LINK_MSG = 'SendLinkMsg'; //发送链接消息，只支持pro版
    const API_SEND_CARD_MSG = "SendCardMsg"; //发送名片消息
    const API_GET_FILE_FO_BASE64 = 'GetFileFoBase64'; //获取文件 返回该文件的Base64编码
    const API_ACCEPT_TRANSFER = 'AccepteTransfer';// 同意转账
    const API_REJECT_TRANSFER = 'RejectTransfer';// 拒绝转账

    const API_GET_FRIEND_LIST = 'WW_DATA_EXTERNAL_LIST_MSG'; //获取外部联系人

    const API_QUIT_GROUP = 'MT_QUIT_DEL_ROOM_MSG'; // 退群
    const API_GET_GROUP_MEMBER = "MT_DATA_CHATROOM_MEMBERS_MSG"; //获取群成员列表
    const API_SET_GROUP_NAME = 'MT_MOD_ROOM_NAME_MSG'; //
    const API_GET_GROUP_LIST = 'WW_DATA_EXTERNAL_ROOM_LIST_MSG'; //获取客户群列表

    const API_CLEAN_CHAT_HISTORY = 'MT_CLEAR_CHAT_HISTORY_MSG'; //清空聊天记录
    const API_INJECT_WECHAT = 'MT_INJECT_WECHAT'; //
    const API_GET_LOGIN_CODE = 'MT_RECV_QRCODE_MSG';
    const API_GET_ROBOT_INFO = 'MT_DATA_OWNER_MSG';
    const API_EXIT = 'MT_QUIT_WECHAT_MSG'; //退出微信程序
    const API_PING = 'PING'; //退出微信程序

    const EVENT_CONNECTED = 'MT_CLIENT_CONTECTED'; //注入微信
    const EVENT_LOGIN_CODE = 'MT_RECV_QRCODE_MSG'; //显示登录码
    const EVENT_LOGIN = 'MT_USER_LOGIN'; //登录微信
    const EVENT_LOGOUT = 'MT_USER_LOGOUT'; //退出微信
    const EVENT_GROUP_MEMBER_ADD = 'MT_ROOM_ADD_MEMBER_NOTIFY_MSG'; //群人员增加
    const EVENT_GROUP_MEMBER_DEC = 'MT_ROOM_DEL_MEMBER_NOTIFY_MSG'; //群人员减少


    /**
     * @var string
     */
    private $token;

    public function __construct($options = [])
    {
        parent::__construct($options);
        $this->token = $this->appKey;
    }

    static function contentTypes($id = null){
        $list = [
            self::MSG_TEXT => BotConst::MSG_TEXT,
            self::MSG_IMG => BotConst::MSG_IMG
        ];
        return $list[$id] ?? $id;
    }

    /**
     * @param array $options
     * @return object
     */
    public static function init($options = [])
    {
        return new static($options);
    }


    function uploadCdn($params = []){
        $params['client_id']= $params['uuid'];
        return $this->doRequest(self::API_CDN_UPLOAD_BY_URL, $params);
    }

    function ping($params = []){
        $params['client_id']= $params['uuid'];
        return $this->doRequest(self::API_PING);
    }

    /**
     * 退出微信程序
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function exit($params = [])
    {
        $params['client_id']= $params['uuid'];
        return $this->doRequest(self::API_EXIT, $params);
    }

    public function forwardMsgToFriends($params = [])
    {
        $data = $params;
        $data['client_id']= $params['uuid'];
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', trim($params['to_wxid'], ','));
        unset($data['to_wxid']);
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $this->doRequest(self::API_FORWARD_MSG, $data);
            $this->sleep();
        }
        return ['code' => 1];
    }

    public function forwardMsg($params = [])
    {
        $params['client_id']= $params['uuid'];
        return $this->doRequest(self::API_FORWARD_MSG, $params);
    }

    public function sendShareLinkToFriends($params = [])
    {
        $data = $params;
        $data['client_id']= $params['uuid'];
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', trim($params['to_wxid'], ','));
        unset($data['to_wxid']);
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $this->doRequest(self::API_SEND_SHARE_LINK_MSG, $data);
            $this->sleep();
        }
        return ['code' => 1];
    }

    public function sendShareLinkMsg($params = [])
    {
        $params['client_id'] = $params['uuid'];
        return $this->doRequest(self::API_SEND_SHARE_LINK_MSG, $params);
    }

    public function sendImgToFriends($params = [])
    {
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', trim($params['to_wxid'], ','));
        foreach($to_wxid as $id){
            $params['to_wxid'] = $id;
            $this->sendImgToFriend($params);
            $this->sleep();
        }
        return ['code' => 1];
    }

    /**
     * req:{
    "data": {
    "to_wxid": "filehelper",
    "content": "新版发送文本接口"
    },
    "client_id": 1,
    "type": "MT_SEND_TEXT_V2_MSG"
     *
     * }
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendImgToFriend($params = [])
    {
        $params = [
            'client_id' => $params['uuid'],
            'conversation_id' => $params['to_wxid'],
            'url' => $params['url']??$params['path'],
            'file_name' => $params['filename']??basename($params['path'])
        ];
        return $this->doRequest(self::API_SEND_IMG, $params);
    }

    public function sendTextToFriends($params = [])
    {
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', trim($params['to_wxid'], ','));
        foreach($to_wxid as $id){
            $params['to_wxid'] = $id;
            $this->sendTextToFriend($params);
            $this->sleep();
        }
        return ['code' => 1];
    }

    /**
     * req:{
        "data": {
        "to_wxid": "filehelper",
        "content": "新版发送文本接口"
        },
        "client_id": 1,
        "type": "MT_SEND_TEXT_V2_MSG"
     *
     * }
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendTextToFriend($params = [])
    {
        $params = [
            'client_id' => $params['uuid'],
            'content' => $params['msg'],
            'conversation_id' => $params['to_wxid']
        ];
        return $this->doRequest(self::API_SEND_TEXT, $params);
    }

    public function cleanChatHistory($params = [])
    {
        $params = [
            'client_id' => $params['uuid']
        ];
        return $this->doRequest(self::API_CLEAN_CHAT_HISTORY, $params);
    }

    /**
     * req:{
     * "data": {
     * "room_wxid": "xxxxxxx"
     * },
     * "client_id": 1,
     * "type": "MT_QUIT_DEL_ROOM_MSG"
     * }
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @return bool
     */
    public function quitGroup($params = [])
    {
        $params = [
            'client_id' => $params['uuid'],
            'room_wxid' => $params['group_wxid'],
        ];
        return $this->doRequest(self::API_QUIT_GROUP, $params);
    }

    /**
     * req:{
            "data": {
            "room_wxid": "xxxxxxx"
            },
            "client_id": 1,
            "type": "MT_DATA_CHATROOM_MEMBERS_MSG"
     * }
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getGroupMembers($params = [])
    {
        $params = [
            'client_id' => $params['uuid'],
            'room_wxid' => $params['group_wxid'],
        ];
        return $this->doRequest(self::API_GET_GROUP_MEMBER, $params);
    }

    /**
     * req:{
     * "data": {
     * "room_wxid": "xxxxxxx",
     * "name": "新群名"
     * },
     * "client_id": 1,
     * "type": "MT_MOD_ROOM_NAME_MSG"
     * }
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @return bool
     */
    public function setGroupName($params = [])
    {
        $params = [
            'client_id' => $params['uuid'],
            'room_wxid' => $params['group_wxid'],
            'name' => $params['group_name']
        ];
        return $this->doRequest(self::API_SET_GROUP_NAME, $params);
    }

    public function getGroups($params = [])
    {
        $params = [
            'client_id' => $params['data']['uuid'],
            'start_index' => $params['data']['start_index'] ?? 1,
            'limit' => $params['data']['limit'] ?? 1000
        ];
        return $this->doRequest(self::API_GET_GROUP_LIST, $params);
    }

    /**
     * resp:{
        data:[
     *      { "account" => "doogiefu"
            "avatar" => "http://wx.qlogo.cn/mmhead/ver_1/1rtV9unkWSGmcib5JIH6ypjppBYVtaUmshTicicncriajvAYnGsYkfbYgWVpnROaffbQlEt62etiaiaD8EiaVIibic840QF7Au50RciaicaWFDRiahVzJ0k/0"
            "city" => "Xiamen"
            "country" => "CN"
            "nickname" => "DJ"
            "province" => "Fujian"
            "remark" => ""
            "sex" => 1
            "wxid" => "wxid_xokb2ezu1p6t21"
            }
     * ]
     * }
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getFriends($params = []){
        $params = [
            'client_id' => $params['data']['uuid'],
            'page_num' => $params['data']['page_num'] ?? 1,
            'page_size' => $params['data']['page_size'] ?? 10000
        ];
        return $this->doRequest(self::API_GET_FRIEND_LIST, $params);
    }

    /**
     * 获取微信信息
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getRobotInfo($params = []){
        return $this->doRequest(self::API_GET_ROBOT_INFO, $params);
    }

    private function doRequest($api = '', $params = []){
        //return $this->buildPostData($api, $params);
        return $this->request([
            'headers' => ['token' => $this->token],
            'data' => $this->buildPostData($api, $params)
        ]);
    }

    private function buildPostData($api = '', $params = []){
        $data = [
            'type' => $api,
        ];
        if(!empty($params['client_id'])){
            $data['client_id'] = $params['client_id'];
        }
        $data['is_sync'] = $params['is_sync'] ?? 0;
        unset($params['is_sync'], $params['client_id']);
        $data['data'] = $params;
        return $data;
    }

    /**
     * 优化结果
     * @param $res
     * @return array
     */
    public function dealRes($res){
        if(intval($res['err_code']) === 0){
            $res['code'] = 1;
        }else{
            $res['errmsg'] = $res['err_msg'];
            $res['code'] = 0;
        }
        return $res;
    }

    private  function  errors($err_no = -100){
        $list = [
            1 => 'token错误',
            2 => '已达到最大客户端数量',
            -100 => '未知错误',
        ];
        $this->errMsg = isset($list[$err_no]) ? $list[$err_no] : $list[-100];
    }

    /**
     * 注入微信
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function injectWechat($params = []){
        return $this->doRequest(self::API_INJECT_WECHAT, $params);
    }

    /**
     * 获取登录二维码
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getLoginCode($params = []){
        return $this->doRequest(self::API_GET_LOGIN_CODE, $params);
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

    public function sendCardToFriend($params = [])
    {
        // TODO: Implement sendCardToFriend() method.
    }

    public function sendCardToFriends($params = [])
    {
        // TODO: Implement sendCardToFriends() method.
    }

    public function sendVideoToFriends($params = [])
    {
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', trim($params['to_wxid'], ','));
        foreach($to_wxid as $id){
            $params['to_wxid'] = $id;
            $this->sendVideoMsg($params);
            $this->sleep();
        }
        return ['code' => 1];
    }

    public function sendVideoMsg($params = [])
    {
        $url = $params['file']??$params['path'];
        $key = md5($url);
        $file = cache($key);
        if(empty($file)){
            $params['url'] = $url;
            $params['file_type'] = 4;
            $res = $this->uploadCdn($params);
            if(!empty($res['data']['file_path'])){
                $file = $res['data']['file_path'];
                cache($key, $file);
            }else{
                return ['code' => 0, 'errmsg' => '发送失败'];
            }
        }
        $params = [
            'client_id' => $params['uuid'],
            'conversation_id' => $params['to_wxid'],
            'file' => $file
        ];
        return $this->doRequest(self::API_SEND_VIDEO_MSG, $params);
    }

    public function sendFileToFriends($params = [])
    {
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', trim($params['to_wxid'], ','));
        foreach($to_wxid as $id){
            $params['to_wxid'] = $id;
            $this->sendFileMsg($params);
            $this->sleep();
        }
        return ['code' => 1];
    }

    public function sendFileMsg($params = [])
    {
        $params = [
            'client_id' => $params['uuid'],
            'conversation_id' => $params['to_wxid'],
            'url' => $params['url']??$params['path'],
            'file_name' => $params['filename']??basename($params['path'])
        ];
        return $this->doRequest(self::API_SEND_FILE_MSG, $params);
    }

    public function sendMusicLinkMsg($params = [])
    {
        // TODO: Implement sendMusicLinkMsg() method.
    }

    public function sendLinkMsg($params = [])
    {
        // TODO: Implement sendLinkMsg() method.
    }

    public function setFriendRemarkName($params = [])
    {
        // TODO: Implement setFriendRemarkName() method.
    }

    public function deleteFriend($params = [])
    {
        // TODO: Implement deleteFriend() method.
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

    public function getMemberInfo($params = [])
    {
        // TODO: Implement getMemberInfo() method.
    }

    public function buildingGroup($params = [])
    {
        // TODO: Implement buildingGroup() method.
    }

    public function getGuest($content = [], $field = '')
    {
        // TODO: Implement getGuest() method.
    }


    public function sendGroupMsgAndAt($params = [])
    {
        // TODO: Implement sendGroupMsgAndAt() method.
    }

    public function sendMsgAtAll($params = [])
    {
        // TODO: Implement sendMsgAtAll() method.
    }

    public function removeGroupMember($params = [])
    {
        // TODO: Implement removeGroupMember() method.
    }

    public function inviteInGroup($params = [])
    {
        // TODO: Implement inviteInGroup() method.
    }

    public function getGroupMemberInfo($params = [])
    {
        // TODO: Implement getGroupMemberInfo() method.
    }

    public function setGroupNotice($params = [])
    {
        // TODO: Implement setGroupNotice() method.
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