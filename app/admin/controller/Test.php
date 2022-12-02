<?php

/**
 * Created by PhpStorm.
 * Script Name: Test.php
 * Create: 12/20/21 11:49 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;

use app\admin\model\Bot;
use app\admin\model\BotMember;
use app\common\model\kefu\ChatLog;
use app\common\model\kefu\Kefu;

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
            "msg_id" => $time . rand(0, 9999999)  // 消息ID
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
            'msg_type' => 1,
            'event' => 'msg'
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
            'msg_type' => 1 //文本
        ];
        $id = $chat_model->partition('p' . $year)->insertGetId($insert_data);
        $redis->rpush($key, $msg);
        dump($msg);
    }

    public function emoji()
    {
        $this->emoji = new \ky\Emoji();
        $unified = $this->emoji->emojiDocomoToUnified('\ue04a');
        // $bytes = $this->emoji->utf8Bytes($this->emoji->unifiedToHex($unified));
        $image = $this->emoji->emojiUnifiedToHtml($unified);
        dump($unified);
        exit;
    }

    /**
     * 
     * 模拟好友添加
     */
    public function setNewFriend()
    {
        $kefuM = new Kefu();
        $kefuM->sendToClinet([
            'event' => 'new_friend',
            'from_wxid' => 'wxid_53fet7200ygs22',
            'robot_wxid' => 'cengzhiyang4294',
            'admin_id' => 1
        ]);
    }


    public function convertReceiveMsg()
    {

        $msg_type = 3;
        $msg = "[pic=E:\北遇框架(兼容我的框架)\Data\wxid_eko8u5yga0jr22\4d6eb5054e05cef3ac288ca8423c6805.jpg]";
        $content = '';
        $last_chat_content = '';
        switch ($msg_type) {
                //文本消息
                //[微笑]
            case 1:
                $content = $msg;
                $last_chat_content = $content;
                break;
                //图片消息
                //转base64上传七牛云获取url地址
                //[pic=E:\北遇框架(兼容我的框架)\Data\wxid_eko8u5yga0jr22\4d6eb5054e05cef3ac288ca8423c6805.jpg]
            case 3:
                $bot_model = new Bot();
                $bot = $bot_model->getOne(22);
                $bot_client = $bot_model->getRobotClient($bot);
                $path = mb_substr($msg, 5, -1);
                $res = $bot_client->getFileFoBase64(['path' => $path]);
                $base64 = $res['ReturnStr'];
                $url = upload_base64('pic_' . rand(1000, 9999) . '_' . time(), $base64);
                $content = $url;
                $last_chat_content = "[图片]";
                break;
                //文件
                //[file=E:\北遇框架(兼容我的框架)\Data\xxx]
            case 2004:
                $bot_model = new Bot();
                $bot = $bot_model->getOne(22);
                $bot_client = $bot_model->getRobotClient($bot);
                $path = mb_substr($msg, 6, -1);
                $res = $bot_client->getFileFoBase64(['path' => $path]);
                $base64 = $res['ReturnStr'];
                $url = upload_base64('file_' . rand(1000, 9999) . '_' . time(), $base64);
                $content = $url;
                $last_chat_content = "[文件]";
                break;
                //语音
                //[mp3=C:\Users\Administrator\AppData\Local\Temp\2\wxmD189_tmp.mp3]
            case 34:
                $bot_model = new Bot();
                $bot = $bot_model->getOne(22);
                $bot_client = $bot_model->getRobotClient($bot);
                $path = mb_substr($msg, 5, -1);
                $res = $bot_client->getFileFoBase64(['path' => $path]);
                $base64 = $res['ReturnStr'];
                $url = upload_base64('mp3_' . rand(1000, 9999) . '_' . time(), $base64);
                $content = $url;
                $last_chat_content = "[语音消息]";
                break;
            case 42:
                $content = "[名片消息]";
                $last_chat_content = "[名片消息]";
                break;
            case 43:
                //视频消息
                //[mp4=C:\Users\Administrator\Documents\WeChat Files\wxid_bg2yo1n6rh2m22\FileStorage\Video\2022-11\0777bb2b86444a5ac848234dd1071683.mp4]
                $bot_model = new Bot();
                $bot = $bot_model->getOne(22);
                $bot_client = $bot_model->getRobotClient($bot);
                $path = mb_substr($msg, 5, -1);
                $res = $bot_client->getFileFoBase64(['path' => $path]);
                $base64 = $res['ReturnStr'];
                $url = upload_base64('mp4_' . rand(1000, 9999) . '_' . time(), $base64);
                $content = $url;
                $last_chat_content = "[视频]";
                break;
            case 47:
                //gif
                //[gif=E:\北遇框架(兼容我的框架)\Data\wxid_uzmmu9jzsvjn12\bac239988eb3606f63dbebebeb15dcb4.gif]
                $bot_model = new Bot();
                $bot = $bot_model->getOne(22);
                $bot_client = $bot_model->getRobotClient($bot);
                $path = mb_substr($msg, 5, -1);
                $res = $bot_client->getFileFoBase64(['path' => $path]);
                $base64 = $res['ReturnStr'];
                $url = upload_base64('gif_' . rand(1000, 9999) . '_' . time(), $base64);
                $content = $url;
                $last_chat_content = "[动态表情]";
                break;
            case 48:
                $content = "[地理位置]";
                $last_chat_content = "[地理位置]";
                break;
            case 49:
                $content = "[分享链接]";
                $last_chat_content = "[分享链接]";
                break;
                //转账
            case 2000:
                //转账
                //{"payer_pay_id":"100005000122112500083349286519001491","receiver_pay_id":"1000050001202211250210301400144","paysubtype":3,"money":"1165.00","pay_memo":""}
                $content = $msg;
                $last_chat_content = "[转账]";
                break;
            case 2001:
                $content = "[红包]";
                $last_chat_content = "[红包]";
                break;
            case 2003:
                $content = "[群邀请]";
                $last_chat_content = "[群邀请]";
                break;
            default:
                $content = "[链接]";
                $last_chat_content = "[链接]";
                break;
        }


        return $content;
    }


    public function filetobase64()
    {
        $msg = "[mp3=C:\Users\Administrator\AppData\Local\Temp\2\wxmD189_tmp.mp3]";
        $bot_model = new Bot();
        $bot = $bot_model->where(['id' => 38])->find();
        $bot_client = $bot_model->getRobotClient($bot);
        $path = mb_substr($msg, 5, -1);
        $res = $bot_client->downloadFile(['path' => $path]);
        dump($res);exit;
    }
}
