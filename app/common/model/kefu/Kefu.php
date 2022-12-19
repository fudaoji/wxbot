<?php

/**
 * Created by PhpStorm.
 * Script Name: Tpzs.php
 * Create: 2022/3/28 11:38
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\kefu;

use app\admin\controller\Kefu as ControllerKefu;
use app\common\model\Base;
use app\admin\model\BotMember;
use app\admin\model\Bot as BotM;
use app\common\model\kefu\ChatLog;
use ky\Logger;
class Kefu extends Base
{
    protected $isCache = false;

    public function __construct($data = [])
    {
        $this->table = $this->getTablePrefix() . 'kefu_' . $this->table;
        parent::__construct($data);
    }

    /**
     * 
     * 自动通过验证
     */
    public function autoPass($content, $bot, $botClient,$config)
    {
        // {
        //     "robot_wxid":"wxid_5fprdytoi1k612",
        //     "type":30,
        //     "from_wxid":"wxid_uzmmu9jzsvjn12",
        //     "from_name":"尚新假发 郑启示 新号",
        //     "v1":"v3_020b3826fd03010000000000df9a0737664def000000501ea9a3dba12f95f6b60a0536a1adb6c5611a1f1a3700022df4a80f14f2bd6a6cd092de24a79652f2839b48621948ba01e466dbd429f0d9fb7166c91bba8de674a28b426f025b3e6abe669b@stranger",
        //     "v2":"v4_000b708f0b04000001000000000012669003a7109f63ab14afd396631000000050ded0b020927e3c97896a09d47e6e9ebf39214f82d8bcb32d650371d210306bfcf482cbd3b431c81996b06d79d1282ec000480f94156e894de0a9173b89d22edfc6ee90336d5aa6ed7a3b46adf9e6b8ba89119394d669c7959e4b988bb8920cac296067d9f4690c9ec093bf4a5d0da9a2418de1a81a5145@stranger",
        //     "json_msg":{
        //         "scene":0,
        //         "headimgurl":"http://wx.qlogo.cn/mmhead/ver_1/lojuVBD2y5arq8ttXb1wXdfC5w1fyaRS3brEQ24uB5abH3tfMoMMnFlz8whthqJL9EkaCFQywZkcBS3c0HaCF670uEqfQUIYlAW1THeyYzM/0",
        //         "from_content":"我是尚新假发 郑启示 新号",
        //         "from_group_wxid":"",
        //         "share_wxid":"",
        //         "share_nickname":"",
        //         "v1":"v3_020b3826fd03010000000000df9a0737664def000000501ea9a3dba12f95f6b60a0536a1adb6c5611a1f1a3700022df4a80f14f2bd6a6cd092de24a79652f2839b48621948ba01e466dbd429f0d9fb7166c91bba8de674a28b426f025b3e6abe669b@stranger",
        //         "v2":"v4_000b708f0b04000001000000000012669003a7109f63ab14afd396631000000050ded0b020927e3c97896a09d47e6e9ebf39214f82d8bcb32d650371d210306bfcf482cbd3b431c81996b06d79d1282ec000480f94156e894de0a9173b89d22edfc6ee90336d5aa6ed7a3b46adf9e6b8ba89119394d669c7959e4b988bb8920cac296067d9f4690c9ec093bf4a5d0da9a2418de1a81a5145@stranger",
        //         "sex":1,
        //         "content":"我是尚新假发 郑启示 新号"
        //     },
        //     "robot_type":0
        // }
        Logger::write("好友自动通过Config:---".json_encode($config)."\n");
        if ($config['auto_pass']) {
            Logger::write("好友自动通过---"."\n");
            Logger::write("content".json_encode($content)."\n");
            $v1 = $content['json_msg']['v1'];
            $v2 = $content['json_msg']['v2'];
            $type = $content['type'];
            $res = $botClient->agreeFriendVerify([
                'robot_wxid' => $content['robot_wxid'],
                'v1' => $v1,
                'v2' => $v2,
                'type' => $type
            ]);
            Logger::write("好友自动通过接口返回:".json_encode($res)."\n");
            //插入用户表
            $bot_menber_model = new BotMember();
            if($data = $bot_menber_model->getOneByMap(['uin' => $bot['uin'], 'wxid' => $content['from_wxid']], ['id'])){
                $id = $data['id'];
                $bot_menber_model->updateOne([
                    'id' => $data['id'],
                    'nickname' => $content['from_name'],
                    'remark_name' => $content['json_msg']['from_content'],
                    'wxid' => $content['from_wxid'],
                    'headimgurl' => $content['json_msg']['headimgurl'],
                ]);

            }else{
                $id = $bot_menber_model->addOne([
                    'uin' => $bot['uin'],
                    'nickname' => $content['from_name'],
                    'remark_name' => $content['json_msg']['from_content'],
                    'type' => 'friend',
                    'wxid' => $content['from_wxid'],
                    'headimgurl' => $content['json_msg']['headimgurl'],
                ]);
            }
            //发送自动回复
            $auto_reply = trim($config['auto_reply']);
            if($auto_reply) {
                Logger::write("发送自动回复"."\n");
                // $ControllerKefu = new ControllerKefu();
                $param = ['bot_id' => $bot['id'],'type' => 1, 'to_wxid' => $content['from_wxid'], 'content' => $auto_reply, 'friend_id' => $id];
                // $ControllerKefu->sendMsg($param);
                // $ControllerKefu->sendMsgPost($param);
                $this->sendMsg($param);
                //发一条好友请求事件到前端，刷新好友列表
                $this->sendToClinet([
                    'event' => 'new_friend',
                    'from_wxid' => $content['from_wxid'], 
                    'robot_wxid' => $content['robot_wxid'],
                    'admin_id' => $bot['admin_id']
                ]);
            }
        }

    }
    /**
     * 发送信息到前端
     */
    public function sendToClinet($param){
        $key = 'receive_private_chat';
        $redis = get_redis();
        $msg = json_encode([
            'event' => $param['event'],
            'from_wxid' => $param['from_wxid'],
            'robot_wxid' => $param['robot_wxid'],
            'client' => $param['admin_id'],//对应用户id
        ]);
        $redis->rpush($key,$msg);
        return true;
    }

    public function sendMsg($post_data){
        $bot_model = new BotM();
        $chat_model = new ChatLog();
        $member_model = new BotMember();
        $date = date("Y-m-d H:i:s");
        $year = date("Y");
        $time = time();
        $bot = $bot_model->getOne($post_data['bot_id']);
        $content = $post_data['content'];
        // $content = $chat_model->convertMsg($content, $post_data['type']);
        // $last_chat_content = $content;
        $bot_client = $bot_model->getRobotClient($bot);
        if ($post_data['type'] == 1) { //文本
            $bot_client->sendTextToFriends([
                'robot_wxid' => $bot['uin'],
                'to_wxid' => $post_data['to_wxid'],
                'msg' => $post_data['content']
            ]);
            // $content = $this->emojiM->emojiText($post_data['content']);
            $last_chat_content = $content;
        } else if ($post_data['type'] == 3) { //图片
            // $bot_client->sendImgToFriends([
            //     'robot_wxid' => $bot['uin'],
            //     'to_wxid' => $post_data['to_wxid'],
            //     'path' => $post_data['content']
            // ]);
            $last_chat_content = '[图片]';
        } else if ($post_data['type'] == 2004) { //文件
            // $bot_client->sendFileToFriends([
            //     'robot_wxid' => $bot['uin'],
            //     'to_wxid' => $post_data['to_wxid'],
            //     'path' => $post_data['content']
            // ]);
            $last_chat_content = '[文件]';
        } else if ($post_data['type'] == 43) { //视频
            $last_chat_content = '[视频]';
        } else {
            $content = '[链接]';
            $last_chat_content = '[链接]';
        }
        $msgid = 'send_' . time() . $bot['admin_id'];
        // $insert_data = [
        //     'from' => $bot['uin'],
        //     'to' => $post_data['to_wxid'],
        //     'create_time' => $time,
        //     'content' => $post_data['content'],
        //     'year' => $year,
        //     'from_headimg' => $bot['headimgurl'],
        //     'msg_id' => $msgid,
        //     'type' => 'send',
        //     'msg_type' => $post_data['type'] //文本
        // ];
        // $chat_model->partition('p' . $year)->insertGetId($insert_data);
        //更改好友最后聊天时间
        if (isset($post_data['friend_id']) && $post_data['friend_id'] > 0) {
            $friend_id = $post_data['friend_id'];
        } else {
            $friend_id = $member_model->where(['uin' => $bot['uin'], 'wxid' => $post_data['to_wxid']])->order(['id' => 'desc'])->value('id');
        }
        $member_model->where(['id' => $friend_id])->update(['last_chat_time' => $time]);
        $friend = $member_model->where(['id' => $friend_id])->find();
        $friend['last_chat_content'] = $last_chat_content;

        $result = [
            'msg_id' => $msgid,
            'date' => $date,
            'content' => $content,
            'type' => 'send',
            'class' => 'my_chat_content',
            'quote' => $post_data['quote'] ?? '',
            'headimgurl' => $bot['headimgurl'],
            'friend' => $friend,
            'msg_type' => $post_data['type'],
        ];
        //最后一条聊天记录放redis
        $redis = get_redis();
        $key = 'last_chat_log:' . $bot['uin'];
        $hkey = $post_data['to_wxid'];
        $h_data = $result;
        $h_data['content'] = $last_chat_content;
        $redis->hSet($key, $hkey, json_encode($result));
        Logger::write("自动回复发送成功"."\n");
    }
}
