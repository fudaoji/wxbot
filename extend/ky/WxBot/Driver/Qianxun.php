<?php
/**
 * Created by PhpStorm.
 * Script Name: Qianxun.php
 * Create: 12/20/21 11:42 PM
 * Description: 千寻驱动
 * Link: https://gitee.com/daenmax/pc-wechat-hook-http-api
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\WxBot\Driver;
use ky\WxBot\Base;

class Qianxun extends Base
{
    //fromType	来源类型：1|私聊 2|群聊 3|公众号
    const FROM_TYPE_PRIVATE = 1;
    const FROM_TYPE_GROUP = 2;
    const FROM_TYPE_MP = 3;

    const EVENT_SYS_MSG = 'EventSysMsg'; //系统消息事件
    const EVENT_CONTACTS_CHANGE = 'EventContactsChange'; //朋友变动事件
    const EVENT_RECEIVE_TRANSFER = 'EventReceivedTransfer'; //收到转账
    const EVENT_SCAN_CASH_MONEY = 'EventScanCashMoney';
    const EVENT_FRIEND_VERIFY = 'EventFriendVerify';

    const EVENT_GROUP_MEMBER_DEC = 'EventGroupMemberDecrease'; //群成员减少事件（群成员退出）
    const EVENT_GROUP_MEMBER_ADD = 'EventGroupMemberAdd'; //群成员增加事件（新人进群）
    const EVENT_PRIVATE_CHAT = '10009';
    const EVENT_GROUP_CHAT = '10008';
    const EVENT_LOGIN = '10014'; //type	1=上线，0=下线

    const API_GET_ROBOT_LIST = 'X0000';
    const API_GET_ROBOT_INFO= 'Q0003'; //Q0003
    const API_GET_ROBOT_STATUS= 'Q0000'; // 微信运行状态
    const API_GET_FRIEND_LIST = 'Q0005'; //获取好友列表
    const API_GET_GROUP_LIST = 'Q0006'; //获取群列表
    const API_GET_MEMBER_INFO = "Q0004"; //获取对象信息好友|群聊|公众号
    const API_GET_GROUP_MEMBER_INFO = ""; //获取某个群成员信息

    const API_SEND_TEXT = 'Q0001';
    const API_SEND_IMG = 'Q0010'; //发送图片
    const API_SEND_FILE_MSG = 'Q0011'; // 发送文件消息
    const API_SEND_SHARE_LINK_MSG = 'Q0012'; //发送图文链接消息，
    const API_FORWARD_MSG = 'ForwardMsg'; //转发消息
    const API_SEND_VIDEO_MSG = 'SendVideoMsg'; // 发送视频消息，
    const API_SEND_MUSIC_MSG = 'SendMusicMsg'; //发送一条可播放的歌曲链接
    const API_SEND_EMOJI_MSG = 'SendEmojiMsg'; //emoji

    const API_AGREE_FRIEND_VERIFY = 'AgreeFriendVerify'; // 同意好友请求
    const API_AGREE_GROUP_INVITE = 'AgreeGroupInvite'; //同意群聊邀请
    const API_MODIFY_FRIEND_REMARK = 'EditFriendNote'; //修改好友备注
    const API_DELETE_FRIEND = 'DeleteFriend'; // 删除好友
    const API_SEARCH_ACCOUNT = "Q0020"; //搜索好友

    const API_GET_GROUP_MEMBERS = "Q0008"; //获取群成员列表
    const API_INVITE_IN_GROUP = 'InviteInGroup'; // 邀请好友入群
    const API_REMOVE_GROUP_MEMBER = 'RemoveGroupMember'; //将好友移除群
    const API_SET_GROUP_NAME = 'EditGroupName'; //
    const API_SET_GROUP_NOTICE = 'EditGroupNotice'; //
    const API_QUIT_GROUP = 'QuitGroup'; // 退群
    const API_SEND_GROUP_MSG_AND_AT = "SendGroupMsgAndAt"; //发送群消息并艾特成员
    const API_SEND_MSG_AT_ALL = 'SendMsgAtAll'; //艾特群员

    const API_ADD_FRIEND_BY_SEARCH = 'Q0018'; //通过手机号去添加企业微信好友,不可频繁调用。失败返回0 成功返回1 好友返回2 企业账号离线返回3 频繁返回-1
    const API_ADD_FRIEND_BY_WXID = 'Q0019'; //根据wxid添加好友
    const API_SEND_CARD_MSG = "SendCardMsg"; //发送名片消息，只支持pro版

    const API_GET_FILE_FO_BASE64 = 'GetFileFoBase64'; //获取文件 返回该文件的Base64编码
    const API_ACCEPT_TRANSFER = 'AccepteTransfer';// 同意转账
    const API_REJECT_TRANSFER = 'RejectTransfer';// 拒绝转账

    const FIELD_MAP = [
        "wxid" => "wxid",
        "wxNum" => "username",
        "nick" => "nickname",
        "remark" => "remark_name",
        'avatarUrl' => 'headimgurl',
        'avatarMaxUrl' => 'headimgurl',
        'groupNick' => 'group_nickname',
        'v3' => 'v1',
        'v4' => 'v2'
    ];

    public function __construct($options = [])
    {
        parent::__construct($options);
    }

    private function doRequest($params = [], $api = ''){
        $body = [
            "type" => $api,
            'data' => empty($params) ? [] : $params
        ];
        $url = "/DaenWxHook/httpapi/";
        !empty($params['robot_wxid']) && $url .= ('?wxid=' . $params['robot_wxid']);
        return $this->request([
            'url' => $url,
            'data' => $body
        ]);
    }

    /**
     * resp:{
        code: 200
        msg:string
        result: []|{}
        timestamp: int
     * }
     * @param $res
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function dealRes($res)
    {
        $res['ori_code'] = $res['code'];
        if(intval($res['code']) === 200){
            $res['code'] = 1;
        }else{
            //$this->errors($res['code']);
            $res['errmsg'] = $res['msg'];
            $res['code'] = 0;
        }
        return $res;
    }

    private  function  errors($err_no = 500){
        $list = [
            500 => '请求错误',
        ];
        $this->errMsg = isset($list[$err_no]) ? $list[$err_no] : $list[500];
    }

    public static function response()
    {
        echo json_encode(['code' => 200, 'msg' => 'ok', 'timestamp' => time() * 1000]);
    }

    /**
     * 映射结果字段
     * @param $data
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    private function dealResField($data){
        foreach (self::FIELD_MAP as $k => $v){
            isset($data[$k]) && $data[$v] = $data[$k];
        }
        return $data;
    }

    /**
     * 根据wxid添加好友
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function addFriendByWxid($params = [])
    {
        $params['content'] = $params['msg'];
        return $this->doRequest($params, self::API_ADD_FRIEND_BY_WXID);
    }

    /**
     * 根据搜索结果添加好友
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function addFriendBySearch($params = [])
    {
        $params['v3'] = $params[self::FIELD_MAP['v3']];
        $params['content'] = $params['msg'];
        return $this->doRequest($params, self::API_ADD_FRIEND_BY_SEARCH);
    }

    /**
     * 根据手机号、微信号、QQ号搜索
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function searchAccount($params = [])
    {
        $params['pq'] = $params['content'];
        $res = $this->doRequest($params, self::API_SEARCH_ACCOUNT);
        if($res['code']){
            $res['data'] = $this->dealResField($res['result']);
            unset($res['result']);
        }
        return $res;
    }

    public function getMemberInfo($params = [])
    {
        $params['wxid'] = $params['to_wxid'];
        $res = $this->doRequest($params, self::API_GET_MEMBER_INFO);
        if($res['code']){
            $res['data'] = $this->dealResField($res['result']);
            unset($res['result']);
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
        $params['content'] = $params['desc'];
        $params['jumpUrl'] = $params['url'];
        $params['path'] = $params['image_url'];
        return $this->doRequest($params, self::API_SEND_SHARE_LINK_MSG);
    }

    public function sendFileToFriends($params = [])
    {
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        foreach($to_wxid as $id){
            $params['wxid'] = $id;
            $this->doRequest($params, self::API_SEND_FILE_MSG);
            $this->sleep();
        }
        return ['code' => 1];
    }

    public function sendFileMsg($params = [])
    {
        $params['wxid'] = $params['to_wxid'];
        return $this->doRequest($params, self::API_SEND_FILE_MSG);
    }

    public function sendImgToFriends($params = [])
    {
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        foreach($to_wxid as $id){
            $params['wxid'] = $id;
            $this->doRequest($params, self::API_SEND_IMG);
            $this->sleep();
        }
        return ['code' => 1];
    }

    public function sendImgToFriend($params = [])
    {
        $params['wxid'] = $params['to_wxid'];
        return $this->doRequest($params, self::API_SEND_IMG);
    }

    public function sendTextToFriends($params = [])
    {
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        foreach($to_wxid as $id){
            $params['wxid'] = $id;
            $this->doRequest($params, self::API_SEND_TEXT);
            $this->sleep();
        }
        return ['code' => 1];
    }

    /**
     * 发送文本消息给好友
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendTextToFriend($params = []){
        $params['wxid'] = $params['to_wxid'];
        return $this->doRequest($params, self::API_SEND_TEXT);
    }

    public function getGroupMembers($params = [])
    {
        $params['wxid'] = $params['group_wxid'];
        $res = $this->doRequest($params, self::API_GET_GROUP_MEMBERS);
        if($res['code']){
            $data = [];
            foreach ($res['result'] as $v){
                $data[] = $this->dealResField($v);
            }
            unset($res['result']);
            $res['data'] = $data;
        }
        return $res;
    }

    public function getGroups($params = [])
    {
        $params = $params['data'];
        $params['type'] = isset($params['is_refresh']) ? ($params['is_refresh']+1) : 1;
        $res = $this->doRequest($params, self::API_GET_GROUP_LIST);
        if($res['code']){
            $data = [];
            foreach ($res['result'] as $v){
                $data[] = $this->dealResField($v);
            }
            unset($res['result']);
            $res['data'] = $data;
        }
        return $res;
    }

    public function getFriends($params = [])
    {
        $params = $params['data'];
        $params['type'] = isset($params['is_refresh']) ? ($params['is_refresh']+1) : 1;
        $res = $this->doRequest($params, self::API_GET_FRIEND_LIST);
        if($res['code']){
            $data = [];
            foreach ($res['result'] as $v){
                $data[] = $this->dealResField($v);
            }
            unset($res['result']);
            $res['data'] = $data;
        }
        return $res;
    }

    /**
     * 获取微信信息
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getRobotInfo($params = []){
        $res = $this->doRequest($params, self::API_GET_ROBOT_INFO);
        if($res['code']){
            $res['data'] = $this->dealResField($res['result']);
            unset($res['result']);
        }
        return  $res;
    }

    /**
     * 获取微信状态
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getRobotStatus($params = []){
        $res = $this->doRequest($params, self::API_GET_ROBOT_STATUS);
        if($res['code']){
            $v = $res['result'];
            $v = array_merge($v, [
                'nickname' => $v['nick'],
                'username' => $v['wxNum'],
                'headimgurl' => ''
            ]);
            $res['data'] = $v;
        }
        return  $res;
    }

    /**
     * 获取机器人列表
     * req:
     * {
        type:X0000
        data:{}
     * }
     *
     * resp:{
        code：integer
        msg:string
        result:[
            {startTimeStamp,startTime,runTime,recv:integer(接收消息数),send:integer(发送消息数),
                wxNum：微信号， nick:昵称，wxid:wxid,pid:进程PID,port:端口
            }
        ]
     * }
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getRobotList($params = []){
        $res = $this->doRequest($params, self::API_GET_ROBOT_LIST);
        if($res['code']){
            $data = [];
            foreach ($res['result'] as $v){
                array_push($data, [
                    'nickname' => $v['nick'],
                    'username' => $v['wxNum'],
                    'wxid' => $v['wxid'],
                    'headimgurl' => ''
                ]);
            }
            $res['data'] = $data;
        }
        return  $res;
    }

    public function forwardMsg($params = [])
    {
        // TODO: Implement forwardMsg() method.
    }

    public function sendVideoToFriends($params = [])
    {
        // TODO: Implement sendVideoToFriends() method.
    }

    public function sendVideoMsg($params = [])
    {
        // TODO: Implement sendVideoMsg() method.
    }

    public function sendMusicLinkMsg($params = [])
    {
        // TODO: Implement sendMusicLinkMsg() method.
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
        // TODO: Implement deleteFriend() method.
    }

    public function agreeFriendVerify($params = [])
    {
        // TODO: Implement agreeFriendVerify() method.
    }

    public function sendGroupMsgAndAt($params = [])
    {
        // TODO: Implement sendGroupMsgAndAt() method.
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

    public function getGuest($content = [], $field = '')
    {
        // TODO: Implement getGuest() method.
    }



    public function forwardMsgToFriends($params = [])
    {
        // TODO: Implement forwardMsgToFriends() method.
    }

    public function sendMsgAtAll($params = [])
    {
        // TODO: Implement sendMsgAtAll() method.
    }

    public function quitGroup($params = [])
    {
        // TODO: Implement quitGroup() method.
    }

    public function setGroupName($params = [])
    {
        // TODO: Implement setGroupName() method.
    }

    public function setGroupNotice($params = [])
    {
        // TODO: Implement setGroupNotice() method.
    }

    public function setFriendRemarkName($params = [])
    {
        // TODO: Implement setFriendRemarkName() method.
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
}