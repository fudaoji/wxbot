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

    abstract public function getGuest($content = [], $field = '');
    abstract public function getGroupMembers($params = []);
    abstract public function forwardMsgToFriends($params = []);
    abstract public function forwardMsg($params = []);
    abstract public function sendImgToFriends($params = []);
    //req: robot_wxid  to_wxid  path
    abstract public function sendImgToFriend($params = []);
    abstract public function sendTextToFriends($params = []);
    //req: robot_wxid  to_wxid  msg
    abstract public function sendTextToFriend($params = []);
    abstract public function sendVideoToFriends($params = []);
    abstract public function sendVideoMsg($params = []);
    abstract public function sendFileToFriends($params = []);
    abstract public function sendFileMsg($params = []);
    abstract public function sendMusicLinkMsg($params = []);
    abstract public function sendShareLinkToFriends($params = []);
    abstract public function sendShareLinkMsg($params = []);
    abstract public function sendLinkMsg($params = []);
    abstract public function sendCardMsg($params = []);

    //设置好友备注名 note
    abstract public function setFriendRemarkName($params = []);
    abstract public function deleteFriend($params = []);
    abstract public function agreeFriendVerify($params = []);
    abstract public function searchAccount($params = []);
    abstract public function addFriendBySearch($params = []);
    abstract public function getFriends($params = []);

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
    abstract public function quitGroup($params = []);
    //设置群名称 group_name
    abstract public function setGroupName($params = []);
    //设置群公告 notice
    abstract public function setGroupNotice($params = []);

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

        $response = $this->client->request($method, $url, $extra);

        if($response->getStatusCode() !== 200){
            $this->setError($response->getStatusCode());
            return false;
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