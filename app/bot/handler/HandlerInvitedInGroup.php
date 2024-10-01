<?php
/**
 * Created by PhpStorm.
 * Script Name: HandlerInvitedInGroup.php
 * Create: 2023/5/29 8:57
 * Description: 被邀请入群事件 //企微信不传递此事件
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler;


use app\common\model\Reply as ReplyM;
use app\constants\Reply;
use ky\Logger;

class HandlerInvitedInGroup extends Handler
{
    protected $addonHandlerName = 'invitedInGroupHandle';

    /**
     * @var ReplyM
     */
    public $replyM;

    public function handle(){
        $this->replyM = new ReplyM();
        $this->group = $this->memberM->getOneByMap(['uin' => $this->botWxid, 'wxid' => $this->groupWxid]);
        $this->basic();
        $this->addon();
    }

    protected function basic()
    {
        $guest = $this->botClient->getGuest($this->content);
        $nickname = $guest['nickname'];
        $member_wxid = empty($guest['wxid']) ? '' : $guest['wxid'];
        //Logger::error('新人信息：');
        //Logger::error($guest);
        $member_wxid && $this->groupMemberM->addMember([
            'bot_id' => $this->bot['id'],
            'wxid' => $member_wxid,
            'group_id' => $this->group['id'],
            'nickname' => $nickname,
            'group_nickname' => $nickname
        ]);

        //回复消息
        $replys = $this->replyM->getAll([
            'order' => ['sort' => 'desc'],
            'where' => [
                'bot_id' => $this->bot['id'],
                'event' => Reply::BEINVITED,
                'status' => 1
            ]
        ]);

        foreach ($replys as $k => $reply){
            if(empty($reply['wxids']) || strpos($reply['wxids'], $this->groupWxid) !== false){
                //Logger::error('进群后打招呼');
                if(empty($reply['medias'])){
                    $this->replyM->botReply(
                        $this->bot, $this->botClient, $reply, $this->groupWxid,
                        ['nickname' => $nickname, 'member_wxid' => $member_wxid]
                    );
                }else{
                    $medias = json_decode($reply['medias'], true);
                    foreach ($medias as $media) {
                        $reply['media_type'] = $media['type'];
                        $reply['media_id'] = $media['id'];
                        $this->replyM->botReply(
                            $this->bot, $this->botClient, $reply, $this->groupWxid,
                            ['nickname' => $nickname, 'member_wxid' => $member_wxid]
                        );
                    }
                }
            }
        }
    }
}