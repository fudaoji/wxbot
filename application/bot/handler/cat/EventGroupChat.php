<?php
/**
 * Created by PhpStorm.
 * Script Name: EventGroupChat.php
 * Create: 3/19/22 11:39 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\cat;

use app\admin\model\BotMember;
use app\bot\controller\Api;
use app\constants\Addon;
use app\constants\Bot;
use app\constants\Rule;
use app\constants\Task;
use ky\Logger;

class EventGroupChat extends Api
{
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->memberM = new BotMember();
    }

    /**
     * 群聊消息接收器
     */
    public function handle(){
        $this->basic();
        $this->addon();
    }

    /**
     * 基本处理
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function basic(){
        //消息转播
        $this->forward();

        //其他功能
        switch ($this->content['type']){
            case Bot::MSG_TEXT:
                if($this->keyword()) return;
                if($this->rmGroupMember()) return;
                break;
        }
    }

    /**
     * 消息转播
     * Author: fudaoji<fdj@kuryun.cn>
     */
    private function forward(){
        if($group = model('common/Forward')->getGather([
            'group_wxid' => $this->groupWxid,
            'from_wxid' => $this->fromWxid,
            'bot_wxid' => $this->botWxid
        ])) {
            //2.取出机器人负责的群并转发
            $groups = explode(',', $group['wxids']);
            $this->botClient->forwardMsgToFriends([
                'robot_wxid' => $this->botWxid,
                'to_wxid' => $groups,
                'msg' => $this->content['msgid']
            ]);
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
                    controller('bot/'.$k)->groupChatHandle();
                }
            }
        }
    }

    /**
     * 关键词回复
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
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
                model('reply')->botReply($this->bot, $this->botClient, $keyword, $this->groupWxid, ['nickname' => $this->content['from_name']]);
                $flag = true;
            }
        }

        return $flag;
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
    private function rmGroupMember(){
        $flag = ($pos1 = strpos($this->content['msg'], "@") !== false)
            && ($pos2 = strpos($this->content['msg'], "[")) !== false
            && strpos($this->content['msg'], "[弱]") !== false;

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
}