<?php
/**
 * Created by PhpStorm.
 * Script Name: HandlerPrivateChat.php
 * Create: 2022/7/18 13:57
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler;

use app\common\service\BotConfig as ConfigService;
use app\constants\Bot;
use app\constants\Media;
use app\constants\Reply;
use app\constants\Task;
use ky\Logger;

class HandlerPrivateChat extends Handler
{
    protected $friend;
    protected $addonHandlerName = 'privateChatHandle';

    protected function handle(){
        if($res = ConfigService::handleCommand($this->bot['id'], $this->content['msg'], $this->fromWxid)){
            $params = [
                'type' => Media::TEXT,
                'bot' => is_array($this->bot) ? $this->bot : $this->bot->toArray(),
                'payload' => [
                    'robot_wxid' => $this->bot['uin'],
                    'uuid' => $this->bot['uuid'],
                    'to_wxid' => $this->fromWxid,
                    'msg' => $res
                ],
                'do' => ['\\app\\common\\model\\Reply', 'sendJob']
            ];

            //Logger::error($params);
            invoke('\\app\\common\\event\\TaskQueue')->push([
                'delay' => 2,
                'params' => $params
            ]);
            $this->exit();
        }
    }

    /**
     * 消息转播
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected function forward(){
        //Logger::error($this->content);
        if($group = model('common/Forward')->getGather([
            'group_wxid' => '',
            'from_wxid' => $this->fromWxid,
            'bot_wxid' => $this->botWxid
        ])) {
            //2.取出机器人负责的群并转发
            $groups = explode(',', $group['wxids']);
            //save msg log seconds later
            invoke('\\app\\common\\event\\TaskQueue')->push([
                'delay' => 3,
                'params' => [
                    'do' => ["\\app\\common\\event\\Bot", 'forwardMsg'],
                    'bot_info' => $this->bot,
                    'to_wxid' => $groups,
                    'msgid' => $this->content['msg_id'],
                    'content_type' => $this->content['type']
                ]
            ]);
            parent::$replied = true;
            /*$this->botClient->forwardMsgToFriends([
                'robot_wxid' => $this->botWxid,
                'to_wxid' => $groups,
                'msgid' => $this->content['msg_id']
            ]);*/
        }
    }

    /**
     * 关键词回复
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected function keyword(){
        $this->ignoreKeyword($this->content['msg']);

        $keywords = model('keyword')->searchByKeyword([
            'keyword' => $this->content['msg'],
            'bot_id' => $this->bot['id'],
            'refresh' => true
        ]);

        $flag = false;
        foreach ($keywords as $keyword){
            if(empty($keyword['medias'])){ //兼容旧版
                if (empty($keyword['wxids'])) {
                    if (empty($keyword['wxids'])) {
                        if ($keyword['user_type'] == Task::USER_TYPE_GROUP) {
                            continue;
                        }else{
                            $keyword['wxids'] = $this->fromWxid;
                        }
                    }
                }
                if (strpos($keyword['wxids'], $this->fromWxid) !== false) {
                    model('reply')->botReply($this->bot, $this->botClient, $keyword, $this->fromWxid, ['nickname' => $this->fromName]);
                    $flag = true;
                    parent::$replied = true;
                }
            }else{
                $medias = json_decode($keyword['medias'], true);
                foreach ($medias as $media) {
                    $keyword['media_type'] = $media['type'];
                    $keyword['media_id'] = $media['id'];

                    if (empty($keyword['wxids'])) {
                        if ($keyword['user_type'] == Task::USER_TYPE_GROUP) {
                            continue;
                        }else{
                            $keyword['wxids'] = $this->fromWxid;
                        }
                    }
                    if (strpos($keyword['wxids'], $this->fromWxid) !== false) {
                        model('reply')->botReply($this->bot, $this->botClient, $keyword, $this->fromWxid, ['nickname' => $this->fromName]);
                        $flag = true;
                        parent::$replied = true;
                    }
                }
            }
        }
        return $flag;
    }

    /**
     * 被添加好友
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected function beAdded(){
        if(empty($this->friend) || strpos($this->content['msg'],'通过了你的朋友验证请求') || '你已添加了'){
            $reply_m = new \app\common\model\Reply();
            $this->isNewFriend = true;

            $this->friend = $this->memberM->addFriend([
                'bot' => $this->bot,
                'nickname' => filter_emoji($this->fromName),
                'wxid' => $this->fromWxid
            ]);

            //回复消息
            $replys = $reply_m->getAll([
                'order' => ['sort' => 'desc'],
                'where' => [
                    'bot_id' => $this->bot['id'],
                    'event' => Reply::BEADDED,
                    'status' => 1
                ]
            ]);
            foreach ($replys as $k => $reply){
                if(empty($reply['medias'])){ //兼容旧版
                    if(empty($reply['wxids']) || strpos($reply['wxids'], $this->fromWxid) !== false){
                        $reply_m->botReply($this->bot, $this->botClient, $reply, $this->fromWxid, ['nickname' => $this->fromName]);
                    }
                }else{
                    $medias = json_decode($reply['medias'], true);
                    foreach ($medias as $media) {
                        $reply['media_type'] = $media['type'];
                        $reply['media_id'] = $media['id'];

                        if(empty($reply['wxids']) || strpos($reply['wxids'], $this->fromWxid) !== false){
                            $reply_m->botReply($this->bot, $this->botClient, $reply, $this->fromWxid, ['nickname' => $this->fromName]);
                        }
                    }
                }
            }
        }else{
            empty($this->fromName) && $this->fromName = $this->friend['nickname']; //兼容个别驱动
        }
    }

    /**
     * 针对消息事件的特殊响应
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function eventReply(){
        $replys = model('reply')->getAll([
            'order' => ['sort' => 'desc'],
            'where' => [
                'bot_id' => $this->bot['id'],
                'event' => Reply::MSG,
                'status' => 1,
                'msg_type' => $this->content['type']
            ]
        ]);
        foreach ($replys as $k => $reply){
            if(empty($reply['wxids']) || strpos($reply['wxids'], $this->fromWxid) !== false){
                switch ($reply['handle_type']){ //todo
                    case Reply::HANDLE_TRANSFER_REFUSE:

                        break;
                    case Reply::HANDLE_TRANSFER_ACCEPT:

                        break;
                    case Reply::HANDLE_DEL:
                        $this->botClient->deleteFriend(['robot_wxid' => $this->botWxid, 'to_wxid' => $this->fromWxid]);
                        break;
                    case Reply::HANDLE_MSG:
                        if(empty($reply['medias'])){
                            model('reply')->botReply($this->bot, $this->botClient, $reply, $this->fromWxid, ['nickname' => $this->fromName]);
                        }else{
                            $medias = json_decode($reply['medias'], true);
                            foreach ($medias as $media) {
                                $reply['media_type'] = $media['type'];
                                $reply['media_id'] = $media['id'];
                                model('reply')->botReply($this->bot, $this->botClient, $reply, $this->fromWxid, ['nickname' => $this->fromName]);
                            }
                        }
                        break;
                }
            }
        }
    }
}