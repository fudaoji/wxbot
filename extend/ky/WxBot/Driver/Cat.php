<?php
/**
 * Created by PhpStorm.
 * Script Name: Kam.php
 * Create: 12/20/21 11:42 PM
 * Description: 可爱猫驱动 https://gitee.com/ikam/http-sdk
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\WxBot\Driver;

use ky\Logger;
use ky\WxBot\Base;

class Cat extends Base
{
    const EVENT_SYS_MSG = 'EventSysMsg'; //系统消息事件
    const EVENT_GROUP_MEMBER_DEC = 'EventGroupMemberDecrease'; //群成员减少事件（群成员退出）
    const EVENT_GROUP_MEMBER_ADD = 'EventGroupMemberAdd'; //群成员增加事件（新人进群）
    const EVENT_CONTACTS_CHANGE = 'EventContactsChange'; //朋友变动事件
    const EVENT_FRIEND_MSG = 'EventFriendMsg';
    const EVENT_GROUP_MSG = 'EventGroupMsg';
    const EVENT_RECEIVE_TRANSFER = 'EventReceivedTransfer'; //收到转账
    const EVENT_SCAN_CASH_MONEY = 'EventScanCashMoney';
    const EVENT_FRIEND_VERIFY = 'EventFriendVerify';
    const EVENT_LOGIN = 'EventLogin'; //账号登录|退出

    const API_GET_ROBOT_LIST = 'GetLoggedAccountList';
    const API_GET_FRIEND_LIST = 'GetFriendList'; //获取好友列表
    const API_GET_GROUP_LIST = 'GetGroupList'; //获取群列表

    const API_SEND_TEXT = 'SendTextMsg';
    const API_SEND_IMG = 'SendImageMsg'; //发送图片
    const API_FORWARD_MSG = 'ForwardMsg'; //转发消息
    const API_GET_GROUP_MEMBER_INFO = "GetGroupMemberInfo"; //获取某个群成员信息
    const API_SEND_VIDEO_MSG = 'SendVideoMsg'; // 发送视频消息，
    const API_SEND_FILE_MSG = 'SendFileMsg'; // 发送文件消息
    const API_SEND_MUSIC_MSG = 'SendMusicMsg'; //发送一条可播放的歌曲链接
    const API_SEND_EMOJI_MSG = 'SendEmojiMsg'; //emoji
    const API_SEND_SHARE_LINK_MSG = 'SendLinkMsg'; //发送图文链接消息，

    const API_AGREE_FRIEND_VERIFY = 'AgreeFriendVerify'; // 同意好友请求
    const API_AGREE_GROUP_INVITE = 'AgreeGroupInvite'; //同意群聊邀请
    const API_MODIFY_FRIEND_REMARK = 'EditFriendNote'; //修改好友备注
    const API_DELETE_FRIEND = 'DeleteFriend'; // 删除好友

    const API_GET_GROUP_MEMBERS = "GetGroupMemberList"; //获取群成员列表
    const API_INVITE_IN_GROUP = 'InviteInGroup'; // 邀请好友入群
    const API_REMOVE_GROUP_MEMBER = 'RemoveGroupMember'; //将好友移除群
    const API_SET_GROUP_NAME = 'EditGroupName'; //
    const API_SET_GROUP_NOTICE = 'EditGroupNotice'; //
    const API_QUIT_GROUP = 'QuitGroup'; // 退群
    const API_SEND_GROUP_MSG_AND_AT = "SendGroupMsgAndAt"; //发送群消息并艾特成员
    const API_SEND_MSG_AT_ALL = 'SendMsgAtAll'; //艾特群员

    const API_ADD_FRIEND_BY_SEARCH = 'AddFriendBySearchEnterprise'; //通过手机号去添加企业微信好友,不可频繁调用。失败返回0 成功返回1 好友返回2 企业账号离线返回3 频繁返回-1
    const API_DOWNLOAD_FILE = 'DownloadFile'; //下载文件到机器人服务器本地，只支持pro版
    const API_SEND_CARD_MSG = "SendCardMsg"; //发送名片消息，只支持pro版
    const API_SEARCH_ACCOUNT = "SearchAccount"; //搜索好友，只支持pro版

    const API_GET_MOMENTS = 'GetWechatMoments';
    const API_GET_FILE_FO_BASE64 = 'GetFileFoBase64'; //获取文件 返回该文件的Base64编码
    const API_ACCEPT_TRANSFER = 'AccepteTransfer';// 同意转账
    const API_REJECT_TRANSFER = 'RejectTransfer';// 拒绝转账

    public function __construct($options = [])
    {
        parent::__construct($options);
    }

    private function doRequest($params = [], $api = ''){
        $body = array_merge(empty($params['data']) ? $params : $params['data'], [
            "success" => true,
            "message" => "successful!",
            'event' => $api
        ]);
        return $this->request([
            'data' => $body,
            'headers' => ['Authorization' => $this->appKey]
        ]);
    }

    public function dealRes($res)
    {
        $res['ori_code'] = $res['code'];
        if(intval($res['code']) === 0){
            $res['code'] = 1;
        }else{
            $this->errors($res['code']);
            $res['errmsg'] = $this->errMsg;
            $res['code'] = 0;
        }
        return $res;
    }

    /**
     * 同意群聊邀请
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function agreeGroupInvite($params = []){
        return $this->doRequest($params, self::API_AGREE_GROUP_INVITE);
    }

    /**
     * 批量发送文字消息给好友/群聊等
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendTextToFriends($params = []){
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        $data = $params;
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $this->doRequest($data, self::API_SEND_TEXT);
            $this->sleep();
        }
        return ['code' => 1];
    }

    /**
     * 获取机器人列表
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getRobotList($params = []){
        return $this->doRequest($params, self::API_GET_ROBOT_LIST);
    }

    /**
     * 发送文本消息给好友
     * resp:{
        "success":true,//true时，http-sdk才处理，false直接丢弃
        "message":"successful!",
        "event":"SendImageMsg",//告诉它干什么，SendImageMsg是发送图片事件
        "robot_wxid":"wxid_5hxa04j4z6pg22",//用哪个机器人发
        "to_wxid":"18900134932@chatroom",//发到哪里？群/好友
        "member_wxid":"",
        "member_name":"",
        "group_wxid":"",
        "msg": "hi" {//消息内容:发送 图片、视频、文件、动态表情都是这个结构
    "url":"https:\/\/b3logfile.com\/bing\/20201024.jpg",
    "name":"20201024.jpg"//带有扩展名的文件名，建议文件md5(尽量别重名，否则会给你发错哦！http-sdk会先检测文件在不在，如果不在才去url下载，再发送，否则直接发送)
    }
        }
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendTextToFriend($params = []){
        return $this->doRequest($params, self::API_SEND_TEXT);
    }

    private  function  errors($err_no = -1){
        // 状态 0成功 -1未在白名单 -2token有误 -3api有误 -4参数有误 -97其他错误 -98调用方式有误 -99数据解析失败 -100未知错误 200——299具体含义请参考调用API的注释
        $list = [
            -1 => '权限验证失败',
        ];
        $this->errMsg = isset($list[$err_no]) ? $list[$err_no] : '未知错误';
    }

    public function forwardMsg($params = [])
    {
        $this->doRequest($params, self::API_FORWARD_MSG);
    }

    public function sendImgToFriends($params = [])
    {
        $params = $this->dealParams($params);
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        $data = $params;
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $this->doRequest($data, self::API_SEND_IMG);
            $this->sleep();
        }
        return ['code' => 1];
    }

    /**
     * 参数名转换
     * @param array $params
     * @param string $type
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    private function dealParams($params = [], $type = 'img'){
        switch ($type){
            case 'music':
                $params['msg'] = [
                    'music_name' => $params['title'],
                    'type' => $params['desc']
                ];
                unset($params['title'], $params['desc']);
                break;
            case 'share_link':
                $params['msg'] = [
                    'title' => $params['title'],
                    'text' => $params['desc'],
                    'target_url' => $params['url'],
                    'pic_url' => $params['image_url'],
                    'icon_url' => ''
                ];
                unset($params['title'], $params['desc'], $params['url'], $params['image_url']);
                break;
            default:
                $params['msg'] = [
                    'name' => md5($params['path']).basename($params['path']),
                    'url' => $params['path']
                ];
                unset($params['path']);
                break;
        }
        return $params;
    }

    /**
     *resp:{
     * "success":true,//true时，http-sdk才处理，false直接丢弃
     * "message":"successful!",
     * "event":"SendImageMsg",//告诉它干什么，SendImageMsg是发送图片事件
     * "robot_wxid":"wxid_5hxa04j4z6pg22",//用哪个机器人发
     * "to_wxid":"18900134932@chatroom",//发到哪里？群/好友
     * "member_wxid":"",
     * "member_name":"",
     * "group_wxid":"",
     * "msg": {//消息内容:发送 图片、视频、文件、动态表情都是这个结构
     * "url":"https:\/\/b3logfile.com\/bing\/20201024.jpg",
     * "name":"20201024.jpg"//带有扩展名的文件名，建议文件md5(尽量别重名，否则会给你发错哦！http-sdk会先检测文件在不在，如果不在才去url下载，再发送，否则直接发送)
     * }
     * }
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @return bool
     */
    public function sendImgToFriend($params = [])
    {
        $params = $this->dealParams($params);
        return $this->doRequest($params, self::API_SEND_IMG);
    }

    public function sendVideoToFriends($params = [])
    {
        $params = $this->dealParams($params);
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        $data = $params;
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $this->doRequest($data, self::API_SEND_VIDEO_MSG);
            $this->sleep();
        }
        return ['code' => 1];
    }

    public function sendVideoMsg($params = [])
    {
        $params = $this->dealParams($params);
        return $this->doRequest($params, self::API_SEND_VIDEO_MSG);
    }

    public function sendFileToFriends($params = [])
    {
        $params = $this->dealParams($params);
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        $data = $params;
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $this->doRequest($data, self::API_SEND_FILE_MSG);
            $this->sleep();
        }
        return ['code' => 1];
    }

    public function sendFileMsg($params = [])
    {
        $params = $this->dealParams($params);
        return $this->doRequest($params, self::API_SEND_FILE_MSG);
    }

    /**
     * 发送音乐分享 robot_wxid, to_wxid(群/好友), msg(music_name, type)
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendMusicLinkMsg($params = [])
    {
        $params = $this->dealParams($params, 'music');
        return $this->doRequest($params, self::API_SEND_MUSIC_MSG);
    }

    public function sendShareLinkToFriends($params = [])
    {
        $params = $this->dealParams($params, 'share_link');
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        $data = $params;
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $this->doRequest($data, self::API_SEND_SHARE_LINK_MSG);
            $this->sleep();
        }
        return ['code' => 1];
    }

    /**
     * 发送分享链接
     * req:{
        * robot_wxid, to_wxid(群/好友), msg(title, text, target_url, pic_url, icon_url)
     * }
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendShareLinkMsg($params = [])
    {
        $params = $this->dealParams($params, 'share_link');
        return $this->doRequest($params, self::API_SEND_SHARE_LINK_MSG);
    }

    /**
     * 文本式链接
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @return array
     */
    public function sendLinkMsg($params = [])
    {
        return $this->apiUnSupport();
    }

    public function sendCardMsg($params = [])
    {
        return $this->apiUnSupport();
    }

    public function setFriendRemarkName($params = [])
    {
        $params['msg'] = $params['note'];
        unset($params['note']);
        return $this->doRequest($params, self::API_MODIFY_FRIEND_REMARK);
    }

    public function deleteFriend($params = [])
    {
        return $this->doRequest($params, self::API_DELETE_FRIEND);
    }

    public function agreeFriendVerify($params = [])
    {
        return $this->doRequest($params, self::API_AGREE_FRIEND_VERIFY);
    }

    public function searchAccount($params = [])
    {
        return $this->apiUnSupport();
    }

    public function addFriendBySearch($params = [])
    {
        return $this->apiUnSupport();
    }

    /**
     * resp:{
            'event':'GetFriendList',
            'code' : 1,
            'msg': 'successful',
            'data':[
                {
                 'headimgurl' :'http://wx.qlogo.cn/mmhead/ver_1/hCJJXicBAUbiaZ3EIPZvvmd9DADovGwRpEgO8FibDXNHxjLTHCMGWfT4AFzkAQtRnsqFbibKia32Io8fNpOzVwfviaW6ajjCicDlaXJWJaPKHDibmYc/0',
                'nickname':'AA凯丽德电钢琴厂家-苏',
                'note' : '',
                'sex': 0,
                'wx_num' : 'suosite666',
                'wxid' :'wxid_i1xoqpqz57xr12',
                'robot_wxid' : 'wxid_a98qqf9m4bny22',
                }
         * ]
     * }
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getFriends($params = [])
    {
        return $this->doRequest($params, self::API_GET_FRIEND_LIST);
    }

    public function getGroups($params = [])
    {
        return $this->doRequest($params, self::API_GET_GROUP_LIST);
    }

    public function sendGroupMsgAndAt($params = [])
    {
        return $this->doRequest($params, self::API_SEND_GROUP_MSG_AND_AT);
    }

    /**
     * 踢出群成员
     * req: robot_wxid, group_wxid, member_wxid
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @return bool
     */
    public function removeGroupMember($params = [])
    {
        return $this->doRequest($params, self::API_REMOVE_GROUP_MEMBER);
    }

    /**
     * req: robot_wxid, group_wxid, to_wxid
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @return bool
     */
    public function inviteInGroup($params = [])
    {
        $params['to_wxid'] = $params['friend_wxid'];
        return $this->doRequest($params, self::API_INVITE_IN_GROUP);
    }

    public function getGroupMemberInfo($params = [])
    {
        return $this->doRequest($params, self::API_GET_GROUP_MEMBER_INFO);
    }

    public function getGroupMembers($params = [])
    {
        return $this->doRequest($params, self::API_GET_GROUP_MEMBERS);
    }

    public function forwardMsgToFriends($params = [])
    {
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        $data = $params;
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $this->doRequest($data, self::API_FORWARD_MSG);
            $this->sleep();
        }
        return ['code' => 1];
    }

    public function getGuest($content = [], $field = '')
    {
        $guest = $content['msg']['guest'][0];
        return isset($guest[$field]) ? $guest[$field] : $guest;
    }

    /**
     * 修改群名称 robot_wxid, group_wxid, msg
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @return bool
     */
    public function setGroupName($params = [])
    {
        $params['msg'] = $params['group_name'];
        unset($params['group_name']);
        return $this->doRequest($params, self::API_SET_GROUP_NAME);
    }

    /**
     * 修改群公告 robot_wxid, group_wxid, msg
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @return bool
     */
    public function setGroupNotice($params = [])
    {
        $params['msg'] = $params['notice'];
        unset($params['notice']);
        return $this->doRequest($params, self::API_SET_GROUP_NOTICE);
    }

    /**
     * req:
     *  robot_wxid (string)  // 机器人ID
     *  group_wxid (string)  // 群ID
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function quitGroup($params = [])
    {
        return $this->doRequest($params, self::API_QUIT_GROUP);
    }

    public function sendMsgAtAll($params = [])
    {
        return $this->apiUnSupport();
    }

    public function getMemberInfo($params = [])
    {
        return $this->apiUnSupport();
    }

    public function cleanChatHistory($params = [])
    {
        return $this->apiUnSupport();
    }

    public function sendCardToFriend($params = [])
    {
        return $this->apiUnSupport();
    }

    public function sendCardToFriends($params = [])
    {
        return $this->apiUnSupport();
    }

    public function buildingGroup($params = [])
    {
        return $this->apiUnSupport();
    }

    public function getMoments($params = [])
    {
        return $this->apiUnSupport();
        //return $this->doRequest($params, self::API_GET_MOMENTS);
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