<?php
/**
 * Created by PhpStorm.
 * Script Name: Extian.php
 * Create: 2023/3/8 10:46
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\WxBot\Driver;


use app\constants\Bot;
use ky\Logger;
use ky\WxBot\Base;

class Extian extends Base
{
    const EVENT_GROUP_MEMBER_DEC = 'chatroommemberSub';
    const EVENT_GROUP_MEMBER_ADD = 'chatroommemberAdd';
    const EVENT_FRIEND_VERIFY = 'verifyuser'; // TYPE=706             好友验证结果
    //tenpay  705                 收款结果
    const EVENT_NEW_MSG = 'newmsg';
    const EVENT_GROUP_MEMBER_DETAIL = 'getchatroommemberdetail'; // 701 群成员信息更新（new为1表示新成员，这里有邀请人id）
    const EVENT_GET_CONTACT = 'getcontact'; //704 联系人信息更新

    const MSG_FILE = 49;

    //消息类型映射
    const MSG_TYPES = [
        self::MSG_FILE => Bot::MSG_FILE
    ];

    /**
     * @var int
     */
    private $clientId;

    const API_NOTIFY = 'notify';
    const API_GET_ROBOT_LIST = 'list'; //获取机器人列表
    const API_GET_ROBOT_INFO = 'getInfo';
    const API_INJECT_WECHAT = 'run'; //
    const API_GET_LOGIN_CODE = 'gotoQr';
    const API_GET_FRIEND_LIST = 'getUser';
    const API_GET_GROUP_LIST = 'getGroup';
    const API_GET_GROUP_MEMBER = 'getGroupUser';
    const API_REMOVE_GROUP_MEMBER = 'delRoomMember';
    const API_INVITE_IN_GROUP = 'addGroupMember';
    const API_QUIT_GROUP = 'quitChatRoom';
    const API_MODIFY_FRIEND_REMARK = 'setRemark';
    const API_DELETE_FRIEND = 'deleteUser';
    const API_CLEAN_CHAT_HISTORY = 'ClearMsgList';
    const API_SET_GROUP_NAME = 'setRoomName';
    const API_BUILDING_GROUP = 'createRoom'; //建群
    const API_GET_MEMBER_INFO = 'getUserInfo';
    const API_AGREE_FRIEND_VERIFY = 'agreeUser'; // 同意好友请求
    const API_CHECK_IS_FRIEND = 'addUserCheck'; //判断是否单向好友

    const API_GET_MOMENTS = 'back_get_sns'; //获取朋友圈列表

    const API_GET_MSG = 'getMsg'; //获取消息内容
    const API_GET_FILE_FO_BASE64 = 'GetFileFoBase64'; //获取文件 返回该文件的Base64编码
    const API_SEND_MUSIC_LINK_MSG = 'SendMusicLinkMsg'; //发送一条可播放的歌曲链接
    const API_SAVE_FILE = 'savefile'; //保存文件

    const API_SEND_EMOJI = 'sendEmoji'; //发送表情
    const API_SEND_XML = 'sendAppmsgForward'; //发送xml消息
    const API_SEND_IMG = 'sendImage'; //发送图片
    const API_SEND_TEXT = 'sendText'; //发送文本
    const API_FORWARD_MSG = 'forwardMsg'; //转发消息
    const API_SEND_VIDEO_MSG = 'sendFile'; // 发送视频消息，
    const API_SEND_FILE_MSG = 'sendFile'; // 发送文件消息，
    const API_DOWNLOAD_FILE = 'getimgbyid'; //下载文件到机器人服务器本地，
    const API_SEND_SHARE_LINK_MSG = 'sendAppmsgForward'; //发送普通分享链接
    const API_SEND_LINK_MSG = 'SendLinkMsg'; //发送链接消息，只支持pro版
    const API_SEND_CARD_MSG = "sendCard"; //发送名片消息
    const API_INVITE_IN_GROUP_BY_LINK = 'sendGroupInvite';
    const API_SEND_GROUP_MSG_AND_AT = 'sendText';
    const API_CALL_VOIP_AUDIO = 'callVoipAudio'; //拨打语音电话


    const API_ACCEPTE_TRANSFER = 'agreeCash'; //同意转账
    const API_REJECT_TRANSFER = ''; //拒收转账

    public function __construct($options = [])
    {
        parent::__construct($options);
        $this->clientId = $options['uuid'] ?? 0;
    }

    private function doRequest($api = '', $params = []){
        $params['method'] = $api;
        if(empty($params['pid'])){
            $params['pid'] = $this->clientId;
            isset($params['uuid']) && $params['pid'] = $params['uuid'];
        }

        return $this->request([
            'url' => '/api?json&key=' . $this->appKey,
            'data' => $params
        ]);
    }

    /**
     * 统一类型
     * @param int $type
     * @return int|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function msgMap($type = 0){
        //Logger::error('ex'.$type);
        return self::MSG_TYPES[$type] ?? $type;
    }

    /**
     * 判断是否单向好友
     * {
     *      ...
           "data": { //是单向好友
                "msg": "user need verify",
                "result": -44,
                "wxid": "",
                "encryptusername": ""
            }
     * }
     *
     * {
     *      ...
            "data": { //双向好友
                "msg": "",
                "result": 0,
                "wxid": "",
                "encryptusername": ""
            }
     * }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function checkIsFriend($params = [])
    {
        return $this->doRequest(self::API_CHECK_IS_FRIEND, $params);
    }

    public function sendEmojiToFriend($params = [])
    {
        $params['wxid'] = $params['to_wxid'];
        return $this->doRequest(self::API_SEND_EMOJI, $params);
    }

    /**
     * res:{
            "method": "callVoipAudio",
            "wxid": "wxid_vw2prmx8xv5n22"
        }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function callVoipAudio($params = []){
        return $this->doRequest(self::API_CALL_VOIP_AUDIO, $params);
    }

    /**
    res: 同意好友请求
    robot_wxid (string)  // 机器人ID
    encryptusername (string)  // 收到好友验证消息中（json）的v1属性
    ticket (string)  // 收到好友验证消息中（json）的v2属性
    scene (int)  // 收到好友验证消息中（json）的type属性
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function agreeFriendVerify($params = []){
        $params['encryptusername'] = $params['encryptusername'] ?? $params['v1'];
        $params['ticket'] = $params['ticket'] ?? $params['v2'];
        $params['scene'] = $params['scene'] ?? $params['type'];
        return $this->doRequest(self::API_AGREE_FRIEND_VERIFY, $params);
    }

    /**
     * 获取机器人列表
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getRobotList($params = []){
        return $this->doRequest(self::API_GET_ROBOT_LIST, $params);
    }

    /**
     * 获取登录二维码
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getLoginCode($params = []){
        $params['pid'] = $params['client_id'];
        return $this->doRequest(self::API_GET_LOGIN_CODE, $params);
    }

    /**
     * 注入微信
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function injectWechat(){
        $params['pid'] = -1;
        return $this->doRequest(self::API_INJECT_WECHAT, $params);
    }

    /**
     * 获取微信信息
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getRobotInfo($params = []){
        $params['pid'] = $params['client_id'];
        return $this->doRequest(self::API_GET_ROBOT_INFO, $params);
    }

    public function getMoments($params = [])
    {
        return $this->apiUnSupport();
    }

    public function getFriendMoments($params = [])
    {
        return $this->apiUnSupport();
    }

    public function likeMoments($params = [])
    {
        return $this->apiUnSupport();
    }

    public function commentMoments($params = [])
    {
        return $this->apiUnSupport();
    }

    public function sendMomentsText($params = [])
    {
        return $this->apiUnSupport();
    }

    public function sendMomentsImg($params = [])
    {
        return $this->apiUnSupport();
    }

    public function sendMomentsVideo($params = [])
    {
        return $this->apiUnSupport();
    }

    public function sendMomentsLink($params = [])
    {
        return $this->apiUnSupport();
    }

    public function sendMomentsXml($params = [])
    {
        return $this->apiUnSupport();
    }

    public function favoritesMsg($params = [])
    {
        return $this->apiUnSupport();
    }

    public function getFavorites($params = [])
    {
        return $this->apiUnSupport();
    }

    public function sendFavoritesMsg($params = [])
    {
        return $this->apiUnSupport();
    }

    public function forwardMsgToFriends($params = [])
    {
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        foreach($to_wxid as $id){
            $params['to_wxid'] = $id;
            $this->forwardMsg($params);
            $this->sleep();
        }
        return ['code' => 1];
    }

    public function forwardMsg($params = [])
    {
        $params['wxid'] = $params['to_wxid'];
        $params['sid'] = $params['msgid'];
        return $this->doRequest(self::API_FORWARD_MSG, $params);
    }

    public function sendVideoToFriends($params = [])
    {
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        foreach($to_wxid as $id){
            $params['to_wxid'] = $id;
            $res = $this->sendVideoMsg($params);
            if(empty($res['code'])){
                return $res;
            }
            $this->sleep();
        }
        return ['code' => 1];
    }

    public function sendVideoMsg($params = [])
    {
        $path = $params['path'];
        if(strpos($path, 'http') !== false){ //先下载到本地
            $res = $this->saveFile([
                'uuid' => $params['uuid'],
                'data' => $path
            ]);
            if(!empty($res['code'])){
                $path = $res['data'];
            }
        }
        $params['file'] = $path;
        $params['fileType'] = empty($params['file_type']) ? 'file' : $params['file_type'];
        $params['wxid'] = $params['to_wxid'];
        return $this->doRequest(self::API_SEND_VIDEO_MSG, $params);
    }

    public function sendCardToFriend($params = [])
    {
        $params['xml'] = $params['content'];
        $params['wxid'] = $params['to_wxid'];
        return $this->doRequest(self::API_SEND_CARD_MSG, $params);
    }

    public function sendCardToFriends($params = [])
    {
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        foreach($to_wxid as $id){
            $params['to_wxid'] = $id;
            $this->sendCardToFriend($params);
            $this->sleep();
        }
        return ['code' => 1];
    }

    public function sendXml($params = [])
    {
        $params['wxid'] = $params['to_wxid'];
        return $this->doRequest(self::API_SEND_XML, $params);
    }

    public function sendXmlToFriends($params = [])
    {
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        foreach($to_wxid as $id){
            $params['to_wxid'] = $id;
            $this->sendXml($params);
            $this->sleep();
        }
        return ['code' => 1];
    }


    public function sendImgToFriends($params = [])
    {
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        foreach($to_wxid as $id){
            $params['to_wxid'] = $id;
            $this->sendImgToFriend($params);
            $this->sleep();
        }
        return ['code' => 1];
    }

    public function sendImgToFriend($params = [])
    {
        $params['img'] = $params['path'];
        $params['imgType'] = empty($params['img_type']) ? 'url' : $params['img_type'];
        $params['wxid'] = $params['to_wxid'];
        $res = $this->doRequest(self::API_SEND_IMG, $params);
        if(empty($res['code']) && $params['imgType'] == 'url'){
            $res = $this->saveFile([
                'data' => $params['img']
            ]);
            if(!empty($res['code'])){
                $params['img'] = $res['data'];
            }
            $params['imgType'] = "file";
            $res = $this->doRequest(self::API_SEND_IMG, $params);
        }
        return $res;
    }

    public function sendTextToFriends($params = [])
    {
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        foreach($to_wxid as $id){
            $params['to_wxid'] = $id;
            $this->sendTextToFriend($params);
            $this->sleep();
        }
        return ['code' => 1];
    }

    public function sendTextToFriend($params = [])
    {
        $params['wxid'] = $params['to_wxid'];
        $params['msg'] = str_replace("\r", "", $params['msg']);
        return $this->doRequest(self::API_SEND_TEXT, $params);
    }

    public function sendFileToFriends($params = [])
    {
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        foreach($to_wxid as $id){
            $params['to_wxid'] = $id;
            $this->sendFileMsg($params);
            $this->sleep();
        }
        return ['code' => 1];
    }

    public function sendFileMsg($params = [])
    {
        $params['file'] = $params['path'];
        $params['fileType'] = empty($params['file_type']) ? 'url' : $params['file_type'];
        $params['wxid'] = $params['to_wxid'];
        $res = $this->doRequest(self::API_SEND_FILE_MSG, $params);
        if(empty($res['code']) && $params['fileType'] == 'url'){
            $res = $this->saveFile([
                'data' => $params['file']
            ]);
            if(!empty($res['code'])){
                $params['file'] = $res['data'];
            }
            $params['fileType'] = "file";
            $res = $this->doRequest(self::API_SEND_FILE_MSG, $params);
        }
        return $res;
    }

    public function sendShareLinkToFriends($params = [])
    {
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        foreach($to_wxid as $id){
            $params['to_wxid'] = $id;
            $this->sendShareLinkMsg($params);
            $this->sleep();
        }
        return ['code' => 1];
    }

    public function sendShareLinkMsg($params = [])
    {
        $params['wxid'] = $params['to_wxid'];
        $params['xml'] = "<?xml version=\"1.0\"?><msg><appmsg appid=\"\" sdkver=\"0\"><title>{$params['title']}</title><des>{$params['desc']}</des><type>5</type><url>{$params['url']}</url><thumburl>{$params['image_url']}</thumburl></appmsg></msg>";
        return $this->doRequest(self::API_SEND_SHARE_LINK_MSG, $params);
    }

    public function sendMusicLinkMsg($params = [])
    {
        return $this->apiUnSupport();
    }

    public function sendLinkMsg($params = [])
    {
        return $this->apiUnSupport();
    }

    public function acceptTransfer($params = [])
    {
        $params['wxid'] = $params['from_wxid'];
        $params['transferid'] = $params['payer_pay_id'];
        return $this->doRequest(self::API_ACCEPTE_TRANSFER, $params);
    }

    public function rejectTransfer($params = [])
    {
        return $this->apiUnSupport();
    }

    public function setFriendRemarkName($params = [])
    {
        $params['wxid'] = $params['to_wxid'];
        $params['msg'] = $params['note'];
        return $this->doRequest(self::API_MODIFY_FRIEND_REMARK, $params);
    }

    public function deleteFriend($params = [])
    {
        $params['wxid'] = $params['to_wxid'];
        return $this->doRequest(self::API_DELETE_FRIEND, $params);
    }

    public function searchAccount($params = [])
    {
        return $this->apiUnSupport();
    }

    public function addFriendBySearch($params = [])
    {
        return $this->apiUnSupport();
    }

    public function getFriends($params = [])
    {
        //$params = ['client_id' => $params['data']['uuid']];
        return $this->doRequest(self::API_GET_FRIEND_LIST, $params);
    }

    public function getMemberInfo($params = [])
    {
        $params['wxid'] = $params['to_wxid'];
        return $this->doRequest(self::API_GET_MEMBER_INFO, $params);
    }

    /**
     *
     * 修改群名称
    robot_wxid (string)  // 机器人ID
    group_wxid (string)  // 群ID
    group_name (string)  // 新的群名称
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function setGroupName($params = [])
    {
        $params['msg'] = $params['group_name'];
        $params['wxid'] = $params['group_wxid'];
        return $this->doRequest(self::API_SET_GROUP_NAME, $params);
    }

    public function buildingGroup($params = [])
    {
        is_string($params['wxids']) && $params['wxids'] = explode(',', $params['wxids']);
        $params['msg'] = implode('|', $params['wxids']);
        return $this->doRequest(self::API_BUILDING_GROUP, $params);
    }

    public function getGuest($content = [], $field = '')
    {
        if(!empty($content['member'][0])){
            $guest = $content['member'][0];
            $guest['nickname'] = $guest['nickName'];
            $guest['headimgurl'] = $guest['img'];
        }else{
            $guest = [
                'nickname' => '',
                'wxid' => '',
                'headimgurl' => ''
            ];
        }
        /*$member_info = $this->getMemberInfo([
            'uuid' => $content['uuid'] ?? '',
            'to_wxid' => $content['from_wxid']
        ]);
        if(!empty($member_info['data'])){
            $guest = [
                'nickname' => $member_info['data']['nickName'] ?? '',
                'wxid' => $content['from_wxid'],
                'headimgurl' => $member_info['data']['headImg']
            ];
            $guest['nickname'] = $guest['nickName'];
            $guest['headimgurl'] = $guest['img'];
        }else{
            $guest = [
                'nickname' => '',
                'wxid' => '',
                'headimgurl' => ''
            ];
        }*/
        return isset($guest[$field]) ? $guest[$field] : $guest;
    }

    public function getGroupMembers($params = [])
    {
        $params = [
            'client_id' => $params['uuid'],
            'wxid' => $params['group_wxid'],
        ];
        return $this->doRequest(self::API_GET_GROUP_MEMBER, $params);
    }

    public function getGroups($params = [])
    {
        $params = [
            'client_id' => $params['data']['uuid']
        ];
        return $this->doRequest(self::API_GET_GROUP_LIST, $params);
    }

    public function sendGroupMsgAndAt($params = [])
    {
        $params['wxid'] = $params['group_wxid'];
        $params['atid'] = $params['member_wxid'];
        return $this->doRequest(self::API_SEND_GROUP_MSG_AND_AT, $params);
    }

    public function sendMsgAtAll($params = [])
    {
        return $this->apiUnSupport();
    }

    public function removeGroupMember($params = [])
    {
        $params['wxid'] = $params['group_wxid'];
        $params['msg'] = $params['to_wxid'];
        return $this->doRequest(self::API_REMOVE_GROUP_MEMBER, $params);
    }

    /**
    * res:
    * robot_wxid (string)  // 机器人ID
    * group_wxid (string)  // 群ID
    * to_wxid (string|array)  // 好友ID
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function inviteInGroupByLink($params = []){
        $params['msg'] = $params['to_wxid'];
        $params['wxid'] = $params['group_wxid'];
        return $this->doRequest(self::API_INVITE_IN_GROUP_BY_LINK, $params);
    }

    public function inviteInGroup($params = [])
    {
        $params['wxid'] = $params['group_wxid'];
        $params['msg'] = $params['to_wxid'];
        return $this->doRequest(self::API_INVITE_IN_GROUP, $params);
    }

    public function getGroupMemberInfo($params = [])
    {
        // TODO: Implement getGroupMemberInfo() method.
    }

    public function quitGroup($params = [])
    {
        $params['wxid'] = $params['group_wxid'];
        return $this->doRequest(self::API_QUIT_GROUP, $params);
    }

    public function setGroupNotice($params = [])
    {
        return $this->apiUnSupport();
    }

    public function cleanChatHistory($params = [])
    {
        return $this->doRequest(self::API_CLEAN_CHAT_HISTORY, $params);
    }

    public function downloadFile($params = [])
    {
        $params['sid'] = $params['path']; //消息id
        return $this->doRequest(self::API_DOWNLOAD_FILE, $params);
    }

    public function dealRes($res)
    {
        /*if((isset($res['status']) && $res['status']!= 0) || !empty($res['pid']) || !empty($res['pids']) || !empty($res['data'])){
            $res['code'] = 1;
        }else{
            $this->errMsg = $res['msg'];
            $res['errmsg'] = $this->errMsg;
            $res['code'] = 0;
        }*/
        if((isset($res['status']) && $res['status']== 0) || !empty($res['msg'])){
            $this->errMsg = $res['msg'] ?? '';
            $res['errmsg'] = $this->errMsg;
            $res['code'] = 0;
        }else{
            $res['code'] = 1;
        }
        return $res;
    }

    public static function response()
    {
        echo json_encode(['code' => 200, 'msg' => 'ok', 'timestamp' => time() * 1000]);
    }

    public function notify($params = [])
    {
        return $this->doRequest(self::API_NOTIFY, $params);
    }

    /**
     * 可选参数:
    sid:返回等于该id的消息(sid为消息通知中的19位id)
    id:返回大于该id的消息
    flag:返回小于上述id的消息
    type:返回指定类型的消息
    msg:返回包含相关内容的消息(搜xml加前缀x:)
    fromid:返回指定对象消息
    memid:返回指定群成员消息
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getMsg($params = []){
        !empty($params['msgid']) && $params['sid'] = $params['msgid'];
        //!empty($params['id']) && $params['id'] = $params['id'];
        return $this->doRequest(self::API_GET_MSG, $params);
    }

    /**
     * 手动保存文件
     * req{
            type: 可选type:base64,hex,url
     *      data: "",
     *      path: "保存文件名称"
     * }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function saveFile($params = []){
        $params["type"] = empty($params['type']) ? "url" : $params['type'];
        if(empty($params['path'])){
            $path_arr = explode('/', $params['data']);
            $params['path'] = time().'-'.end($path_arr);
        }
        //$params['data'] = $params['path'];
        return $this->doRequest(self::API_SAVE_FILE, $params);
    }
}