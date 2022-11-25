<?php

/**
 * Created by PhpStorm.
 * Script Name: Test.php
 * Create: 12/20/21 11:49 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;
use app\admin\model\BotMember;
use app\common\model\kefu\ChatLog;
class Test
{

    public function index()
    {
        return response('hello thinkphp6');
    }

    /**
     * 
     * 模拟插入聊天
     */
    public function setPrivateChat()
    {
        $redis = get_redis();
        $year = date("Y");
        $chat_model = new ChatLog();
        $key = 'receive_private_chat';
        $time = time();
        $data = [
            "robot_wxid" => "cengzhiyang4294",  // 机器人账号id
            "type" => 1,  // 1/文本消息 3/图片消息 34/语音消息  42/名片消息  43/视频 47/动态表情 48/地理位置  49/分享链接  2001/红包  2002/小程序  2003/群邀请  更多请参考常量表
            "from_wxid" => "wxid_53fet7200ygs22",  // 来源用户ID
            "from_name" => "crush",  // 来源用户昵称
            "msg" => "模拟插入聊天111",  // 消息内容
            "clientid" => 0,  // 企业微信可用
            "robot_type" => 0,  // 来源微信类型 0 正常微信 / 1 企业微信
            "msg_id" => $time.rand(0,9999999)  // 消息ID
        ];
        $member_model = new BotMember();
        $member = $member_model->where(['wxid' => $data['from_wxid']])->find();
        //更改好友最后聊天时间
        $member_model->where(['id' => $member['id']])->update(['last_chat_time' => $time]);
        $member['last_chat_time'] = $time;
        $member['last_chat_content'] = $data['msg'];
        $msg = json_encode([
            'msg' => $data['msg'],
            'date' => date("Y-m-d H:i:s"),
            'msg_id' => $data['msg_id'],
            'headimgurl' => $member['headimgurl'],
            'from_wxid' => $data['from_wxid'],
            'robot_wxid' => $data['robot_wxid'],
            'client' => 1,
            'friend' => $member,
            'msg_type' => 1
        ]);
        $insert_data = [
            'from' => $data['from_wxid'],
            'to' => $data['robot_wxid'],
            'create_time' => time(),
            'content' => $data['msg'],
            'year' => $year,
            'from_headimg' => $member['headimgurl'],
            'msg_id' => $data['msg_id'],
            'type' => 'receive',
            'msg_type' => 1//文本
        ];
        $id = $chat_model->partition('p' . $year)->insertGetId($insert_data);
        $redis->rpush($key,$msg);
        dump($msg);
    }

    public function emoji(){
        $this->emoji = new \ky\Emoji();
        $unified =$this->emoji->emojiDocomoToUnified('\ue04a');
        // $bytes = $this->emoji->utf8Bytes($this->emoji->unifiedToHex($unified));
        $image = $this->emoji->emojiUnifiedToHtml($unified);
        dump($unified);exit;
    }
}
