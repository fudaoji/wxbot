<?php
/**
 * Created by PhpStorm.
 * Script Name: Vlw.php
 * Create: 12/20/21 11:42 PM
 * Description: vlw http://a.vlwai.cn/
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\WxBot\Driver;

use ky\WxBot\Base;

class My extends Base
{
    //2004文件消息  1/文本消息 3/图片消息 34/语音消息  42/名片消息  43/视频 47/动态表情 48/地理位置  49/分享链接  2001/红包  2002/小程序  2003/群邀请
    const MSG_TEXT = 1;
    const MSG_IMG = 3;
    const MSG_VOICE = 34;
    const MSG_CARD = 42;
    const MSG_VIDEO = 43;
    const MSG_FILE = 2004;
    const MSG_LINK = 49;

    const EVENT_LOGIN = 'Login';
    const EVENT_FRIEND_VERIFY = 'EventFrieneVerify';

    const API_GET_MOMENTS = 'GetMoments'; //
    const API_GET_FRIEND_MOMENTS = 'GetMomentsForFriend'; //
    const API_LIKE_MOMENTS = 'MomentsLike'; //
    const API_COMMENT_MOMENTS = 'MomentsComment'; //
    const API_SEND_MOMENTS_TEXT = 'SendMoments_Str'; //
    const API_SEND_MOMENTS_IMG = 'SendMoments_Img'; //
    const API_SEND_MOMENTS_VIDEO = 'SendMoments_Video'; //
    const API_SEND_MOMENTS_LINK = 'SendMoments_Like'; //
    const API_SEND_MOMENTS_XML = 'MomentsSend'; //

    const API_SEND_XML = 'SendXmlMsg'; //发送xml消息
    const API_SEND_IMG = 'SendImageMsg'; //发送图片
    const API_SEND_TEXT = 'SendTextMsg'; //发送文本
    const API_FORWARD_MSG = 'ForwardMsg'; //转发消息
    const API_SEND_VIDEO_MSG = 'SendVideoMsg'; // 发送视频消息，只支持pro版
    const API_SEND_FILE_MSG = 'SendFileMsg'; // 发送文件消息，只支持pro版
    const API_DOWNLOAD_FILE = 'DownloadFile'; //下载文件到机器人服务器本地，只支持pro版
    const API_GET_FILE_FO_BASE64 = 'GetFileFoBase64'; //获取文件 返回该文件的Base64编码
    const API_SEND_MUSIC_LINK_MSG = 'SendMusicLinkMsg'; //发送一条可播放的歌曲链接
    const API_SEND_SHARE_LINK_MSG = 'SendShareLinkMsg'; //发送普通分享链接
    const API_SEND_LINK_MSG = 'SendLinkMsg'; //发送链接消息，只支持pro版
    const API_SEND_CARD_MSG = "SendCardMsg"; //发送名片消息

    const API_AGREE_FRIEND_VERIFY = 'AgreeFriendVerify'; // 同意好友请求
    const API_DELETE_FRIEND = 'DeleteFriend'; // 删除好友，只支持pro版
    const API_GET_DETAIL_BY_WXID = 'GetDetailInfoByWxid'; // 获取某个好友详细
    const API_SEARCH_ACCOUNT = "SearchAccount"; //搜索好友，只支持pro版
    const API_MODIFY_FRIEND_REMARK = 'ModifyFriendNote'; //修改好友备注
    const API_ADD_FRIEND_BY_SEARCH = 'AddFriendBySearch';

    const API_REMOVE_GROUP_MEMBER = 'RemoveGroupMember'; //将好友移除群
    const API_INVITE_IN_GROUP_BY_LINK = 'InviteInGroupByLink'; // 通过群链接邀请好友入群
    const API_INVITE_IN_GROUP = 'InviteInGroup'; // 邀请好友入群
    const API_SEND_GROUP_MSG_AND_AT = "SendGroupMsgAndAt"; //发送群消息并艾特成员
    const API_SEND_MSG_AT_ALL = 'SendMsgAtAll'; //艾特群员
    const API_GET_GROUP_MEMBER_INFO = "GetGroupMemberDetailInfo"; //获取某个群成员信息
    const API_GET_GROUP_MEMBER = "GetGroupMember"; //获取群成员列表
    const API_SET_GROUP_NAME = 'ModifyGroupName'; //
    const API_SET_GROUP_NOTICE = 'ModifyGroupNotice'; //
    const API_QUIT_GROUP = 'QuitGroup'; // 退群
    const API_BUILDING_GROUP = 'BuildingGroup'; //建群

    const API_CLEAN_CHAT_HISTORY = 'CleanChathistory'; //清空聊天记录
    const API_GET_GROUP_LIST = 'GetGrouplist'; //获取群列表
    const API_GET_FRIEND_LIST = 'GetFriendlist'; //获取好友列表
    const API_GET_ROBOT_LIST = 'GetRobotList'; //获取机器人列表
    const API_GET_ROBOT_INFO = 'GetRobotInfo'; //获取机器人信息
    const API_GET_LOGIN_CODE = 'StartWeChat'; //
    const API_EXIT_LOGIN_CODE = 'ExitWeChatLoginWin';
    const API_EXIT = 'ExitWeChat';
    const API_GET_CONTACT = 'GetContact';
    const API_GET_SUBSCRIPTION = 'GetSubscriptionlist';

    const API_FAVORITE_MSG = 'FavoritesMsg'; //收藏消息
    const API_GET_FAVORITES = 'FavoritesGetList'; //获取收藏列表
    const API_SEND_FAVORITE_MSG = 'SendFavoritesMsg'; //发送收藏消息

    const API_ON_NOT_DISTURB = 'OnNotDisturb'; //开启消息免打扰
    const API_OFF_NOT_DISTURB = 'OffNotDisturb'; //关闭消息免打扰

    const API_ACCEPTE_TRANSFER = 'AccepteTransfer'; //同意转账
    const API_REJECT_TRANSFER = 'RejectTransfer'; //拒收转账
    const FIELD_MAP = [
        "wxid" => "wxid"
    ];


    private $token;

    public function __construct($options = [])
    {
        parent::__construct($options);
        $this->token = $this->appKey;
    }

    /**
     * @param array $options
     * @return object
     */
    public static function init($options = [])
    {
        return new static($options);
    }

    public function setToken($token = ''){
        $this->token = $token;
        return $this;
    }

    public function getToken(){
        return $this->token;
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

    private function buildPostData($params = [], $api = ''){
        return array_merge(empty($params['data']) ? $params : $params['data'], [
            'api' => $api,
            'token' => $this->token
        ]);
    }

    /**
     * req:
    robot_wxid (string)  // 机器人ID
    group_wxid (string)  // 群ID
    member_wxid (string)  // 要踢出的群成员ID，多个成员用英文逗号“,”分开
     * Author: fudaoji<fdj@kuryun.cn>
     * @param array $params
     * @return array
     */
    public function removeGroupMember($params = []){
        $params['member_wxid'] = $params['to_wxid'];
        return $this->request([
            'data' => $this->buildPostData($params, self::API_REMOVE_GROUP_MEMBER)
        ]);
    }

    /**
     * req:
    robot_wxid (string)  // 机器人ID
    to_wxid (string)  // 目标wxid
    msgid (string)  // 消息
     * Author: fudaoji<fdj@kuryun.cn>
     * @param array $params
     * @return array
     */
    public function forwardMsgToFriends($params = []){
        $data = $this->buildPostData($params, self::API_FORWARD_MSG);
        $to_wxid = is_array($data['to_wxid']) ? $data['to_wxid'] : explode(',', $data['to_wxid']);
        $res = ['code' => 1];
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $res = $this->request([
                'data' => $data
            ]);
            $this->sleep();
        }
        return $res;
    }

    /**
     * req:
        robot_wxid (string)  // 机器人ID
        to_wxid (string)  // 目标wxid
        msgid (string)  // 消息
     * Author: fudaoji<fdj@kuryun.cn>
     * @param array $params
     * @return array
     */
    public function forwardMsg($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_FORWARD_MSG)
        ]);
    }

    /**
     * req:
     * robot_wxid (string)  // 机器人ID
     * to_wxid (string)  // 好友ID
     * resp:
     * {
        "account_wxid": "", // 微信ID
        "wxid": "", // 微信ID
        "wx_num": "", // 查询对象的微信号
        "nickname": "", // 昵称
        "headimgurl": "", // 头像
        "country": "", // 国家
        "province": "", // 省份
        "city": "", // 城市
        "sex": 1, // 性别，0 无 / 1 男 / 2 女
        "scene": 3, // 添加方式  请参考常量表
        "signature": "", // 个签
        "backgroundimgurl": "" // 背景地址
    }
     * Author: fudaoji<fdj@kuryun.cn>
     * @param array $params
     * @return array
     */
    public function getDetailInfoByWxid($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_GET_DETAIL_BY_WXID)
        ]);
    }

    /**
    res:
    robot_wxid (string)  // 机器人ID
    group_wxid (string)  // 群ID
    friend_wxid (string|array)  // 好友ID
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function inviteInGroupByLink($params = []){
        $params['friendArr'] = is_string($params['friend_wxid']) ? explode(',', $params['friend_wxid']) : $params['friend_wxid'];
        return $this->request([
            'data' => $this->buildPostData($params, self::API_INVITE_IN_GROUP_BY_LINK)
        ]);
    }

    /**
    res:
    robot_wxid (string)  // 机器人ID
    group_wxid (string)  // 群ID
    friend_wxid (string)  // 好友ID
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function inviteInGroup($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_INVITE_IN_GROUP)
        ]);
    }

    /**
    res:
    robot_wxid (string)  // 机器人ID
    v1 (string)  // 收到好友验证消息中（json）的v1属性
    v2 (string)  // 收到好友验证消息中（json）的v2属性
    type (int)  // 收到好友验证消息中（json）的type属性
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function agreeFriendVerify($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_AGREE_FRIEND_VERIFY)
        ]);
    }

    /**
    res:
    robot_wxid (string)  // 机器人ID
    to_wxid (string)  // 对方的ID（支持好友/群ID/公众号ID）
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function deleteFriend($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_DELETE_FRIEND)
        ]);
    }

    /**
     *
    res:
    url (string)  // 文件下载直链，不可重定向，需要保证HEAD访问有返回
    savePath (string)  // 文件保存完整路径，目录不存在时会自动创建（如 E:\file\temp.exe）
    is_refresh (int)  // 1为下载或覆盖下载，0为本地存在该文件时不下载（以savePath判断），默认为0
    useApi (string)  // 下载完成 或 本地存在 时 快捷发送，为空则只下载  :SendFileMsg|SendVideoMsg
    robot_wxid (string)  // 机器人ID
    to_wxid (string)  // 对方的ID（支持好友/群ID/公众号ID）
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function downloadAndSend($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_DOWNLOAD_FILE)
        ]);
    }

    /**
     * req:
    robot_wxid (string)  // 机器人ID
    to_wxid (string)  // 对方的ID（支持好友/群ID/公众号ID）
    content (string)  // 朋友ID
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendCardToFriend($params = [])
    {
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_CARD_MSG)
        ]);
    }

    /**
     * 群发名片给好友
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendCardToFriends($params = [])
    {
        $data = $this->buildPostData($params, self::API_SEND_CARD_MSG);
        $to_wxid = is_array($data['to_wxid']) ? $data['to_wxid'] : explode(',', $data['to_wxid']);
        $res = ['code' => 1];
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $res = $this->request([
                'data' => $data
            ]);
            $this->sleep();
        }
        return $res;
    }

    /**
     * 批量发送视频消息给好友/群聊等
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendVideoToFriends($params = []){
        $data = $this->buildPostData($params, self::API_SEND_VIDEO_MSG);
        $to_wxid = is_array($data['to_wxid']) ? $data['to_wxid'] : explode(',', $data['to_wxid']);
        $res = ['code' => 1];
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $res = $this->request([
                'data' => $data
            ]);
            $this->sleep();
        }
        return $res;
    }

    /**
    res:
    robot_wxid (string)  // 机器人ID
    to_wxid (string)  // 对方的ID（支持好友/群ID/公众号ID）
    path (string)  // 机器人本地文件的绝对路径
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendVideoMsg($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_VIDEO_MSG)
        ]);
    }

    /**
     * 批量发送文件消息给好友/群聊等
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendFileToFriends($params = []){
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        $res = ['code' => 1];
        foreach($to_wxid as $id){
            $params['to_wxid'] = $id;
            $res = $this->sendFileMsg($params);
            $this->sleep();
        }
        return $res;
    }

    /**
    res:
        robot_wxid (string)  // 机器人ID
        to_wxid (string)  // 对方的ID（支持好友/群ID/公众号ID）
        path (string)  // 机器人本地文件的绝对路径或图片url
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendFileMsg($params = []){
        /*
         * 接口失效
         * return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_FILE_MSG)
        ]);*/
        $params['useApi'] = 'SendFileMsg';
        $params['url'] = $params['path'];
        $base_path = trim($params['file_storage_path'] ?? "C:\Users\Administrator\Documents", "\\") . "\WeChat Files\\".$params['robot_wxid']."\FileStorage\File\\";
        $params['savePath'] = $base_path.md5($params['path']).'-'.basename($params['path']);
        return $this->request([
            'data' => $this->buildPostData($params, self::API_DOWNLOAD_FILE)
        ]);
    }

    /**
     *
    res:
    robot_wxid (string)  // 机器人ID
    to_wxid (string)  // 对方的ID（支持好友/群ID/公众号ID）
    title (string)  // 标题
    desc (string)  // 内容
    url (string)  // 链接地址
    dataurl (string)  // mp3地址
    thumburl (string)  // http图片地址
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendMusicLinkMsg($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_MUSIC_LINK_MSG)
        ]);
    }

    /**
     * 批量发送链接消息给好友/群聊等
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendShareLinkToFriends($params = []){
        $data = $this->buildPostData($params, self::API_SEND_SHARE_LINK_MSG);
        $to_wxid = is_array($data['to_wxid']) ? $data['to_wxid'] : explode(',', $data['to_wxid']);
        $res = ['code' => 1];
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $res = $this->request([
                'data' => $data
            ]);
            $this->sleep();
        }
        return $res;
    }

    /**
     *
    robot_wxid (string)  // 机器人ID
    to_wxid (string)  // 对方的ID（支持好友/群ID/公众号ID）
    title (string)  // 链接标题
    desc (string)  // 链接内容
    image_url (string)  // 图片地址
    url (string)  // 跳转地址
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendShareLinkMsg($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_SHARE_LINK_MSG)
        ]);
    }

    /**
     * req:
    robot_wxid (string)  // 机器人ID
    to_wxid (string)  // 对方的ID（支持好友/群ID/公众号ID）
    xml (string)  // xml代码
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendLinkMsg($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_LINK_MSG)
        ]);
    }

    /**
     * req:
    robot_wxid (string)  // 机器人ID
    to_wxid (string)  // 对方的ID（支持好友/群ID/公众号ID）
    content (string)  // 朋友ID
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendCardMsg($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_CARD_MSG)
        ]);
    }

    /**
     * req:
    robot_wxid (string)  // 机器人ID
    group_wxid (string)  // 群ID
    member_wxid (string)  // 要艾特的群成员ID，艾特多人用英文逗号“,”分开
    member_name (string)  // 要艾特的群成员昵称，可空 会自动读取
    msg (string)
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendGroupMsgAndAt($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_GROUP_MSG_AND_AT)
        ]);
    }

    /**
     * req:{
     * robot_wxid (string)  // 机器人ID
     * v1 (string)  // 陌生人信息中的v1（可通过搜索或取详细信息）
     * v2 (string)  // 陌生人信息中的v2（可通过搜索或取详细信息）
     * msg (string)  // 打招呼的内容
     * sance (int)  // 添加类型  请参考常量表
     * }
     * @param array $params
     * @return array|bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function addFriendBySearch($params = [])
    {
        $params['sance'] = $params['scene'];
        return $this->request([
            'data' => $this->buildPostData($params, self::API_ADD_FRIEND_BY_SEARCH)
        ]);
    }

    /**
     * 搜索好友
     * req:
        robot_wxid (string)  // 机器人ID
        content (string)  // 支持手机号、微信号等等搜索
     *resp:
     * // 成功 返回json示例
    {
        "Code": 0,
        "Result": "OK",
        "ReturnJson": {
            "from_wxid": "", //  微信ID
            "from_nickname": "", // 昵称
            "headimgurl": "", // 头像
            "v1": "", // V1
            "v2": "", // V2
            "sex": 1, // 性别，0 无 / 1 男 / 2 女
            "province": "", // 省份
            "city": "", // 城市
            "signature": "", // 签名
            "status": 0, // 状态码 -1: 未知内容 0: 搜索成功 1: 找不到相关帐号 2: 对方已隐藏账号 3: 操作频繁 4: 用户不存在 5: 用户异常
            "status_desc": "搜索成功" // 状态码描述
        }
    }
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function searchAccount($params = []){
        $res = $this->request([
            'data' => $this->buildPostData($params, self::API_SEARCH_ACCOUNT)
        ]);
        if($res['code']){
            $data = $res['ReturnJson'];
            $data = array_merge($data, [
                'wxid' => $data['from_wxid'],
                'nickname' => $data['from_nickname'],
            ]);
            $res['data'] = $data;
        }
        return  $res;
    }

    /**
     * 获取群聊成员的详细信息
     * req:
        robot_wxid (string)  // 机器人ID
        group_wxid (string)  // 群ID
        member_wxid (string)  // 群成员ID
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getGroupMemberInfo($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_GET_GROUP_MEMBER_INFO)
        ]);
    }

    /**
     * 批量发送文字消息给好友/群聊等
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendTextToFriends($params = []){
        $data = $this->buildPostData($params, self::API_SEND_TEXT);
        $to_wxid = is_array($data['to_wxid']) ? $data['to_wxid'] : explode(',', $data['to_wxid']);
        $res = ['code' => 1];
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $res = $this->request([
                'data' => $data
            ]);
            $this->sleep();
        }
        return $res;
    }

    /**
     * 批量发送图片消息给好友/群聊等
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendImgToFriends($params = []){
        $data = $this->buildPostData($params, self::API_SEND_IMG);
        $to_wxid = is_array($data['to_wxid']) ? $data['to_wxid'] : explode(',', $data['to_wxid']);
        $res = ['code' => 1];
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $res = $this->request([
                'data' => $data
            ]);
            $this->sleep();
        }
        return $res;
    }

    /**
     * 发送图片消息给好友/群聊等
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendImgToFriend($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_IMG)
        ]);
    }

    /**
     * 获取群成员
     * res:
            robot_wxid (string)  // 机器人ID
            group_wxid (string)  // 群ID
            is_refresh (int)  // 1为重刷列表再获取，0为取缓存，默认为0
     *resp:
        {
            "Code": 0,
            "Result": "OK",
            "ReturnJson": {
                "group_wxid": "", // 群ID
                "group_name": "", // 群昵称
                "count": 2, // 成员数量
                "owner_wxid": "", // 群主微信ID
                "owner_nickname": "", // 群主微信昵称
                "member_list": [{
                    "wxid": "", // 微信ID
                    "wx_num": "", // 微信号
                    "group_nickname": "", // 群内昵称
                    "nickname": "" // 微信昵称
                }...]
            }
        }
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getGroupMembers($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_GET_GROUP_MEMBER)
        ]);
    }

    /**
     * 设置好友备注名
     * res:
     *  robot_wxid (string)  // 机器人ID
        to_wxid (string)  // 好友ID
        note (string)  // 新备注
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function setFriendRemarkName($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_MODIFY_FRIEND_REMARK)
        ]);
    }

    /**
     * 拉取群组列表
     * res: robot_wxid (string)  // 机器人ID
    is_refresh (int)
     *
     * resp{
    "Code": 0,
    "Result": "OK",
    "ReturnJson": [{
    "wxid": "", // 微信ID
    "nickname": "", // 昵称
    }]
    }
     * @param array $params
     * @return array|bool
     */
    public function getGroups($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_GET_GROUP_LIST)
        ]);
    }

    /**
     * 拉取好友列表
     * res: robot_wxid (string)  // 机器人ID
            is_refresh (int)
     *
     * resp{
    "Code": 0,
    "Result": "OK",
    "ReturnJson": [{
    "wxid": "", // 微信ID
    "wx_num": "", // 微信账号
    "nickname": "", // 昵称
    "note": "" // 备注
    }]
    }
     * @param array $params
     * @return array|bool
     */
    public function getFriends($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_GET_FRIEND_LIST)
        ]);
    }

    /**
     * 发送文本消息给好友
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendTextToFriend($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_TEXT)
        ]);
    }

    /**
     * 获取机器人列表
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getRobotList($params = []){
        $res = $this->request([
            'data' => $this->buildPostData($params, self::API_GET_ROBOT_LIST)
        ]);
        if(!empty($res['code'])){
            $data = [];
            foreach ($res['ReturnJson']['data'] as $v){
                $v['nickname'] = $v['username'];
                $v['username'] = $v['wx_num'];
                $v['headimgurl'] = $v['wx_headimgurl'];
                $data[] = $v;
            }
            $res['data'] = $data;
        }
        return  $res;
    }

    /**
     * 获取当前机器人信息(已废弃)
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getCurrentUser($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_GET_ROBOT_INFO)
        ]);
    }

    /**
     * 退出扫码框
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function exitLoginCode(){
        return $this->request([
            'data' => $this->buildPostData([], self::API_EXIT_LOGIN_CODE)
        ]);
    }

    /**
     * 获取登录二维码
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getLoginCode(){
        $res = $this->request([
            'data' => $this->buildPostData([], self::API_GET_LOGIN_CODE)
        ]);
        if($res['code']){
            $res['data'] = $res['ReturnStr'];
        }
        return  $res;
    }

    /**
     * 优化结果
     * @param $res
     * @return array
     */
    public function dealRes($res){
        if(intval($res['Code']) === 0){
            $res['code'] = 1;
        }else{
            $this->errors($res['Code']);
            $res['errmsg'] = $this->errMsg;
            $res['code'] = 0;
        }
        return $res;
    }

    private  function  errors($err_no = -100){
        // 状态 0成功 -1未在白名单 -2token有误 -3api有误 -4参数有误 -97其他错误 -98调用方式有误 -99数据解析失败 -100未知错误 200——299具体含义请参考调用API的注释
        $list = [
            -1 => '未在白名单',
            -2 => 'token有误',
            -3 => 'api有误',
            -4 => '参数有误',
            -97 => '其他错误',
            -98 => '调用方式有误',
            -99 => '数据解析失败',
            -100 => '未知错误',
            200 => '',
            //... 200——299具体含义请参考调用API的注释
            299 => '',
        ];
        $this->errMsg = isset($list[$err_no]) ? $list[$err_no] : $list[-100];
    }

    public function getGuest($content = [], $field = '')
    {
        $guest = $content['guest'];
        $guest['nickname'] = isset($guest['username']) ? $guest['username'] : $guest['nickname'];
        return isset($guest[$field]) ? $guest[$field] : $guest;
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
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SET_GROUP_NAME)
        ]);
    }

    /**
     * 修改群公告
     * robot_wxid (string)  // 机器人ID
     * group_wxid (string)  // 群ID
     * Notice (string)  // 新的群公告
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function setGroupNotice($params = [])
    {
        $params['Notice'] = $params['notice'];
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SET_GROUP_NOTICE)
        ]);
    }

    /**
     * req:
     *  robot_wxid (string)  // 机器人ID
     *  group_wxid (string)  // 群ID
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function quitGroup($params = [])
    {
        return $this->request([
            'data' => $this->buildPostData($params, self::API_QUIT_GROUP)
        ]);
    }

    /**
     * req:
     *  robot_wxid (string)  // 机器人ID
     *  group_wxid (string)  // 群ID
     *  msg                     //文本内容
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendMsgAtAll($params = [])
    {
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_MSG_AT_ALL)
        ]);
    }

    public function getMemberInfo($params = [])
    {
        return $this->request([
            'data' => $this->buildPostData($params, self::API_GET_DETAIL_BY_WXID)
        ]);
    }

    /**
     * 清除聊天记录
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function cleanChatHistory($params = [])
    {
        return $this->request([
            'data' => $this->buildPostData($params, self::API_CLEAN_CHAT_HISTORY)
        ]);
    }

    /**
     * 建群
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function buildingGroup($params = [])
    {
        $params['friendArr'] = is_array($params['wxids']) ? $params['wxids'] : explode(',', $params['wxids']);
        unset($params['wxids']);
        //return $this->buildPostData($params, self::API_BUILDING_GROUP);
        return $this->request([
            'data' => $this->buildPostData($params, self::API_BUILDING_GROUP)
        ]);
    }

    /**
     * 获取朋友圈
     * Author: fudaoji<fdj@kuryun.cn>
     * @param array $params
     * @return array
     */
    public function getMoments($params = [])
    {
        $res = $this->request([
            'data' => $this->buildPostData($params, self::API_GET_MOMENTS)
        ]);
        if($res['code'] && !empty($res['ReturnJson']['pyq_list'])){
            $res['data'] = $res['ReturnJson']['pyq_list'];
            unset($res['ReturnJson']['pyq_list']);
        }else{
            $res['data'] = [];
        }
        return  $res;
    }

    /**
     * 获取好友朋友圈
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getFriendMoments($params = [])
    {
        $res = $this->request([
            'data' => $this->buildPostData($params, self::API_GET_FRIEND_MOMENTS)
        ]);
        if($res['code'] && !empty($res['ReturnJson']['pyq_list'])){
            $res['data'] = $res['ReturnJson']['pyq_list'];
            unset($res['ReturnJson']['pyq_list']);
        }else{
            $res['data'] = [];
        }
        return  $res;
    }

    /**
     * 点赞朋友圈
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function likeMoments($params = [])
    {
        return $this->request([
            'data' => $this->buildPostData($params, self::API_LIKE_MOMENTS)
        ]);
    }

    /**
     * 评论朋友圈
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function commentMoments($params = [])
    {
        $params['msg'] = $params['content'];
        return $this->request([
            'data' => $this->buildPostData($params, self::API_COMMENT_MOMENTS)
        ]);
    }

    /**
     * 发送文本朋友圈
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendMomentsText($params = [])
    {
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_MOMENTS_TEXT)
        ]);
    }

    /**
     * 发送图片朋友圈
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendMomentsImg($params = [])
    {
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_MOMENTS_IMG)
        ]);
    }

    /**
     * 发送视频朋友圈
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendMomentsVideo($params = [])
    {
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_MOMENTS_VIDEO)
        ]);
    }

    /**
     * 发送连接朋友圈
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendMomentsLink($params = [])
    {
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_MOMENTS_LINK)
        ]);
    }

    /**
     * 发送xml朋友圈
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendMomentsXml($params = [])
    {
        $params['pyq_xml'] = $params['xml'];
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_MOMENTS_XML)
        ]);
    }

    public function favoritesMsg($params = [])
    {
        return $this->request([
            'data' => $this->buildPostData($params, self::API_FAVORITE_MSG)
        ]);
    }

    public function getFavorites($params = [])
    {
        return $this->request([
            'data' => $this->buildPostData($params, self::API_GET_FAVORITES)
        ]);
    }

    public function sendFavoritesMsg($params = [])
    {
        $params['local_id'] = $params['favorite_id'];
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_FAVORITE_MSG)
        ]);
    }

    /**
     * 接收转账
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function acceptTransfer($params = [])
    {
        return $this->request([
            'data' => $this->buildPostData($params, self::API_ACCEPTE_TRANSFER)
        ]);
    }

    /**
     * 拒收转账
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function rejectTransfer($params = [])
    {
        return $this->request([
            'data' => $this->buildPostData($params, self::API_REJECT_TRANSFER)
        ]);
    }

    /**
     * 下载文件
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function downloadFile($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_GET_FILE_FO_BASE64)
        ]);
    }

    /**
     * req:
        robot_wxid (string)  // 机器人ID
        content (string)  // 要开启消息免打扰的好友ID 或 群ID 或 公众号ID
     * Author: fudaoji<fdj@kuryun.cn>
     * @param array $params
     * @return array
     */
    public function onNotDisturb($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_ON_NOT_DISTURB)
        ]);
    }


    /**
     * req:
        robot_wxid (string)  // 机器人ID
        content (string)  // 要关闭消息免打扰的好友ID 或 群ID 或 公众号ID
     * Author: fudaoji<fdj@kuryun.cn>
     * @param array $params
     * @return array
     */
    public function offNotDisturb($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_OFF_NOT_DISTURB)
        ]);
    }

    /**
     * 发送xml给好友
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendXml($params = [])
    {
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_XML)
        ]);
    }

    /**
     * 批量发送xml
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendXmlToFriends($params = [])
    {
        $res = ['code' => 1];
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', $params['to_wxid']);
        foreach($to_wxid as $id){
            $params['to_wxid'] = $id;
            $res = $this->sendXml($params);
            $this->sleep();
        }
        return $res;
    }

    /**
     * 获取整个通讯录
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getSubscriptions($params = [])
    {
        return $this->request([
            'data' => $this->buildPostData($params, self::API_GET_SUBSCRIPTION)
        ]);
    }
}