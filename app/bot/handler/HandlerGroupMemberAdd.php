<?php
/**
 * Created by PhpStorm.
 * Script Name: EventGroupMemberDecrease.php
 * Create: 2022/3/24 15:41
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler;

use app\constants\Addon;
use app\constants\Reply;
use ky\Logger;

class HandlerGroupMemberAdd extends Handler
{
    /**
     *
    {
        'event' => 'EventGroupMemberAdd',
        'robot_wxid' => 'wxid_a98qqf9m4bny22',
        'robot_name' => '',
        'type' => 1010,
        'from_wxid' => '20849217466@chatroom',
        'from_name' => '采品群测试',
        'final_from_wxid' => 'wxid_xokb2ezu1p6t21',
        'final_from_name' => 'DJ',
        'to_wxid' => 'wxid_a98qqf9m4bny22',
        'msgid' => '',
        'msg' :{
            'group_headimgurl' : '',
            'group_name' : '采品群测试',
            'group_wxid': '20849217466@chatroom',
            'guest': {
     *          'headimgurl' => 'http://wx.qlogo.cn/mmhead/ver_1/730J8GQYo0oPJbjxVC5PTUnNz2vq0lhfpREQwoh0BC8iaDM6pfccNGBRvWXfE3aY3jbekhyI5eEezUHv0cTQ4C7gbqCsakHniaFsKEUUuyGrg/132',
                'nickname' => 'DJ',
                'wxid' => 'wxid_xokb2ezu1p6t21',
     *      },
     *      'inviter':{
                'nickname' => 'DJ',
                'wxid' => '25984983174973249@openim',
     *      }
     *  }
    }
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function handle(){
        $this->group = $this->memberM->getOneByMap(['uin' => $this->botWxid, 'wxid' => $this->groupWxid]);
        $this->basic();
        $this->addon();
    }

    protected function basic()
    {
        $guest = $this->botClient->getGuest($this->content);
        $nickname = $guest['nickname'];
        $member_wxid = empty($guest['wxid']) ? '' : $guest['wxid'];

        $nickname && $this->groupMemberM->addMember([
            'bot_id' => $this->bot['id'],
            'wxid' => $guest['wxid'],
            'group_id' => $this->group['id'],
            'nickname' => $nickname,
            'group_nickname' => $nickname
        ]);
        //回复消息
        $replys = model('reply')->getAll([
            'order' => ['sort' => 'desc'],
            'where' => [
                'bot_id' => $this->bot['id'],
                'event' => Reply::FRIEND_IN,
                'status' => 1
            ]
        ]);
        foreach ($replys as $k => $reply){
            if(empty($reply['wxids']) || strpos($reply['wxids'], $this->groupWxid) !== false){
                model('reply')->botReply(
                    $this->bot, $this->botClient, $reply, $this->groupWxid,
                    ['nickname' => $nickname, 'need_at' => $reply['need_at'], 'member_wxid' => $member_wxid]
                );
            }
        }
    }

    protected function addon()
    {
        $addons = Addon::addons();
        foreach ($addons as $k => $v){
            $class_name = '\\app\\bot\\controller\\' . ucfirst($k);
            if(class_exists($class_name)){
                $class = new $class_name();
                if(method_exists($class, 'groupMemberAddHandle')){
                    $class->groupMemberAddHandle();
                }
            }
        }
    }
}