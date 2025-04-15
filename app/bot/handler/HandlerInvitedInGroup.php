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
use app\constants\Bot as BotConst;
use app\constants\Reply;
use ky\Logger;
use app\common\service\BotMember as MemberService;
use ky\WxBot\Driver\Extian;

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
        $group_data = MemberService::getInfoByWxid($this->groupWxid, $this->bot);
        if ($this->bot['protocol'] == BotConst::PROTOCOL_EXTIAN){
            foreach ($this->content['member'] as $gm) {
                invoke('\\app\\common\\event\\TaskQueue')->push([
                    'delay' => 3,
                    'params' => [
                        'do' => ['\\app\\common\\event\\GroupMember', 'insertOrUpdate'],
                        'bot' => $this->bot,
                        'group' => $group_data,
                        'member' => $gm
                    ]
                ]);
            }
        }



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
                        $this->bot, $this->botClient, $reply, $this->groupWxid
                    );
                }else{
                    $medias = json_decode($reply['medias'], true);
                    foreach ($medias as $media) {
                        $reply['media_type'] = $media['type'];
                        $reply['media_id'] = $media['id'];
                        $this->replyM->botReply(
                            $this->bot, $this->botClient, $reply, $this->groupWxid
                        );
                    }
                }
            }
        }
    }
}