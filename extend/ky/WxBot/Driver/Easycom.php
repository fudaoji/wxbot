<?php
/**
 * Created by PhpStorm.
 * Script Name: Easycom.php
 * Create: 11/25/22 9:48 PM
 * Description: easywework
 * Link(pwd: 202466): https://easywework.apifox.cn/
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\WxBot\Driver;


use app\constants\Bot as BotConst;
use ky\Logger;
use ky\WxBot\Base;

class Easycom extends Base
{
    protected $checkStatusCode = false; //不能拦截statusCode

    const MSG_TEXT = [0, 2]; //文本消息
    const MSG_IMG = [7,14,101]; //一般图片消息
    const MSG_VIDEO = [22,23,103]; //一般视频消息
    const MSG_FILE = [20,15,102]; //一般文件消息
    const MSG_EMOTICON = [29, 104];   // 动图
    const MSG_LOCATION= [6];    // 地理位置消息
    const MSG_NEWS = [13]; //图文链接
    const MSG_CARD = [41]; //名片消息
    const MSG_RED = [26]; //红包消息
    const MSG_VOICE = [16]; //语音消息
    const MSG_APP = [78];  // 小程序消息
    const MSG_FINDER = [147]; //视频号
    const MSG_FINDER_VIDEO = [141]; //视频号短视频
    const MSG_FINDER_LIVE = [146]; //视频号直播

    const API_GET_TOKEN = '/member/login'; // 获取token

    const API_SET_NOTIFY_URL = '/client/setNotifyUrl'; //设置回调地址
    const API_CLIENT_LIST = '/client/list';
    const API_CLIENT_CREATE = '/client/createClient';
    const API_CLIENT_RESTORE = '/client/restoreClient';
    const API_CLIENT_STOP = '/client/stopClient';

    const API_MANUAL_LOGIN = '/login/manualLogin';
    const API_GET_LOGIN_CODE = '/login/getLoginQrcode';
    const API_VERIFY_LOGIN_CODE = '/login/verifyLoginQrcode';
    const API_CHECK_LOGIN = '/login/checkLoginQrCode';
    const API_CHECK_ACCOUNT_STATUS = '/login/checkLogin';

    const API_GET_ROBOT_INFO = '/user/getProfile';
    const API_EXIT = 'MT_QUIT_WECHAT_MSG'; //退出微信程序
    const API_PING = 'PING'; //退出微信程序

    const API_CDN_UPLOAD_BY_URL = '/cloud/cdnBigUploadByUrl';
    const API_SEND_IMG = '/msg/sendImage'; //发送图片
    const API_SEND_TEXT = '/msg/sendText'; //发送普通文本
    const API_FORWARD_MSG = 'MT_FORWARD_ANY_MSG'; //转发消息
    const API_SEND_VIDEO_MSG = '/msg/sendVideo'; // 发送视频消息
    const API_SEND_FILE_MSG = '/msg/sendFile'; // 发送文件消息，只支持pro版
    const API_DOWNLOAD_FILE = '/cloud/wxDownload'; //下载文件到机器人服务器本地，只支持pro版
    const API_SEND_MUSIC_LINK_MSG = 'SendMusicLinkMsg'; //发送一条可播放的歌曲链接
    const API_SEND_SHARE_LINK_MSG = '/msg/sendLink'; //发送普通分享链接
    const API_SEND_LINK_MSG = 'SendLinkMsg'; //发送链接消息，只支持pro版
    const API_SEND_CARD_MSG = "SendCardMsg"; //发送名片消息
    const API_GET_FILE_FO_BASE64 = 'GetFileFoBase64'; //获取文件 返回该文件的Base64编码
    const API_ACCEPT_TRANSFER = 'AccepteTransfer';// 同意转账
    const API_REJECT_TRANSFER = 'RejectTransfer';// 拒绝转账

    const API_AGREE_FRIEND_VERIFY = '/contact/agreeContact'; // 同意好友请求
    const API_GET_FRIEND_DETAIL_LIST = '/contact/batchGetUserinfo'; //获取联系人详情列表
    const API_GET_FRIEND_LIST = '/contact/getWxContactList'; //获取外部联系人

    const API_QUIT_GROUP = '/room/quitRoom'; // 退群
    const API_INVITE_IN_GROUP = '/room/inviteRoomMember'; //拉入群聊
    const API_REMOVE_GROUP_MEMBER = '/room/removeRoomMember'; //移除成员
    const API_GET_GROUP_MEMBER = "/room/batchGetRoomDetail"; //获取群成员列表
    const API_SET_GROUP_NAME = '/room/modifyRoomName'; //
    const API_BUILDING_GROUP = '/room/createRoom'; //建群
    const API_GET_GROUP_LIST = '/room/getRoomList'; //获取客户群列表
    const API_GET_GROUP_QR = '/room/getRoomQrCode'; // 获取群二维码
    const API_DISMISS_GROUP = '/room/dismissRoom'; //解散群
    const API_SEND_GROUP_MSG_AND_AT = '/msg/sendHyperText';

    const API_CLEAN_CHAT_HISTORY = 'MT_CLEAR_CHAT_HISTORY_MSG'; //清空聊天记录


    const EVENT_FRIEND_VERIFY = 2132; //好友请求事件
    const EVENT_FRIEND_ZOMBIE = 1998;//给对方发消息时，如果对方删除了自己，则会收到这个提示
    const EVENT_GROUP_ESTABLISH = 1006; //创建新群
    const EVENT_GROUP_MEMBER_ADD = 1002; //群人员增加
    const EVENT_GROUP_MEMBER_DEC = [1003, 1004, 1005]; //群人员减少


    /**
     * @var string
     */
    private $token;

    private $cacheApi;

    private $guid;

    public function __construct($options = [])
    {
        parent::__construct($options);
        $this->token = $this->appKey;
        $this->cacheApi = $options['cache'];
        $this->guid = $options['uuid'] ?? 0;
    }

    /**
     * @param array $options
     * @return object
     */
    public static function init($options = [])
    {
        return new static($options);
    }

    private function doRequest($api = '', $params = [], $method = 'post'){
        return $this->request([
            'url' => $api,
            'headers' => ["Authorization" => "Bearer " . $this->getToken()],
            'data' => $this->buildPostData($params),
            'method' => $method
        ]);
    }

    private function buildPostData($params = []){
        if(empty($params['guid'])){
            $params['guid'] = $this->guid;
            isset($params['uuid']) && $params['guid'] = $params['uuid'];
        }
        if(empty($params['guid'])){
            unset($params['guid']);
        }
        //dump($params);exit;
        return $params;
    }

    /**
     * 艾特某人
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendGroupMsgAndAt($params = [])
    {
        $params = [
            'content' => [
                ['subtype' => 1, 'text' => strval($params['member_wxid'])],
                ['subtype' => 0, 'text' => $params['msg']]
            ],
            'toId' => $params['group_wxid']
        ];
        return $this->doRequest(self::API_SEND_GROUP_MSG_AND_AT, $params);
    }

    /**
     * 个微文件下载
     * req:{
    "guid": "{{guid}}",
    "fileAeskey": "7811109615cf06**********8542f16372",
    "fileAuthkey": "306902010204623060020100**********f55ca0204594ba16f020465e1a9dd042436303730666431652d656266392d346633622d623264662d6634613133653631656137390201000203165380041054c4e6ddcb1035f74aa46fd4da1bcc110201020201000400",
    "fileSize" : 1463157,
    "fileType" : 2,
    "fileUrl": "sss"
    }
     *
     * resp:{
    "code": 0,
    "data": {
    "cloudUrl": "https://wochat-media-dev.wochat-media-dev/wochat/buz**********281969888.amr"
    },
    "msg": "成功"
    }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function downloadFile($params = [])
    {
        return $this->doRequest(self::API_DOWNLOAD_FILE, $params);
    }

    public function sendShareLinkToFriends($params = [])
    {
        $data = $params;
        $to_wxid = is_array($params['to_wxid']) ? $params['to_wxid'] : explode(',', trim($params['to_wxid'], ','));
        unset($data['to_wxid']);
        foreach($to_wxid as $id){
            $data['to_wxid'] = $id;
            $res = $this->sendShareLinkMsg($data);
            $this->sleep();
        }
        return $res;
    }

    /**
     * 发送图文链接
     * {
    "guid": "{{guid}}",
    "title": "我是title",
    "iconUrl": "https://wework.qpic.cn/wwp**3az/59411_H0x4p2RNQHmdDjh_1709274772/0",
    "linkUrl": "https://www.baidu.com",
    "desc": "我是desc",
    "toId": "107910821**095291"
    }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendShareLinkMsg($params = [])
    {
        $params = [
            "title" => $params['title'] ?? '',
            "iconUrl" => $params['image_url'] ?? '',
            "linkUrl" => $params['url'] ?? '',
            "desc" => $params['desc'] ?? '',
            "toId" => $params['to_wxid']
        ];
        return $this->doRequest(self::API_SEND_SHARE_LINK_MSG, $params);
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
        $filename = basename($params['path'] ?? ('/file-'.uniqid().time()));
        $detail = $params['detail'] ?? [];
        if(is_string($detail)){
            $detail = json_decode($params['detail'], true);
        }
        $params = [
            'toId' => $params['to_wxid'],
            "fileAesKey" => $detail['fileAesKey'] ?? '',
            "fileId" => $detail['fileId'] ?? '',
            "fileMd5" => $detail['fileMd5'] ?? '',
            "fileSize" => intval($detail['fileSize'] ?? 0),
            'filename' => $params['filename'] ?? $filename
        ];

        return $this->doRequest(self::API_SEND_FILE_MSG, $params);
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
        $filename = basename($params['path'] ?? ('/video-'.uniqid().time().'.mp4'));
        $detail = $params['detail'] ?? [];
        if(is_string($detail)){
            $detail = json_decode($params['detail'], true);
        }
        $params = [
            'toId' => $params['to_wxid'],
            "fileAesKey" => $detail['fileAesKey'] ?? '',
            "fileId" => $detail['fileId'] ?? '',
            "fileMd5" => $detail['fileMd5'] ?? '',
            "fileSize" => intval($detail['fileSize'] ?? 0),
            'filename' => $params['filename'] ?? $filename
        ];
        return $this->doRequest(self::API_SEND_VIDEO_MSG, $params);
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
    "guid": "{{guid}}",
    "fileAesKey": "32656637373664366637303736353264",
    "fileId": "3069020102046230600201000204954ff05702030f424102043f7a5875020465e14218042466616365623137352d346531642d303332342d313562632d65666331346662636231376202010002032c3ef004105c6ebc09c990d7ac3cae5f26b9390da50201010201000400",
    "fileMd5": "5c6ebc09c990d7ac3cae5f26b9390da5",
    "fileSize": 2899681,
    "filename": "mystone.jpg",
    "toId": "10791082**6095291"
    }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendImgToFriend($params = [])
    {
        $filename = basename($params['path'] ?? ('/img-'.uniqid().time().'.png'));
        $detail = $params['detail'] ?? [];
        if(is_string($detail)){
            $detail = json_decode($params['detail'], true);
        }
        $params = [
            'toId' => $params['to_wxid'],
            "fileAesKey" => $detail['fileAesKey'] ?? '',
            "fileId" => $detail['fileId'] ?? '',
            "fileMd5" => $detail['fileMd5'] ?? '',
            "fileSize" => intval($detail['fileSize'] ?? 0),
            'filename' => $params['filename'] ?? $filename
        ];
        return $this->doRequest(self::API_SEND_IMG, $params);
    }

    /**
     * 文件上传-URL
     * req:{
            guid:'',
     *      filename:'',
     *      fileUrl:'',
     *      fileType:'',  //1: jpg图片, 4: mp4视频, 5: 文件(也包括语音amr文件)
     * }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function uploadCdn($params = []){
        $params['fileUrl']= $params['url'];
        $params['fileType'] = $params['file_type'];
        if(empty($params['filename'])){
            $params['filename'] = basename($params['fileUrl']);
        }
        return $this->doRequest(self::API_CDN_UPLOAD_BY_URL, $params);
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
    "guid": "{{guid}}",
    "content": "hahah-stone",
    "toId": "1688852**9651660"
    }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendTextToFriend($params = [])
    {
        $params = [
            'content' => $params['msg'],
            'toId' => $params['to_wxid']
        ];
        return $this->doRequest(self::API_SEND_TEXT, $params);
    }

    /**
     * 解散群
     * req:{
        "guid": "{{guid}}",
        "roomId": "10896**********1"
        }
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @return array
     */
    public function dismissGroup($params = [])
    {
        $params = [
            'roomId' => $params['group_wxid'],
        ];
        return $this->doRequest(self::API_DISMISS_GROUP, $params);
    }


    /**
     * 获取群二维码
     * req:{
    "guid": "{{guid}}",
    "roomId": "10896**********1"
    }
     * resp:{
        "code": 0,
        "data": {
            "qrcodeData": "L8I/kWb5+fnxgeCTfL78fHJKn9/58juO39un9PW+.............QLNEvj/AWLBe2PVkQ9QAAAAAElFTkSuQmCC",
            "roomHeadimgUrl": "https://wework.qpic.cn/wwpic3az/566**********_1709637476/0"
        },
        "msg": "成功"
        }
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @return array
     */
    public function getGroupQr($params = [])
    {
        $params = [
            'roomId' => $params['group_wxid'],
        ];
        return $this->doRequest(self::API_GET_GROUP_QR, $params);
    }


    /**
     * req:{
    "guid": "{{guid}}",
    "roomId": "10896**********1"
    }
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @return array
     */
    public function quitGroup($params = [])
    {
        $params = [
            'roomId' => $params['group_wxid'],
        ];
        return $this->doRequest(self::API_QUIT_GROUP, $params);
    }

    /**
     * req:{
    "guid": "{{guid}}",
    "roomIdList": ["1079108**********"]
    }
     *
     * resp:{
    "code": 200,
    "data": {
    "roomList": [
    {
    "memberList": [
    {
    "inviterId": 16888523234234,
    "isAdmin": 0,
    "joinTime": 1708931411,
    "name": "本群昵称1",
    "userId": "168885**********1",
    "roomRemarkName": "1111"
    }
    ],
    "roomCreateTime": "1708931411",
    "roomCreateUserId": "168885**********1",
    "roomExtType": 2,
    "roomId": "10791082**********1",
    "roomName": "dGVzdDAyMjbmtYvor5XnvqQ=",
    "roomAnnouncement": "",
    "roomIsForbidChangeName": 1,
    "roomEnableInviteConfirm": 0
    }
    ]
    },
    "msg": "成功"
    }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getGroupMembers($params = [])
    {
        $params = [
            'roomIdList' => [$params['group_wxid']],
        ];
        $res = $this->doRequest(self::API_GET_GROUP_MEMBER, $params);
        if(!empty($res['code'])){
            $userIdList = [];
            foreach ($res['data']['roomList'][0]['memberList'] as $member){
                array_push($userIdList, $member['userId']);
            }
            $res1 = $this->getUserDetailList([
                'userIdList' => $userIdList
            ]);
            $res['data']['memberList'] = $res1['data']['contactList'];
            //unset($res['data']['roomList'][0]['memberList']);
        }else{
            $res['data']['memberList'] = [];
        }
        return $res;
    }

    /**
     * req:{
        "guid": "{{guid}}",
        "userIdList": ["788130**********"]
        }
     *
     * resp:{
        "code": 0,
        "data": {
            "contactList": [
                {
                    "acctid": "ston**********s",
                    "alias": "6ZKx6**********Kx",
                    "avatarUrl": "",
                    "corpId": "197032**********",
                    "gender": 2,
                    "groupId": "225180**********",
                    "internationCode": "86",
                    "mobile": "1760**********",
                    "nickname": "5byg**********",
                    "realName": "5byg**********",
                    "userId": "16888**********"
                }
            ]
        },
        "msg": "成功"
        }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getUserDetailList($params = []){
        $userIdList = $params['userIdList'];
        if(!is_array($userIdList)){
            $userIdList = [$userIdList];
        }
        $params = [
            'userIdList' => $userIdList,
        ];
        $res = $this->doRequest(self::API_GET_FRIEND_DETAIL_LIST, $params);
        return $res;
    }

    /**
     * 同意好友申请
     * req:{
    "guid": "{{guid}}",
    "userId": "168885**********",
    "corpId": "1970325032**********"
    }
     *
     * resp:{
    "data": {},
    "code": 0,
    "msg": "string"
    }
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function agreeFriendVerify($params = [])
    {
        $params = [
            'userId' => $params['wxid'],
            'corpId' => $params['corpId'] ?? ''
        ];
        return $this->doRequest(self::API_AGREE_FRIEND_VERIFY, $params);
    }

    /**
     * 邀请入群
     * req:{
    "guid": "{{guid}}",
    "roomId": "107703**********",
    "isOuterRoom": 1, //0 内部群  1外部群
    "memberList": ["16**********2"]
    }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function inviteInGroup($params = [])
    {
        $params = [
            'roomId' => $params['group_wxid'],
            'isOuterRoom' => $params['isOuterRoom'] ?? 1,
            'memberList' => [$params['to_wxid']]
        ];
        return $this->doRequest(self::API_INVITE_IN_GROUP, $params);
    }

    /**
     * 移除成员
     * req:{
        "guid": "{{guid}}",
        "roomId": "107703**********",
        "isOuterRoom": 0,
        "memberList": ["16**********2"]
        }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function removeGroupMember($params = [])
    {
        $params = [
            'roomId' => $params['group_wxid'],
            'isOuterRoom' => $params['isOuterRoom'] ?? 1,
            'memberList' => [$params['to_wxid']]
        ];
        return $this->doRequest(self::API_REMOVE_GROUP_MEMBER, $params);
    }

    /**
     * req:{
     * "guid": "xxx",
     * "roomId": "xxxxxxx",
     * "name": "新群名"
     * }
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @return array
     */
    public function setGroupName($params = [])
    {
        $params = [
            'roomId' => $params['group_wxid'],
            'name' => $params['group_name']
        ];
        return $this->doRequest(self::API_SET_GROUP_NAME, $params);
    }

    /**
     * 建群
     * req:{
            "guid": "{{guid}}",
            "isOuterRoom": 1,  //1外部 0企业群
            "memberList": ["168885**********", "16888501**********", "7881**********4"]
        }
     *
     * resp:{
        "code": 0,
        "data": {
            "memberList": [
                "788130**********",
                "16888**********91",
                "1688850**********1"
            ],
            "roomCreatetime": 1709637022,
            "roomCreatorId": "1688852**********1",
            "roomId": "108854**********6"
        },
        "msg": "成功"
        }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function buildingGroup($params = [])
    {
        $memberList = is_string($params['wxids']) ? explode(',', $params['wxids']) : $params['wxids'];
        $isOuterRoom = empty($params['isOuterRoom']) ? 1 : 0;
        $params = [
            'isOuterRoom' => $isOuterRoom,
            'memberList' => $memberList
        ];
        //dump($params);exit;
        return $this->doRequest(self::API_BUILDING_GROUP, $params);
    }

    /**
     * 群列表
     *req:{
    "guid": "{{guid}}",
    "nextStartIndex": 0
    }
     *
     * resp: {
    "code": 200,
    "data": {
    "hasMore": 0,
    "nextStartIndex": -1,
    "roomCount": 1,
    "roomList": [
    {
    "roomAvatarUrl": "",
    "roomCreateTime": "1708931409",
    "roomId": "107910**********",
    "roomName": "dGVzdDAyMjbmtYvor5XnvqQ=",
    "roomOwnerId": "1688**********",
    "roomMemberCount": 5
    }
    ]
    },
    "msg": "成功"
    }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getGroups($params = [])
    {
        $params = [
            'nextStartIndex' => $params['nextStartIndex'] ?? 0,
            'limit' => $params['data']['limit'] ?? 1000
        ];
        return $this->doRequest(self::API_GET_GROUP_LIST, $params);
    }

    /**
     *
     * req:{
        "guid": "{{guid}}",
            "currentSeq": 0,
          "limit": 100,
          "bizType": 1
        }
     *
     * resp:{
        "code": 0,
        "data": {
            "currentSeq": 9221725,
            "contactCount": 1,
            "hasMore": false,
            "contactList": [{
                "acctid": "",
                "addTime": 1599479231,
                "alias": "",
                "avatarUrl": "http://wx.qlogo.cn/mmhead/x80nypPWjznPvh3CjIc9TKg**********ziaGeU/0",
                "contactType": 2057,
                "corpFullName": "",
                "corpId": "197032**********",
                "corpName": "",
                "desc": "",
                "gender": 2,
                "groupId": "0",
                "internationCode": "",
                "itemFlag": 0,
                "job": "",
                "labelList": [
                    {
                    "businessType": 0,
                    "corpOrVid": 197032533394553,
                    "labelGroupId": 1407333337796584,
                    "labelId": 14333357796586
                    }
                ],
                "unionid": "ozynFsaXJhjOSPHvxXaCWs",
                "applyReason": "",
                "addCustomerTime": 1723711412,
                "companyRemark": "foo",
                "mobileRemark": [
                    "+86-17602101010"
                ],
                "mobile": "",
                "nickname": "fasefFJDFdfdf==",
                "position": "",
                "realName": "",
                "remark": "",
                "sourceInfo": {
                    "addSource": 2,
                    "applyMode": 1,
                    "opUserId": 0,
                    "sourceRoomId": 0
                },
                "userId": "788130**********",
                "seq": 159000002,
                "createSeq": 15900099
            }]
            },
        "msg": "成功"
        }


     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getFriends($params = []){
        $params = [
            'currentSeq' => $params['currentSeq'] ?? 0,
            'limit' => $params['limit'] ?? 500,
            "bizType" => $params['bizType'] ?? 1,
            //'userIdList' => $params['userIdList'] ?? []
        ];
        return $this->doRequest(self::API_GET_FRIEND_LIST, $params);
    }

    /**
     * 设置回调地址
     * req:{
        "url": "http://127.0.0.1:8080"
        }
     *
     * resp:{
            "code": 0,
            "data": {
                "urls": [
                    "http://127.0.0.1:8081",
                    "http://127.0.0.1:8080"
                ]
            },
            "msg": "成功"
        }
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function setNotifyUrl($params = []){
        return $this->doRequest(self::API_SET_NOTIFY_URL, $params);
    }

    /**
     * 获取微信信息
     * req:{guid:''}
     *
     * resp:{
        "code": 200,
        "data": {
            "acctid": "stone-les",
            "alias": "6ZKx6ZKx6fffffZKx",
            "avatarUrl": "",
            "corpId": "197032505*****",
            "gender": 2,
            "groupId": "2251803810****",
            "internationCode": "86",
            "mobile": "17601023251",
            "nickname": "5byg*****",
            "realName": "5by*****",
            "userId": "1688852*****"
        },
        "msg": "成功"
    }
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getRobotInfo($params = []){
        return $this->doRequest(self::API_GET_ROBOT_INFO, $params);
    }

    /**
     * 免扫码快速登录
     * {
        "guid": "{{guid}}"
        }
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function reLogin($params = []){
        return $this->doRequest(self::API_MANUAL_LOGIN, $params);
    }

    /**
     * 登录账号状态
     * req:{
        "guid": "{{guid}}"
      }
     *
     * resp:{
        "code": 200,
        "data": {
            "errorCode": 0,
            "errorMsg": "U1VDQ0VTUw==",
            "userId": "1688852****",
            "lastActiveTime": 0,
            "userOnlineStatus": 2  //=-1 未登陆，需要扫码登陆 =0 未登陆，可免扫码登陆 =1 已扫码，待确认 =2 登陆成功 =4 用户取消登陆 =10 已扫码确认，待检测6位验证码
        },
        "msg": "成功"
    }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function checkAccountStatus($params = []){
        return $this->doRequest(self::API_CHECK_ACCOUNT_STATUS, $params);
    }

    /**
     * 检测登录状态
     * req:{
        "guid": "{{guid}}"
        }
     *
     * resp:{
        "code": 200,
        "data": {
            "code": 0,
            "data": {
                "avatarUrl": "",
                "corpId": "",
                "corpLogo": "",
                "loginQrcodeKey": "",
                "loginQrcodeStatus": 1,      //-1:未登陆，需要扫码登陆. 0:未登陆，可免扫码登陆 1:已扫码，待确认 2:登陆成功 4:用户取消登陆 10:已扫码确认，待检测6位验证码
                "nickname": "",
                "userId": "168885*****"
            },
            "msg": "成功"
        }

     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function checkLogin($params = []){
        return $this->doRequest(self::API_CHECK_LOGIN, $params);
    }

    /**
     * 首次登录验证码
     * {
    "guid": "{{guid}}",
     "code": "{{code}}"
    }
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function verifyLoginCode($params = []){
        return $this->doRequest(self::API_VERIFY_LOGIN_CODE, $params);
    }

    /**
     * 获取登录二维码
     * {
         "guid": "{{guid}}"
     }
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getLoginCode($params = []){
        return $this->doRequest(self::API_GET_LOGIN_CODE, $params);
    }

    /**
     * 停止实例
     * {
            "guid": "111", //必须
            "logUserId": "1688852****", //可选
        }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function clientStop($params = []){
        return $this->doRequest(self::API_CLIENT_STOP, $params);
    }

    /**
     * 创建实例
     * req:{
     *  "guid": '1111', //必须
     * "logUserId": "用户ID", //可选
        "proxyUrl": "代理",  //可选
        "deviceName": "设备名称",  //可选
        "deviceType": 0, //设备类型，可选： 1-ipad, 2-windows, 3-macOS, 4-android, 5-iOS（目前支持ipad，windows）
        "clientVersion": "4.1.22.6014"  //客户端版本号
    }
     * resp:{
        "data": {},
        "code": 0,
        "msg": "string"
    }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function clientRestore($params = []){
        return $this->doRequest(self::API_CLIENT_RESTORE, $params);
    }

    /**
     * 创建实例
     * req:{
        "proxyUrl": "代理",  //可选
        "deviceName": "设备名称",  //可选
        "deviceType": 0, //设备类型，可选： 1-ipad, 2-windows, 3-macOS, 4-android, 5-iOS（目前支持ipad，windows）
        "clientVersion": "4.1.22.6014"  //客户端版本号
    }
     * resp:{
        "data": {
            "guid": "string"
        },
        "code": 0,
        "msg": "string"
    }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function clientCreate($params = []){
        return $this->doRequest(self::API_CLIENT_CREATE, $params);
    }

    /**
     * 实例列表
     * resp:{
        "code": 200,
        "data": {
            "list": [
                {
                    "clientId": 65516,
                    "customerParam": "1708690174029888900",
                    "loginUserInfo": {
                        "userId": "0",
                        "corpId": "0",
                        "groupId": "0",
                        "uuid": "2cc69541-4e71-46e6-9389-65563e0da1c2"
                    }
                }
            ]
        },
        "msg": "成功"
    }
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function clientList(){
        return $this->doRequest(self::API_CLIENT_LIST, [], 'get');
    }

    /**
     * 获取令牌
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getToken(){
        $key = 'easycomtoken:'.$this->appKey;
        if(! $token = $this->cacheApi->get($key)){
            $res = $this->request([
                'url' => self::API_GET_TOKEN,
                'data' => [
                    'appId' => $this->appKey,
                    'appSecret' => $this->appSecret
                ]
            ]);
            if(!empty($res['code'])){
                $token = $res['data']['token'] ?? "";
                $expire = empty($res['data']['expire']) ? 86400 : strtotime($res['data']['expire']) -  time();
                $this->setToken($key, $token, $expire); //缓存cookie
            }
        }

        return $token;
    }

    /**
     * 存储令牌
     * @param $key
     * @param $value
     * @param int $expire
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function setToken($key, $value, $expire = 86400){
        $expire -= 300;
        return $this->cacheApi->set($key, $value, $expire);
    }

    function ping($params = []){
        $params['client_id']= $params['uuid'];
        return $this->doRequest(self::API_PING);
    }

    /**
     * 退出微信程序
     * @param array $params
     * @return array
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



    public function cleanChatHistory($params = [])
    {
        return $this->apiUnSupport();
    }


    /**
     * 优化结果
     * @param $res
     * @return array
     */
    public function dealRes($res){
        if(isset($res['code']) && intval($res['code']) === 0){
            $res['code'] = 1;
        }else{
            $res['errmsg'] = $res['msg'];
            $res['code'] = 0;
            $this->errMsg = $res['msg'];
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

    public function getGuest($content = [], $field = '')
    {
        // TODO: Implement getGuest() method.
    }

    public function sendMsgAtAll($params = [])
    {
        // TODO: Implement sendMsgAtAll() method.
    }


    public function getGroupMemberInfo($params = [])
    {
        // TODO: Implement getGroupMemberInfo() method.
    }

    public function setGroupNotice($params = [])
    {
        // TODO: Implement setGroupNotice() method.
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