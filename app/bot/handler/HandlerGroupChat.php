<?php
/**
 * Created by PhpStorm.
 * Script Name: HandlerPrivateChat.php
 * Create: 2022/7/18 13:57
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler;

use app\constants\Addon;
use app\constants\Bot;
use app\constants\Reply;
use app\constants\Rule;
use app\constants\Task;
use ky\Logger;

class HandlerGroupChat extends Handler
{
    protected $addonHandlerName = 'groupChatHandle';

    /**
     * 消息转播
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected function forward(){
        if($group = model('common/Forward')->getGather([
            'group_wxid' => $this->groupWxid,
            'from_wxid' => $this->fromWxid,
            'bot_wxid' => $this->botWxid
        ])) {
            //2.取出机器人负责的群并转发
            $groups = explode(',', $group['wxids']);
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
            if(empty($reply['wxids']) || strpos($reply['wxids'], $this->groupWxid) !== false){
                switch ($reply['handle_type']){
                    case Reply::HANDLE_RM:
                        $this->botClient->removeGroupMember([
                            'robot_wxid' => $this->botWxid,
                            'uuid' => $this->bot['uuid'],
                            'group_wxid' => $this->groupWxid,
                            'to_wxid' => $this->fromWxid
                        ]);
                        break;
                    case Reply::HANDLE_MSG:
                        if(empty($reply['medias'])){
                            model('reply')->botReply($this->bot, $this->botClient, $reply, $this->groupWxid, ['nickname' => $this->fromName, 'group_name' => $this->groupName]);
                        }else{
                            $medias = json_decode($reply['medias'], true);
                            foreach ($medias as $media) {
                                $reply['media_type'] = $media['type'];
                                $reply['media_id'] = $media['id'];
                                model('reply')->botReply($this->bot, $this->botClient, $reply, $this->groupWxid, ['nickname' => $this->fromName, 'group_name' => $this->groupName]);
                            }
                        }
                        break;
                }
            }
        }
    }

    /**
     * 移出群
     *
     * 1.根据昵称找到wxid
     * 2.过滤白名单
     * 3.判断是否达到群规设置的次数
     *
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function rmGroupMember(){
        $flag = ($pos1 = strpos($this->content['msg'], "@") !== false)
            && ($pos2 = strpos($this->content['msg'], "[")) !== false
            && (strpos($this->content['msg'], "[ThumbsDown]") || strpos($this->content['msg'], "[弱]")) !== false;

        if($flag){
            $rule = model('groupRule')->getOneByMap([
                'bot_id' => $this->bot['id'],
                'rule' => Rule::RM,
            ]);
            if(!empty($rule['status']) && (empty($rule['wxids']) || strpos($rule['wxids'], $this->groupWxid) !== false )){
                $nickname = trim(substr($this->content['msg'], $pos1, $pos2-1));
                if(!empty($nickname) && $gm = $this->getGroupMemberByNickname($nickname)){
                    //判断是否在白名单
                    $whiteid = model('whiteid')->getOneByMap(['bot_id' => $this->bot['id'], 'group_wxid' => $this->groupWxid]);
                    if($whiteid && strpos($whiteid['wxids'], $gm['wxid']) !== false){
                        Logger::error($this->content['from_name']."在白名单上");
                        return true;
                    }

                    $redis = get_redis();
                    $rKey = Rule::RM . $this->groupWxid. $gm['wxid'];
                    $eKey = Rule::RM . $this->groupWxid. $gm['wxid'] . $this->fromWxid;
                    $ttl = 600;
                    if($redis->get($eKey)){ //同一个人不能重复
                        return true;
                    }else{
                        $redis->setex($eKey, $ttl, 1);
                    }
                    $num = $redis->get($rKey);
                    if(!$num){
                        $num = 0;
                        $redis->setex($rKey, $ttl, 0);
                    }

                    if($num+1 >= $rule['value']){
                        $this->botClient->sendTextToFriends(
                            [
                                'uuid' => $this->bot['uuid'],
                                'robot_wxid' => $this->botWxid,
                                'to_wxid' => $this->groupWxid,
                                'msg' => "@".$nickname." 你已经被[弱]{$rule['value']}次，现将你移出群。"
                            ]
                        );
                        //踢出
                        if(! $res = $this->botClient->removeGroupMember(
                            [
                                'robot_wxid' => $this->botWxid,
                                'uuid' => $this->bot['uuid'],
                                'to_wxid' => $gm['wxid'],
                                'group_wxid' => $this->groupWxid
                            ]
                        )){
                            Logger::error($this->botClient->getError());
                        }
                    }else{
                        $redis->incr($rKey);
                    }
                }
                return true;
            }
        }
        return false;
    }

    /**
     * 关键词回复
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function keyword(){
        $keywords = model('keyword')->searchByKeyword([
            'keyword' => str_replace($this->beAtStr, "", $this->content['msg']), //过滤组AT的字符
            'bot_id' => $this->bot['id'],
            'refresh' => true
        ]);
        //Logger::error($keywords);
        $flag = false;
        foreach ($keywords as $keyword){
            if(empty($keyword['medias'])){
                if(empty($keyword['wxids'])){
                    $where = ['uin' => $this->botWxid];
                    if($keyword['user_type']==Task::USER_TYPE_FRIEND){
                        $where['type'] = Bot::FRIEND;
                    }elseif($keyword['user_type']==Task::USER_TYPE_GROUP){
                        $where['type'] = Bot::GROUP;
                    }
                    $keyword['wxids'] = implode(',', $this->memberM->getField('wxid', $where));
                }
                if(strpos($keyword['wxids'], $this->groupWxid) !== false){
                    model('reply')->botReply($this->bot, $this->botClient, $keyword, $this->groupWxid,
                        ['nickname' => $this->fromName, 'need_at' => $keyword['need_at'], 'member_wxid' => $this->fromWxid]
                    );
                    $flag = true;
                }
            }else{
                $medias = json_decode($keyword['medias'], true);
                foreach ($medias as $media) {
                    $keyword['media_type'] = $media['type'];
                    $keyword['media_id'] = $media['id'];

                    if(empty($keyword['wxids'])){
                        $where = ['uin' => $this->botWxid];
                        if($keyword['user_type']==Task::USER_TYPE_FRIEND){
                            $where['type'] = Bot::FRIEND;
                        }elseif($keyword['user_type']==Task::USER_TYPE_GROUP){
                            $where['type'] = Bot::GROUP;
                        }
                        $keyword['wxids'] = implode(',', $this->memberM->getField('wxid', $where));
                    }
                    //Logger::error('groupWxid:'.$this->groupWxid);
                    if(strpos($keyword['wxids'], $this->groupWxid) !== false){
                        //Logger::error('wxids:'.$keyword['wxids']);
                        model('reply')->botReply($this->bot, $this->botClient, $keyword, $this->groupWxid,
                            ['nickname' => $this->fromName, 'need_at' => $keyword['need_at'], 'member_wxid' => $this->fromWxid]
                        );
                        $flag = true;
                    }
                }
            }
        }

        return $flag;
    }
}