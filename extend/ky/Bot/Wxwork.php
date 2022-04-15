<?php
/**
 * Created by PhpStorm.
 * Script Name: Vlw.php
 * Create: 12/20/21 11:42 PM
 * Description: vlw http://a.vlwai.cn/
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\Bot;


use ky\Logger;

class Wxwork extends Base
{
    //1/文本消息 3/图片消息 34/语音消息  42/名片消息  43/视频 47/动态表情 48/地理位置  49/分享链接  2001/红包  2002/小程序  2003/群邀请
    const MSG_TEXT = 1;
    const MSG_IMG = 3;
    const MSG_VOICE = 34;
    const MSG_CARD = 42;
    const MSG_VIDEO = 43;

    const EVENT_LOGIN = 0; //登录
    const EVENT_QUIT = 1; //退出

    const API_ADD_FRIEND_BY_SEARCH = 'AddFriendBySearchEnterprise'; //通过手机号去添加企业微信好友,不可频繁调用。失败返回0 成功返回1 好友返回2 企业账号离线返回3 频繁返回-1
    const API_FORWARD_MSG = 'ForwardMsg'; //转发消息
    const API_REMOVE_GROUP_MEMBER = 'RemoveGroupMemberEnterprise'; //将好友移除群
    const API_INVITE_IN_GROUP = 'InviteInGroup'; // 邀请好友入群
    const API_AGREE_FRIEND_VERIFY = 'AgreeFriendVerify'; // 同意好友请求
    const API_DELETE_FRIEND = 'DeleteFriend'; // 删除好友，只支持pro版
    const API_SEND_VIDEO_MSG = 'SendVideoMsgEnterprise'; // 发送视频消息，只支持pro版
    const API_SEND_FILE_MSG = 'SendFileMsgEnterprise'; // 发送文件消息，只支持pro版
    const API_DOWNLOAD_FILE = 'DownloadFile'; //下载文件到机器人服务器本地，只支持pro版
    const API_SEND_MUSIC_LINK_MSG = 'SendMusicLinkMsg'; //发送一条可播放的歌曲链接
    const API_SEND_SHARE_LINK_MSG = 'SendLinkMsgEnterprise'; //发送普通分享链接
    const API_SEND_LINK_MSG = 'SendLinkMsg'; //发送链接消息，只支持pro版
    const API_SEND_CARD_MSG = "SendCardMsg"; //发送名片消息，只支持pro版
    const API_SEND_GROUP_MSG_AND_AT = "SendGroupMsgAndAt"; //发送群消息并艾特成员
    const API_SEARCH_ACCOUNT = "SearchAccount"; //搜索好友，只支持pro版
    const API_GET_GROUP_MEMBER_INFO = "GetGroupMemberDetailInfo"; //获取某个群成员信息
    const API_GET_GROUP_MEMBER = "GetGroupMemberEnterprise"; //获取群成员列表
    const API_MODIFY_FRIEND_REMARK = 'ModifyFriendNote'; //修改好友备注
    const API_GET_GROUP_LIST = 'GetGrouplistEnterprise'; //获取群列表
    const API_GET_FRIEND_LIST = 'GetFriendlist'; //获取好友列表
    const API_SEND_IMG = 'SendImageMsgEnterprise'; //发送图片
    const API_SEND_TEXT = 'SendTextMsgEnterprise'; //发送文本
    const API_GET_INTERNAL_FRIEND_LIST = "GetInternalFriendlistEnterprise"; //获取外部联系人
    const API_GET_EXTERNAL_FRIEND_LIST = "GetExternalFriendlistEnterprise"; //获取外部联系人
    const API_GET_ROBOT_LIST = 'GetRobotList'; //获取机器人列表
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

    private function buildPostData($params = [], $api = ''){
        return array_merge(empty($params['data']) ? $params : $params['data'], [
            'api' => $api,
            'token' => $this->token
        ]);
    }

    /**
     * req:
        robot_wxid (string)  // 机器人ID
        content (string)  // 手机号
        msg (string)  // 打招呼的内容
     * Author: fudaoji<fdj@kuryun.cn>
     * @param array $params
     * @return bool
     */
    public function addFriendBySearch($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_ADD_FRIEND_BY_SEARCH)
        ]);
    }

    /**
     * req:
    robot_wxid (string)  // 机器人ID
    to_wxid (string)  // 目标wxid
    msgid (string)  // 消息
     * Author: fudaoji<fdj@kuryun.cn>
     * @param array $params
     * @return bool
     */
    public function forwardMsg($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_FORWARD_MSG)
        ]);
    }

    /**
     * req:
    robot_wxid (string)  // 机器人ID
    group_wxid (string)  // 群ID
    to_wxid (string)  // 要踢出的群成员ID，多个成员用英文逗号“,”分开
     * Author: fudaoji<fdj@kuryun.cn>
     * @param array $params
     * @return bool
     */
    public function removeGroupMember($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_REMOVE_GROUP_MEMBER)
        ]);
    }

    /**
    res:
    robot_wxid (string)  // 机器人ID
    group_wxid (string)  // 群ID
    friend_wxid (string)  // 好友ID
     * @param array $params
     * @return bool
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
     * @return bool
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
     * @return bool
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
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function downloadFile($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_DOWNLOAD_FILE)
        ]);
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
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $res = $this->request([
                'data' => $data
            ]);
            sleep(rand(1,3));
        }
        return $res;
    }

    /**
    res:
    robot_wxid (string)  // 机器人ID
    to_wxid (string)  // 对方的ID（支持好友/群ID/公众号ID）
    path (string)  // 机器人本地文件的绝对路径
     * @param array $params
     * @return bool
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
        $data = $this->buildPostData($params, self::API_SEND_FILE_MSG);
        $to_wxid = is_array($data['to_wxid']) ? $data['to_wxid'] : explode(',', $data['to_wxid']);
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $res = $this->request([
                'data' => $data
            ]);
            sleep(rand(1,3));
        }
        return $res;
    }

    /**
    res:
    robot_wxid (string)  // 机器人ID
    to_wxid (string)  // 对方的ID（支持好友/群ID/公众号ID）
    path (string)  // 机器人本地文件的绝对路径
     * @param array $params
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendFileMsg($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_FILE_MSG)
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
     * @return bool
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
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $res = $this->request([
                'data' => $data
            ]);
            sleep(rand(1,3));
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
     * @return bool
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
     * @return bool
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
     * @return bool
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
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendGroupMsgAndAt($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEND_GROUP_MSG_AND_AT)
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
        return $this->request([
            'data' => $this->buildPostData($params, self::API_SEARCH_ACCOUNT)
        ]);
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
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $res = $this->request([
                'data' => $data
            ]);
            sleep(rand(1,4));
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
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $this->request([
                'data' => $data
            ]);
            sleep(rand(1,4));
        }
        return ['code' => 1];
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
     *resp:
        {
            "Code": 0,
            "Result": "OK",
            "ReturnJson": {
                "data":[{
                    "avatar": "", // 头像
                    "corp_id": "", // 公司
                    "nickname": "", // 昵称  有时候会返回空 尽量取 username
                    "position": "", // 职位
                    "realname": "", // 实名认证姓名
                    "remark": "", // 备注
                    "sex": 1, // 性别，0 无 / 1 男 / 2 女
                    "user_id": "", // 用户ID
                    "username": "" // 昵称
                }]
            }
        }
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getGroupMember($params = []){
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
     * res:
        robot_wxid (string)  // 机器人ID
     *
     * resp:
     * "data": [{
            "conversation_id": "", // 群ID
            "create_time": 1624790439, // 建群时间戳
            "create_user_id": "", // 管理员ID
            "is_external": 0, // 0 表示内部群 / 1 表示外部群
            "is_manager": 1, // 0 不是群主 / 1 代表是群主
            "nickname": "", // 昵称
            "total": 4 // 人数
        }]
     * @param array $params
     * @return bool
     */
    public function getGroups($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_GET_GROUP_LIST)
        ]);
    }

    /**
     * 拉取好友列表
     * res: robot_wxid (string)  // 机器人ID
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
     * @return array
     */
    public function getFriends($params = []){
        $res = $this->getInternalFriendlist($params);
        $return = $res;
        $list = [];
        if(!empty($res['ReturnJson']['data'])){
            $internal_list = $res['ReturnJson']['data'];
            foreach ($internal_list as $k => $v){
                $v['internal'] = 1;
                array_push($list, $v);
            }
        }

        $res2 = $this->getExternalFriendlist($params);
        if(!empty($res2['ReturnJson']['data'])){
            $external_list = $res2['ReturnJson']['data'];
            foreach ($external_list as $k => $v){
                $v['internal'] = 0;
                array_push($list, $v);
            }
        }
        $return['ReturnJson']['data'] = $list;
        return $return;
    }

    /**
     * 拉取内部联系人列表
     * res:
     * robot_wxid (string)  // 机器人ID
     *
     * resp:
     * "data": [{
            "avatar": "", // 头像
            "conversation_id": "", // 会话ID
            "corp_id": "", // 公司
            "nickname": "", // 昵称  有时候会返回空 尽量取 username
            "position": "", // 职位
            "realname": "", // 实名认证姓名
            "remark": "", // 备注
            "sex": 1, // 性别，0 无 / 1 男 / 2 女
            "user_id": "", // 用户ID
            "username": "" // 昵称
    }]
     * @param array $params
     * @return array
     */
    public function getInternalFriendlist($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_GET_INTERNAL_FRIEND_LIST)
        ]);
    }

    /**
     * 拉取外部联系人列表
     * res:
     * robot_wxid (string)  // 机器人ID
     *
     * resp:
     * "data": [{
            "conversation_id": "", // 群ID
            "create_time": 1624790439, // 建群时间戳
            "create_user_id": "", // 管理员ID
            "is_external": 0, // 0 表示内部群 / 1 表示外部群
            "is_manager": 1, // 0 不是群主 / 1 代表是群主
            "nickname": "", // 昵称
            "total": 4 // 人数
        }]
     * @param array $params
     * @return bool
     */
    public function getExternalFriendlist($params = []){
        return $this->request([
            'data' => $this->buildPostData($params, self::API_GET_EXTERNAL_FRIEND_LIST)
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
        return $this->request([
            'data' => $this->buildPostData($params, self::API_GET_ROBOT_LIST)
        ]);
    }

    /**
     * 优化结果
     * @param $res
     * @return mixed
     */
    public function dealRes($res){
        if(intval($res['Code']) === 0){
            $res['code'] = 1;
        }else{
            $this->errors($res['Code']);
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
}