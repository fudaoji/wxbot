<?php
/**
 * Created by PhpStorm.
 * Script Name: Tpzs.php
 * Create: 2022/4/18 10:38
 * Description: 优惠券助手处理器
 * Author: fudaoji<fdj@kuryun.cn>
 */
namespace app\bot\controller;

use app\admin\model\BotGroupmember;
use app\admin\model\BotMember;
use app\common\model\zdjr\Clue;
use app\common\model\zdjr\Rule;
use ky\Logger;

class Zdjr extends Addon
{
    private $toWxid = '';

    /**
     * @var Rule
     */
    private $ruleM;
    /**
     * @var Clue
     */
    private $clueM;
    /**
     * @var array|false|\PDOStatement|string|\think\Model
     */
    private $task;
    /**
     * @var array|false|\PDOStatement|string|\think\Model
     */
    private $clue;

    public function init($options = [])
    {
        parent::init($options); // TODO: Change the autogenerated stub
        $this->ruleM = new Rule();
        $this->clueM = new Clue();
        $this->memberM = new BotMember();
        $this->groupMemberM = new BotGroupmember();
        $this->task = $this->ruleM->getOneByMap([
            'bots' => ['like', '%'.$this->bot['id'].'i%'], 'status' => 1
        ],true, true);
        return $this;
    }

    public function groupMemberAddHandle(){
        if(empty($this->task)){
            return false;
        }
        $guest = $this->botClient->getGuest($this->content);
        $nickname = $guest['nickname'];
    }

    /**
     * 机器人主动事件
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function deviceCallbackHandle(){
        $this->groupChatHandle();
    }

    /**
     * 群聊处理器
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function groupChatHandle(){
        if(empty($this->task)){
            return false;
        }
    }

    /**
     * 私聊处理器
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\db\exception\DbException
     * @throws \think\Exception
     */
    public function privateChatHandle(){
        if(! $this->isClue()){
            return false;
        }
        $this->toWxid = $this->fromWxid;
        $rules = json_decode($this->task['rules'], true);
        if($this->isNewFriend){
            //修改状态
            $this->clueM->updateOne(['id' => $this->clue['id'], 'step' => Clue::STEP_ADDED]);

            //备注名称
            if(!empty($rules['remark_name'])){
                $remark_name = str_replace('[名称]', $this->clue['title'], $rules['remark_name']);
                $res = $this->botClient->setFriendRemarkName([
                    'robot_wxid' => $this->botWxid,
                    'to_wxid' => $this->fromWxid,
                    'note' => $remark_name
                ]);
                //Logger::info("备注结果" . $remark_name);
                //Logger::info($res);
            }
            //自动拉群
            if(!empty($rules['groups'])){
                $groups = explode(',', $rules['groups']);
                foreach ($groups as $group_wxid){
                    $group = $this->memberM->getOneByMap(['wxid' => $group_wxid, 'uin' => $this->botWxid], true, true);
                    if($group){
                        if($rules['group_person_limit'] && $this->groupMemberM->total(['group_id' => $group['id']], true) >= $rules['group_person_limit']){
                            continue; //群人数已满
                        }
                        if($rules['invite_way'] == Rule::INVITE_LINK){
                            //Logger::error('链接拉群');
                            $this->botClient->inviteInGroupByLink([
                                'robot_wxid' => $this->botWxid,
                                'group_wxid' => $group_wxid,
                                'friend_wxid' => $this->fromWxid
                            ]);
                        }else{
                            //Logger::error('直接拉群');
                            $this->botClient->inviteInGroup([
                                'robot_wxid' => $this->botWxid,
                                'group_wxid' => $this->groupWxid,
                                'friend_wxid' => $this->fromWxid
                            ]);
                        }
                    }
                }
            }
        }else{
            Logger::error($this->content);
        }
    }

    /**
     * 是否是线索
     * @return bool
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    private function isClue(){
        return ($this->task
            && ($this->clue = $this->clueM->getOneByMap(['admin_id' => $this->bot['admin_id'], 'wxid' => $this->fromWxid], true, true))
        );
    }

    /**
     * 关键词回复
     * Author: fudaoji<fdj@kuryun.cn>
     */
    private function keyword(){
        if($this->content['msg'] == 'good'){
            $this->botClient->sendTextToFriends([
                'robot_wxid' => $this->botWxid,
                'to_wxid' => $this->groupWxid,
                'msg' => 'ooooo'
            ]);
        }
    }
}