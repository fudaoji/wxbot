<?php
/**
 * Created by PhpStorm.
 * Script Name: Onmessage.php
 * Create: 12/25/21 9:43 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler;

use app\admin\model\Bot;
use app\admin\model\BotGroupmember;
use app\admin\model\BotMember;
use app\bot\handler\cat\EventGroupMemberAdd;
use app\bot\handler\vlw\EventFriendVerify;
use app\bot\handler\vlw\EventPrivateChat;
use app\bot\handler\vlw\EventLogin;
use app\common\controller\BaseCtl;
use app\constants\Bot as BotConst;
use ky\Helper;
use ky\WxBot\Driver\Extian;
use ky\WxBot\Driver\My;
use ky\WxBot\Driver\Mycom;
use ky\WxBot\Driver\Vlw;
use ky\WxBot\Driver\Webgo;
use ky\WxBot\Driver\Wxwork;
use ky\WxBot\Driver\Cat;
use ky\Logger;
use ky\WxBot\Driver\Qianxun;
use ky\WxBot\Driver\Xbot;

class Handler extends BaseCtl
{
    /**
     * @var Bot
     */
    protected $botM;
    /**
     * @var BotMember
     */
    protected $memberM;
    /**
     * @var BotGroupmember
     */
    protected $groupMemberM;
    protected $bot;
    /**
     * @var Vlw|Wxwork|Cat|Webgo|My|Mycom|Xbot|Extian
     */
    protected $botClient;
    protected $fromWxid = '';
    protected $fromName = '';
    protected $botWxid = '';
    protected $groupWxid = '';
    protected $groupName = '';
    protected $content;
    protected $group;
    protected $driver;
    protected $event;
    protected $ajaxData;
    protected $addonOptions;
    protected $isNewFriend = false;
    protected $beAtStr = [];

    /**
     * 入口
     * tip:
     * 1.机器人对某个好友的私聊不会有回调
     * 2.机器人在群里发的情况下，from_wxid和from_group 都为空
     * Author: fudaoji<fdj@kuryun.cn>
     * @param array $options
     * @throws \think\db\exception\DbException
     * @throws \Exception
     */
    public function serve($options = []){
        $this->addonOptions = $options;
        $this->driver = $options['driver'];
        $this->ajaxData = $options['ajax_data'];
        $this->checkEvent();

        $class = "\\app\\bot\\handler\\{$this->driver}\\" . ucfirst($this->event);
        if(! class_exists($class)){
            Logger::error("class: " . $class . " not exists!");
            exit(0);
        }

        /**
         * @var $handler EventLogin|EventFriendVerify|EventPrivateChat|EventGroupMemberAdd
         */
        $handler = new $class();
        $handler->initData($options);
        $handler->handle();

        //response
        $this->response();
    }

    /**
     * 全局参数
     * @param array $options
     * @throws \Exception Author: fudaoji<fdj@kuryun.cn>
     */
    public function initData($options = []){
        $this->botM = new Bot();
        $this->memberM = new BotMember();
        $this->groupMemberM = new BotGroupmember();

        $this->driver = $options['driver'];
        $this->ajaxData = $options['ajax_data'];

        $this->checkEvent();
        switch ($this->driver){
            case BotConst::PROTOCOL_EXTIAN:
                $this->botWxid = $this->ajaxData['myid'] ?? '';
                $this->fromWxid = empty($this->content['memid']) ? $this->content['fromid'] : $this->content['memid'];
                $this->fromName = $this->content['nickName'] ?? $this->content['memname'];
                !empty($this->content['wx_type']) && $this->content['type'] = $this->content['wx_type'];
                !empty($this->content['id']) && $this->content['msg_id'] = $this->content['id'];
                break;
            case BotConst::PROTOCOL_XBOT:
                $this->botWxid = empty($this->ajaxData['wxid']) ? (empty($this->content['wxid'])?'':$this->content['wxid']) : $this->ajaxData['wxid'];
                $this->fromWxid = empty($this->content['from_wxid']) ? '' : $this->content['from_wxid'];
                !empty($this->content['wx_type']) && $this->content['type'] = $this->content['wx_type'];
                !empty($this->content['msgid']) && $this->content['msg_id'] = $this->content['msgid'];
                break;
            case BotConst::PROTOCOL_QXUN:
                $this->botWxid = $this->ajaxData['wxid'];
                $this->fromWxid = empty($this->content['finalFromWxid']) ? (
                empty($this->content['fromWxid']) ? $this->botWxid : $this->content['fromWxid']
                ) : $this->content['finalFromWxid'];
                break;
            case BotConst::PROTOCOL_WEB:
                $this->botWxid = $this->content['robot_wxid'];
                $this->fromWxid = empty($this->content['from_wxid']) ? $this->botWxid : $this->content['from_wxid'];
                break;
            case BotConst::PROTOCOL_CAT:
                $this->botWxid = $this->content['robot_wxid'];
                $this->fromWxid = empty($this->content['final_from_wxid']) ? $this->botWxid : $this->content['final_from_wxid'];
                break;
            default:
                $this->botWxid = !empty($this->content['robot_wxid']) ? $this->content['robot_wxid'] : $this->content['Wxid'];
                $this->fromWxid = empty($this->content['from_wxid']) ? $this->botWxid : $this->content['from_wxid'];
                break;
        }

        if(! in_array($this->event, [BotConst::EVENT_LOGIN_CODE, BotConst::EVENT_CONNECTED])){
            $this->getBot($this->botWxid);
            $this->botClient = $this->botM->getRobotClient($this->bot);
        }
        $this->beAtStr = [
            '[at='.$this->botWxid.']',
            "@{$this->bot['nickname']}"
        ];

        $this->content['from_group'] = $this->groupWxid;
        $this->content['from_wxid'] = $this->fromWxid;
        $this->content['from_group_name'] = $this->groupName;
        $this->content['from_name'] = $this->fromName;
        $this->content['robot_wxid'] = $this->botWxid;
        //Logger::error($this->content);
    }

    public function checkEvent(){
        switch ($this->driver){
            case BotConst::PROTOCOL_EXTIAN:
                $this->content = $this->ajaxData['data'] ?? [];
                $map = [
                    Extian::EVENT_GROUP_MEMBER_ADD => BotConst::EVENT_GROUP_MEMBER_ADD,
                    Extian::EVENT_GROUP_MEMBER_DEC => BotConst::EVENT_GROUP_MEMBER_DEC
                ];
                $this->event = isset($map[$this->ajaxData['method']]) ? $map[$this->ajaxData['method']] : $this->ajaxData['method'];
                if($this->ajaxData['method'] == Extian::EVENT_NEW_MSG){
                    $this->event = empty($this->content['memid']) ? BotConst::EVENT_PRIVATE_CHAT : BotConst::EVENT_GROUP_CHAT;
                }
                if($this->isGroupEvent()){
                    $this->groupWxid = $this->content['fromid'];
                    $this->groupName = $this->content['nickName'];
                }
                break;
            case BotConst::PROTOCOL_XBOT:
                $this->content = $this->ajaxData['data'] ?? [];
                $map = [
                    Xbot::EVENT_LOGIN_CODE => BotConst::EVENT_LOGIN_CODE,
                    Xbot::EVENT_CONNECTED => BotConst::EVENT_CONNECTED,
                    Xbot::EVENT_LOGIN => BotConst::EVENT_LOGIN,
                    Xbot::EVENT_LOGOUT => BotConst::EVENT_LOGOUT,
                    Xbot::EVENT_GROUP_MEMBER_ADD => BotConst::EVENT_GROUP_MEMBER_ADD,
                    Xbot::EVENT_GROUP_MEMBER_DEC => BotConst::EVENT_GROUP_MEMBER_DEC
                ];
                $this->event = isset($map[$this->ajaxData['type']]) ? $map[$this->ajaxData['type']] : $this->ajaxData['type'];
                if(!empty($this->content['from_wxid'])){
                    $this->event = BotConst::EVENT_PRIVATE_CHAT;
                    if(!empty($this->content['room_wxid'])){
                        $this->event = BotConst::EVENT_GROUP_CHAT;
                    }
                }
                if($this->isGroupEvent()){
                    $this->groupWxid = $this->content['room_wxid'];
                }
                break;
            case BotConst::PROTOCOL_QXUN:
                $this->content = empty($this->ajaxData['data']['data']) ? $this->ajaxData['data'] : $this->ajaxData['data']['data'];
                !empty($this->content['msgType']) && $this->content['type'] = $this->content['msgType'];
                $map = [
                    Qianxun::EVENT_LOGIN => BotConst::EVENT_LOGIN,
                    Qianxun::EVENT_PRIVATE_CHAT => BotConst::EVENT_PRIVATE_CHAT,
                    Qianxun::EVENT_GROUP_CHAT => BotConst::EVENT_GROUP_CHAT,
                ];
                $this->event = isset($map[$this->ajaxData['event']]) ? $map[$this->ajaxData['event']] : $this->ajaxData['event'];
                if($this->isGroupEvent()){
                    $this->groupWxid = $this->content['fromWxid'];
                }
                break;
            case BotConst::PROTOCOL_WEB:
                $this->content = $this->ajaxData;
                $this->event = $this->ajaxData['event'];
                if($this->isGroupEvent()){
                    $this->groupWxid = $this->content['from_group'];
                    $this->groupName = $this->content['from_group_name'];
                }
                break;
            case BotConst::PROTOCOL_CAT:
                $this->content = $this->ajaxData;
                $map = [
                    Cat::EVENT_FRIEND_MSG => BotConst::EVENT_PRIVATE_CHAT,
                    Cat::EVENT_GROUP_MSG => BotConst::EVENT_GROUP_CHAT,
                    Cat::EVENT_LOGIN => BotConst::EVENT_LOGIN,
                    Cat::EVENT_GROUP_MEMBER_ADD => BotConst::EVENT_GROUP_MEMBER_ADD,
                    Cat::EVENT_GROUP_MEMBER_DEC => BotConst::EVENT_GROUP_MEMBER_DEC
                ];
                $this->event = isset($map[$this->ajaxData['event']]) ? $map[$this->ajaxData['event']] : $this->ajaxData['event'];
                if($this->isGroupEvent()){
                    $this->groupWxid = $this->content['from_wxid'];
                    $this->groupName = $this->content['from_name'];
                }
                break;
            default:
                $this->content = $this->ajaxData['content'];
                $map = [
                    Vlw::EVENT_FRIEND_VERIFY => BotConst::EVENT_FRIEND_VERIFY,
                    Vlw::EVENT_LOGIN => BotConst::EVENT_LOGIN,
                ];
                $this->event = isset($map[$this->ajaxData['Event']]) ? $map[$this->ajaxData['Event']] : $this->ajaxData['Event'];

                if($this->isGroupEvent()){
                    $this->groupWxid = $this->content['from_group'];
                    $this->groupName = $this->content['from_group_name'];
                }
                break;
        }
    }

    public function isGroupEvent(){
        return in_array($this->event, [
            BotConst::EVENT_GROUP_CHAT,
            BotConst::EVENT_GROUP_MEMBER_ADD,
            BotConst::EVENT_GROUP_MEMBER_DEC
        ]);
    }

    /**
     * @param string $nickname
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function getGroupMemberByNickname($nickname=''){
        $group = $this->memberM->getOneByMap(
            ['uin' => $this->bot['uin'], 'wxid' => $this->groupWxid],
            ['id']
        );
        return $this->groupMemberM->getOneByMap(['nickname' => $nickname, 'group_id' => $group['id']]);
    }

    /**
     * @param string $uin
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getBot($uin = ''){
        $map = ['uin' => $uin, 'alive' => 1];
        if($this->event == BotConst::EVENT_LOGIN){
            unset($map['alive']);
        }
        if(! $this->bot = $this->botM->getOneByMap($map)) {
            //Logger::error($this->botM->getlastsql());
            Logger::error('Bot not exists or not logged in: ' . $uin);
            exit(0);
        }
        return $this->bot;
    }

    protected function getAddonOptions(){
        $this->addonOptions['driver'] = $this->driver;
        $this->addonOptions['ajax_data'] = $this->ajaxData;
        $this->addonOptions['bot'] = $this->bot;
        $this->addonOptions['bot_client'] = $this->botClient;
        $this->addonOptions['bot_wxid'] = $this->botWxid;
        $this->addonOptions['from_wxid'] = $this->fromWxid;
        $this->addonOptions['from_name'] = $this->fromName;
        $this->addonOptions['group_wxid'] = $this->groupWxid;
        $this->addonOptions['group_name'] = $this->groupName;
        $this->addonOptions['event'] = $this->event;
        $this->addonOptions['group'] = $this->group;
        $this->addonOptions['content'] = $this->content;
        $this->addonOptions['is_new_friend'] = $this->isNewFriend;
        $this->addonOptions['be_at_str'] = $this->beAtStr;
        return $this->addonOptions;
    }

    private function response()
    {
        switch ($this->driver){
            case BotConst::PROTOCOL_QXUN:
                return Qianxun::response();
            case BotConst::PROTOCOL_EXTIAN:
                return Extian::response();
        }
    }
}