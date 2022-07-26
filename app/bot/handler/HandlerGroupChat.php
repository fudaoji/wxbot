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
                        $this->botClient->removeGroupMember(['robot_wxid' => $this->botWxid, 'group_wxid' => $this->groupWxid, 'to_wxid' => $this->fromWxid]);
                        break;
                    default:
                        model('reply')->botReply($this->bot, $this->botClient, $reply, $this->groupWxid);
                        break;
                }
            }
        }
    }

    /**
     * 插件处理
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function addon(){
        $addons = Addon::addons();
        foreach ($addons as $k => $v){
            $class_name = '\\app\\bot\\controller\\' . ucfirst($k);
            if(class_exists($class_name)){
                $class = new $class_name();
                if(method_exists($class, 'groupChatHandle')){
                    $class->init($this->getAddonOptions())->groupChatHandle();
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
                                'robot_wxid' => $this->botWxid,
                                'to_wxid' => $this->groupWxid,
                                'msg' => "@".$nickname." 你已经被[弱]{$rule['value']}次，现将你移出群。"
                            ]
                        );
                        //踢出
                        if(! $res = $this->botClient->removeGroupMember(
                            [
                                'robot_wxid' => $this->botWxid,
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
        $keywords = model('keyword')->getAll([
            'order' => ['sort' => 'desc'],
            'where' => [
                'bot_id' => $this->bot['id'],
                'keyword' => $this->content['msg'],
                'status' => 1
            ]
        ]);

        $flag = false;
        foreach ($keywords as $keyword){
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
                    ['nickname' => $this->content['from_name'], 'need_at' => $keyword['need_at'], 'member_wxid' => $this->fromWxid]
                );
                $flag = true;
            }
        }

        return $flag;
    }
}