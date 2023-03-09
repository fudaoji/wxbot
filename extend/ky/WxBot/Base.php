<?php
/**
 * Created by PhpStorm.
 * Script Name: Base.php
 * Create: 12/20/21 11:33 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\WxBot;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

Abstract class Base
{
    private $options = [];
    protected $errMsg = '';
    protected $appKey = '';
    protected $baseUri = '';
    protected $stepTime = [2, 4];

    /**
     * @var Client
     */
    private $client;

    public function __construct($options = [])
    {
        $this->baseUri = $options['base_uri'];
        $this->options = array_merge($this->options, $options);
        if(!empty($this->options['app_key'])){
            $this->appKey = $this->options['app_key'];
        }
        !empty($options['step_time']) && $this->stepTime = $options['step_time'];
    }
    /*==========================朋友圈类==========================*/
    //req:{robot_wxid, pyq_id: string|optional, num: int|1>num<10}
    abstract public function getMoments($params = []); // 获取朋友圈
    //req:{robot_wxid, to_wxid, pyq_id: string|optional, num: int|1>num<10}
    abstract public function getFriendMoments($params = []); // 获取好友朋友圈
    //req:{robot_wxid, pyq_id: string}
    abstract public function likeMoments($params = []); // 点赞朋友圈
    //req:{robot_wxid, pyq_id: string, content:评论内容}
    abstract public function commentMoments($params = []); // 评论朋友圈
    //req:{robot_wxid, content:文本内容}
    abstract public function sendMomentsText($params = []); // 发送文本朋友圈
    //req:{robot_wxid, content:文字,  img:图片}
    abstract public function sendMomentsImg($params = []); // 发送图片朋友圈
    //req:{robot_wxid, content:文字, video:视频连接}
    abstract public function sendMomentsVideo($params = []); // 发送视频朋友圈
    //req:{robot_wxid, content:文字, title: 标题， img:图片, url:跳转地址}
    abstract public function sendMomentsLink($params = []); // 发送图片朋友圈
    //req:{robot_wxid, xml:xml内容}
    abstract public function sendMomentsXml($params = []); // 发送xml朋友圈

    /*==========================消息类============================*/
    //发送xml消息 req: {robot_wxid  xml:}
    abstract public function sendXml($params = []);
    //发送xml消息 req: {robot_wxid  xml:}
    abstract public function sendXmlToFriends($params = []);

    //收藏消息 req: {robot_wxid  msgid:消息id}
    abstract public function favoritesMsg($params = []);
    //获取收藏列表  req: {robot_wxid}
    abstract public function getFavorites($params = []);
    //发送收藏消息  req: {robot_wxid  to_wxid:接收人wxid   favorite_id: 收藏id}
    abstract public function sendFavoritesMsg($params = []);

    //req: {robot_wxid  to_wxid:接收人wxid  content:名片wxid}
    abstract public function sendCardToFriend($params = []);
    abstract public function sendCardToFriends($params = []);
    //{robot_wxid  to_wxid:接收人wxid  msgid: 消息id}
    abstract public function forwardMsgToFriends($params = []);
    abstract public function forwardMsg($params = []);
    //req: {robot_wxid  to_wxid  path}
    abstract public function sendImgToFriends($params = []);
    abstract public function sendImgToFriend($params = []);
    //req: robot_wxid  to_wxid  msg
    abstract public function sendTextToFriends($params = []);
    abstract public function sendTextToFriend($params = []);
    //req: {robot_wxid  to_wxid  path}
    abstract public function sendVideoToFriends($params = []);
    abstract public function sendVideoMsg($params = []);
    //req: {robot_wxid  to_wxid  path}
    abstract public function sendFileToFriends($params = []);
    abstract public function sendFileMsg($params = []);
    //{robot_wxid: ,to_wxid ,title, url, desc, dataurl, thumburl
    abstract public function sendMusicLinkMsg($params = []);
    //{robot_wxid: ,to_wxid ,title, url, desc, image_url
    abstract public function sendShareLinkToFriends($params = []);
    //{title:'', desc:'', image_url:'', url:''}
    abstract public function sendShareLinkMsg($params = []);

    abstract public function sendLinkMsg($params = []);
    //同意好友转账 {'robot_wxid','from_wxid','payer_pay_id','receiver_pay_id','paysubtype','money'}
    abstract public function acceptTransfer($params = []);
    //拒绝好友转账 {'robot_wxid','receiver_pay_id'}
    abstract public function rejectTransfer($params = []);
    /*==========================好友操作类============================*/
    //设置好友备注名 note  to_wxid
    abstract public function setFriendRemarkName($params = []);
    abstract public function deleteFriend($params = []);
    abstract public function agreeFriendVerify($params = []);
    //{robot_wxid:'',  content:'手机号、QQ或微信号'}
    abstract public function searchAccount($params = []);
    //{data:{robot_wxid: '', v1:'', msg:'', type: 1|2}}
    abstract public function addFriendBySearch($params = []);
    //{data:{robot_wxid: '', is_refresh:1|0}}
    abstract public function getFriends($params = []);
    //{robot_wxid: '', to_wxid: ''}
    abstract public function getMemberInfo($params = []);

    /*==========================群操作类============================*/
    //{robot_wxid: '', wxids: [wxid...]}
    abstract public function buildingGroup($params = []);
    abstract public function getGuest($content = [], $field = '');
    abstract public function getGroupMembers($params = []);
    //{data:{robot_wxid: '', is_refresh:1|0}}
    abstract public function getGroups($params = []);
    //at  robot_wxid group_wxid member_wxid msg
    abstract public function sendGroupMsgAndAt($params = []);
    //at  robot_wxid group_wxid msg
    abstract public function sendMsgAtAll($params = []);
    //移除群聊 robot_wxid group_wxid to_wxid
    abstract public function removeGroupMember($params = []);
    //邀请好友入群 robot_wxid, group_wxid, friend_wxid
    abstract public function inviteInGroup($params = []);
    abstract public function getGroupMemberInfo($params = []);
    //退出群聊 {robot_wxid:'', group_wxid:''}
    abstract public function quitGroup($params = []);
    //设置群名称 group_name
    abstract public function setGroupName($params = []);
    //设置群公告 notice
    abstract public function setGroupNotice($params = []);

    /*==========================账号操作类============================*/
    //清空聊天记录
    abstract public function cleanChatHistory($params = []);

    /*==========================文件操作类============================*/
    //获取文件 返回该文件的Base64编码 path
    abstract public function downloadFile($params = []);
    /**
     * 间隔
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sleep(){
        sleep(rand($this->stepTime[0], $this->stepTime[1]));
    }

    public function setAppKey($app_key = ''){
        $this->appKey = $app_key;
        return $this;
    }

    public function setBaseUri($url = ''){
        $this->baseUri = $url;
        return $this;
    }

    protected function request($params = []){
        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'timeout' => empty($this->options['timeout']) ? 0 : $this->options['timeout']
        ]);
        $url = empty($params['url']) ? '/' : $params['url'];
        $method = empty($params['method']) ? 'post' : $params['method'];
        $extra = [
            'http_errors' => false
        ];
        $headers = [
            'Content-Type'     => 'application/json;charset=UTF-8',
        ];
        if(!empty($params['headers'])){
            $headers = array_merge($headers, $params['headers']);
        }
        $extra['headers'] = $headers;
        if(!empty($params['data'])){
            if(isset($params['content_type']) && $params['content_type'] === 'form_params'){
                $extra['form_params'] = $params['data'];
                $extra['headers']['Content-Type'] = 'application/x-www-form-urlencoded';
            }else{
                switch ($method){
                    case 'get':
                        $url .= '?' . http_build_query($params['data']);
                        break;
                    default:
                        $extra['json'] = $params['data'];
                        break;
                }
            }
        }
        //var_dump($extra);
        $response = $this->client->request($method, $url, $extra);

        if($response->getStatusCode() !== 200){
            $this->setError($response->getStatusCode());
            return ['code' => 0, 'errmsg' => $this->errMsg];
        }
        //return $response->getBody()->getContents();
        return $this->dealRes(json_decode($response->getBody()->getContents(), true));
    }

    public function setError($code = 200){
        $list = [
            401 => '获取token失败',
            404 => '接口路径与请求方式错误',
            429 => '接口请求频率超过限制',
            500 => '服务端错误'
        ];
        $this->errMsg = isset($list[$code]) ? ($code . ':' .$list[$code]) : ($code.':未知错误');
    }

    public function getError(){
        return $this->errMsg;
    }

    abstract public function dealRes($res);

    /**
     * 接口暂未开放
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected function apiUnSupport(){
        return ['code' => 0, 'errmsg' => '此接口暂不支持'];
    }
}