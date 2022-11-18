<?php

namespace app\admin\model;

use app\common\model\Base;
use app\admin\model\Bot as ModelBot;
class ChatLog extends Base
{

    public function saveChat($data, $bot){
        // "content": {
        //     "robot_wxid": "",  // 机器人账号id
        //     "type": 1,  // 1/文本消息 3/图片消息 34/语音消息  42/名片消息  43/视频 47/动态表情 48/地理位置  49/分享链接  2001/红包  2002/小程序  2003/群邀请  更多请参考常量表
        //     "from_wxid": "",  // 来源用户ID
        //     "from_name": "",  // 来源用户昵称
        //     "msg": "",  // 消息内容
        //     "clientid": 0,  // 企业微信可用
        //       "robot_type": 0,  // 来源微信类型 0 正常微信 / 1 企业微信
        //     "msg_id": 0  // 消息ID
        // }  // 内容（易语言模板的参数名）
        $redis = get_redis();
        $year = date("Y");
        $chat_model = new ChatLog();
        // $bot_model = new ModelBot();
        $key = 'receive_private_chat';
        $time = time();
        $member_model = new BotMember();
        $member = $member_model->where(['wxid' => $data['from_wxid']])->find();
        //更改好友最后聊天时间
        $member_model->where(['id' => $member['id']])->update(['last_chat_time' => $time]);
        $member['last_chat_time'] = $time;
        $member['last_chat_content'] = $data['msg'];
        // $bot = $bot_model->where(['uin' => $data['robot_wxid']])->find();
        $msg = json_encode([
            'msg' => $data['msg'],
            'date' => date("Y-m-d H:i:s",$time),
            'msg_id' => $data['msg_id'],
            'headimgurl' => $member['headimgurl'],
            'from_wxid' => $data['from_wxid'],
            'robot_wxid' => $data['robot_wxid'],
            'client' => $bot['admin_id'],//对应用户id
            'friend' => $member
        ]);
        $insert_data = [
            'from' => $data['from_wxid'],
            'to' => $data['robot_wxid'],
            'create_time' => $time,
            'content' => $data['msg'],
            'year' => $year,
            'from_headimg' => $member['headimgurl'],
            'msg_id' => $data['msg_id'],
            'type' => 'receive',
            'msg_type' => $data['type'],
        ];
        $chat_model->partition('p' . $year)->insertGetId($insert_data);
        $redis->rpush($key,$msg);
    }
}
