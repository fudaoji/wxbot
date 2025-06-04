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
use app\common\controller\BaseCtl;
use app\common\service\Addon as AppService;
use app\common\service\MsgLog;
use app\common\service\Platform;
use app\constants\Addon;
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
use ky\WxBot\Driver\Xbotcom;
use ky\WxBot\Driver\Xhx;

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
    protected $isNewGroup = false;
    protected $beAtStr = [];
    protected $conversationId = '';

    protected $addonHandlerName;
    protected static $replied = false;

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
        //Logger::error($this->ajaxData);
        $this->checkEvent();
        $class = "\\app\\bot\\handler\\{$this->driver}\\" . ucfirst($this->event);
        if(! class_exists($class)){
            //Logger::error("class: " . $class . " not exists!");
            $this->exit();
        }

        /**
         * @var $handler Handler
         */
        $handler = new $class();
        $handler->initData($options);
        $handler->handle();
        //Logger::error($this->content['msg']);
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

        //Logger::error($this->ajaxData);
        switch ($this->driver){
            case BotConst::PROTOCOL_XHX:
                $this->botWxid = $this->ajaxData['wxid'];
                $this->fromWxid = $this->content['from_wxid'] ?? $this->botWxid;
                break;
            case BotConst::PROTOCOL_EXTIAN:
                $this->botWxid = $this->ajaxData['myid'] ?? '';
                if(in_array($this->event, [BotConst::EVENT_GROUP_MEMBER_ADD, BotConst::EVENT_GROUP_MEMBER_DEC])){
                    $new = $this->content['member'][0];
                    $this->fromWxid = $new['wxid'];
                    $this->fromName = $new['nickName'] ?? '';
                    $this->content['to_wxid'] = $new['wxid'] ?? '';
                }else{
                    if(empty($this->content['memid'])){
                        $this->fromWxid = $this->content['fromid'] ?? '';
                        $this->fromName = $this->content['nickName'] ?? '';
                    }else{
                        $this->fromWxid = $this->content['memid'] ?? '';
                        $this->fromName = $this->content['memname'] ?? '';
                        $this->groupName = $this->content['nickName'] ?? '';
                        $this->groupWxid = $this->content['fromid'] ?? '';
                    }
                }

                if(in_array($this->ajaxData['type'], [BotConst::MSG_LINK, BotConst::MSG_VERIFY, BotConst::MSG_FILE])){ //特殊消息类型
                    $this->content = BotConst::getContentType($this->content);
                }

                !empty($this->content['id']) && $this->content['msg_id'] = $this->content['id'];
                break;
            case BotConst::PROTOCOL_XBOT:
                $this->botWxid = empty($this->ajaxData['wxid']) ? (empty($this->content['wxid'])?'':$this->content['wxid']) : $this->ajaxData['wxid'];
                $this->fromWxid = empty($this->content['from_wxid']) ? '' : $this->content['from_wxid'];
                !empty($this->content['wx_type']) && $this->content['type'] = $this->content['wx_type'];
                !empty($this->content['msgid']) && $this->content['msg_id'] = $this->content['msgid'];
                break;
            case BotConst::PROTOCOL_XBOTCOM:
                $this->botWxid = $this->ajaxData['user_id'];
                if(!empty($this->content['sender'])){
                    $this->fromWxid = $this->content['sender'];
                    $this->fromName = $this->content['sender_name'];
                }
                $this->content['type'] = Xbotcom::contentTypes($this->content['contenttype'] ?? $this->content['content_type']);
                !empty($this->content['content']) && $this->content['msg'] = $this->content['content'];
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
                $this->fromName = $this->content['from_name'] ?? '';
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

        if (strpos($this->fromWxid, 'gh_') !== false) { //公众号忽略
            $this->exit();
        }

        empty($this->content['from_group']) && $this->content['from_group'] = $this->groupWxid;
        empty($this->content['from_wxid']) && $this->content['from_wxid'] = $this->fromWxid;
        empty($this->content['from_group_name']) && $this->content['from_group_name'] = $this->groupName;
        empty($this->content['from_name']) && $this->content['from_name'] = $this->fromName;
        empty($this->content['robot_wxid']) && $this->content['robot_wxid'] = $this->botWxid;
        //Logger::error($this->content);
        //save msg log seconds later
        invoke('\\app\\common\\event\\TaskQueue')->push([
            'delay' => 2,
            'params' => [
                'do' => ["\\app\\common\\service\\MsgLog", 'addLogTask'],
                'content' => $this->content,
                'bot' => $this->bot,
                'from_wxid' => $this->fromWxid,
                'from_nickname' => $this->fromName,
                'group_wxid' => $this->groupWxid,
                'group_nickname' => $this->groupName
            ]
        ]);
    }

    /**
     * 验证回调事件类型
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function checkEvent(){
        switch ($this->driver){
            case BotConst::PROTOCOL_EXTIAN:
                $ignore_methods = ['chatroommember', Extian::EVENT_GET_CONTACT];
                if(in_array($this->ajaxData['method'], $ignore_methods) ||
                    ($this->ajaxData['type'] == 10000 && !empty($this->ajaxData['data']['fromid']) && strpos($this->ajaxData['data']['fromid'], '@chatroom') !== false)){ //群的系统消息
                    $this->exit();
                }

                $this->content = $this->ajaxData['data'] ?? [];
                $map = [
                    Extian::EVENT_GROUP_MEMBER_ADD => BotConst::EVENT_GROUP_MEMBER_ADD,
                    Extian::EVENT_GROUP_MEMBER_DEC => BotConst::EVENT_GROUP_MEMBER_DEC
                ];
                $this->event = isset($map[$this->ajaxData['method']]) ? $map[$this->ajaxData['method']] : $this->ajaxData['method'];
                if($this->ajaxData['method'] == Extian::EVENT_NEW_MSG){
                    $this->event = empty($this->content['memid']) ? BotConst::EVENT_PRIVATE_CHAT : BotConst::EVENT_GROUP_CHAT;
                }

                if(!empty($this->content['type'])){
                    //矫正类型
                    $this->content['type'] = Extian::msgMap($this->content['type']);

                    if($this->content['type'] == 37){
                        $this->event = BotConst::EVENT_FRIEND_VERIFY;  //好友申请请求事件
                    }
                }

                if($this->ajaxData['method'] == Extian::EVENT_GROUP_MEMBER_DETAIL) {  //群成员变化
                    $this->groupWxid = $this->content['fromid'] ?? $this->content['wxid'];
                    foreach ($this->content['member'] as $gm) { //用此方法判断被邀请入群
                        if($gm['wxid'] == $this->ajaxData['myid'] && !empty($gm['invite'])){
                            $this->event = BotConst::EVENT_INVITED_IN_GROUP;
                            break;
                        }
                    }
                }
                if($this->isGroupEvent()){
                    empty($this->groupWxid) && $this->groupWxid = $this->content['fromid'] ?? $this->content['wxid'];
                    empty($this->groupName) && $this->groupName = $this->content['nickName'] ?? ($this->content['myName'] ?? '');
                }else{
                    //Logger::error($this->ajaxData);
                }
                break;
            case BotConst::PROTOCOL_XBOTCOM:
                $this->content = $this->ajaxData['data'] ?? [];
                $map = [
                    Xbotcom::EVENT_LOGIN_CODE => BotConst::EVENT_LOGIN_CODE,
                    Xbotcom::EVENT_CONNECTED => BotConst::EVENT_CONNECTED,
                    Xbotcom::EVENT_LOGIN => BotConst::EVENT_LOGIN,
                    Xbotcom::EVENT_LOGOUT => BotConst::EVENT_LOGOUT,
                    Xbotcom::EVENT_GROUP_MEMBER_ADD => BotConst::EVENT_GROUP_MEMBER_ADD,
                    Xbotcom::EVENT_GROUP_MEMBER_DEC => BotConst::EVENT_GROUP_MEMBER_DEC
                ];
                $this->event = isset($map[$this->ajaxData['type']]) ? $map[$this->ajaxData['type']] : $this->ajaxData['type'];
                if(!empty($this->content['conversation_id'])){
                    if(strpos($this->content['conversation_id'], 'S:') === 0){
                        $this->event = BotConst::EVENT_PRIVATE_CHAT;
                    }elseif (strpos($this->content['conversation_id'], 'R:') === 0) {
                        $this->event = BotConst::EVENT_GROUP_CHAT;
                    }
                }
                if($this->isGroupEvent()){
                    $this->groupWxid = str_replace("R:", "", $this->content['conversation_id']);
                }
                break;
            case BotConst::PROTOCOL_XHX:
                $this->content = empty($this->ajaxData['data']['data']) ? $this->ajaxData['data'] : $this->ajaxData['data']['data'];
                !empty($this->content['wx_type']) && $this->content['type'] = $this->content['wx_type'];
                $map = [
                    Xhx::EVENT_LOGIN => BotConst::EVENT_LOGIN,
                    Xhx::EVENT_PRIVATE_CHAT => BotConst::EVENT_PRIVATE_CHAT,
                    Xhx::EVENT_GROUP_CHAT => BotConst::EVENT_GROUP_CHAT,
                ];
                $this->event = isset($map[$this->ajaxData['event']]) ? $map[$this->ajaxData['event']] : $this->ajaxData['event'];
                if($this->isGroupEvent()){
                    $this->groupWxid = $this->content['room_wxid'];
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
            Logger::error('Bot not exists or not logged in: ' . $uin);
            $this->exit();
        }elseif (empty($this->bot['status'])){
            Logger::error('Bot is settled disabled: ' . $uin);
            $this->exit();
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
        $this->addonOptions['is_new_group'] = $this->isNewGroup;
        $this->addonOptions['be_at_str'] = $this->beAtStr;

        /*if($this->groupWxid == '34503818873@chatroom'){
            Logger::error($this->addonOptions);
        }*/

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

    protected function exit(){
        $this->response();
        exit(0);
    }

    /**
     * 插件调用
     * @param $handler
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected function addon(){
        //require_once base_path('addon') . 'common.php';

        //插件新方案执行
        $addons = AppService::listOpenApps(Platform::WECHAT);
        foreach ($addons as $k => $v){
            try {
                $class_name = "\\".config('addon.pathname')."\\".$v['name']."\\platform\\controller\\Bot";
                if(class_exists($class_name)){
                    $class = new $class_name();
                    if(method_exists($class, $this->addonHandlerName)){
                        file_exists($common = addon_path($v['name'], 'common.php')) and require_once $common;
                        //Logger::error($this->getAddonOptions());
                        $class->init($this->getAddonOptions())->{$this->addonHandlerName}();

                    }
                }
            }catch (\Exception $e){
                Logger::error($e->getMessage());
            }
        }

        //插件旧方案
        $addons = Addon::addons();
        foreach ($addons as $k => $v){
            try {
                $class_name = '\\app\\bot\\controller\\' . ucfirst($k);
                if(class_exists($class_name)){
                    $class = new $class_name();
                    if(method_exists($class, $this->addonHandlerName)){

                        $class->init($this->getAddonOptions())->{$this->addonHandlerName}();

                    }
                }
            }catch (\Exception $e){
                Logger::error($e->getMessage());
            }
        }
    }

    /**
     * 屏蔽关键词
     * @param $msg
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected function ignoreKeyword($msg = '')
    {
        empty($msg) && $msg = $this->content['msg'];
        $filter = ['发送消息过于频繁，可稍候再试。', '开启了朋友验证，你还不是他（她）朋友'];
        foreach ($filter as $f){
            if(strpos($msg, $f) !== false){
                Logger::error('不回复:'.$f);
                $this->exit();
            }
        }
    }

    /**
     * 去除消息体中的艾特部分
     * @param string $msg
     * @return string|string[]|null
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected function trimAtStr($msg = ''){
        empty($msg) && $msg = $this->content['msg'];
        $msg = trim(str_replace($this->beAtStr, "", $msg));
        return preg_replace('/[\x{2005}]/u', '', $msg); //e小天需要
    }
}