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
            "from_wxid" => "yeshumiao628",  // 来源用户ID
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
            'event' => 'msg',
            'last_chat_content' => $data['msg'],
            'last_chat_time' => $time
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
        model('common/setting')->settings();
        $msg = '[mp4=D:\weixinjilu\WeChat Files\wxid_5fprdytoi1k612\FileStorage\Video\2022-12\d97e4708ae1f7b3947c2d38c7c6976a8.mp4]';
        $bot_model = new Bot();
        $bot = $bot_model->where(['id' => 40])->find();
        dump($bot);
        dump($bot_model->getlastsql());
        $bot_client = $bot_model->getRobotClient($bot);
        $path = mb_substr($msg, 5, -1);
        dump($path);
        $res = $bot_client->downloadFile(['path' => $path]);
        dump($res);
        $base64 = $res['ReturnStr'];
        $url = upload_base64('mp4_' . rand(1000, 9999) . '_' . time(), $base64);
        dump($url);
        exit;
    }

    public function sendVideoMsg()
    {
        $member_model = new BotMember();
        $member = $member_model->where(['wxid' => 'wxid_53fet7200ygs22'])->find();
        $msg = json_encode([
            'msg' => 'http://webwx.sxwig.com/mp4_4890_1669988986',
            'date' => date("Y-m-d H:i:s"),
            'msg_id' => '5791531156442254467',
            'headimgurl' => 'http://wx.qlogo.cn/mmhead/ver_1/sGCpic6YuOTdGoYdslG1riaCj5Vly5P33FbXibfG1guiaXW5OokPr1ltt3nurPpCosQcgibQNic60pond25zBczooPialXG891Lxk19arf3RicLkbdk/0',
            'from_wxid' => 'wxid_53fet7200ygs22',
            'robot_wxid' => 'cengzhiyang4294',
            'client' => 1,
            'friend' => $member,
            'msg_type' => 43,
            'event' => 'msg',
            'last_chat_content' => '[视频]',
        ]);
        $redis = get_redis();
        $key = 'receive_private_chat';
        $redis->rpush($key, $msg);
        echo "ok";
    }

    public function sendTransfer()
    {
        $member_model = new BotMember();
        $member = $member_model->where(['wxid' => 'wxid_53fet7200ygs22'])->find();
        $msg = '{"payer_pay_id":"100005000122112500083349286519001491","receiver_pay_id":"1000050001202211250210301400144","paysubtype":3,"money":"1165.00","pay_memo":""}';
        // $msg = json_decode($msg,true);
        $msg = json_encode([
            'msg' => $msg,
            'date' => date("Y-m-d H:i:s"),
            'msg_id' => '5791531156442254467',
            'headimgurl' => 'http://wx.qlogo.cn/mmhead/ver_1/sGCpic6YuOTdGoYdslG1riaCj5Vly5P33FbXibfG1guiaXW5OokPr1ltt3nurPpCosQcgibQNic60pond25zBczooPialXG891Lxk19arf3RicLkbdk/0',
            'from_wxid' => 'wxid_53fet7200ygs22',
            'robot_wxid' => 'cengzhiyang4294',
            'client' => 1,
            'friend' => $member,
            'msg_type' => 2000,
            'event' => 'msg',
            'last_chat_content' => '[转账]',
        ]);
        $redis = get_redis();
        $key = 'receive_private_chat';
        $redis->rpush($key, $msg);
        echo "ok";
    }

    public function sendTextMsg()
    {
        $member_model = new BotMember();
        $member = $member_model->where(['wxid' => 'wxid_53fet7200ygs22'])->find();
        $msg = '测试3';
        $member['last_chat_content'] = $msg;
        $member['last_chat_time'] = time();
        // $msg = json_decode($msg,true);
        $msg = json_encode([
            'msg' => $msg,
            'date' => date("Y-m-d H:i:s"),
            'msg_id' => '5791531156442254467',
            'headimgurl' => 'http://wx.qlogo.cn/mmhead/ver_1/sGCpic6YuOTdGoYdslG1riaCj5Vly5P33FbXibfG1guiaXW5OokPr1ltt3nurPpCosQcgibQNic60pond25zBczooPialXG891Lxk19arf3RicLkbdk/0',
            'from_wxid' => 'wxid_53fet7200ygs22',
            'robot_wxid' => 'cengzhiyang4294',
            'client' => 1,
            'friend' => $member,
            'msg_type' => 1,
            'event' => 'msg',
            'last_chat_content' => $msg,
        ]);
        $redis = get_redis();
        $key = 'receive_private_chat';
        $redis->rpush($key, $msg);
        echo "ok";
    }

    public function msgCallback()
    {
        // {
        //     "robot_wxid":"wxid_bg2yo1n6rh2m22",
        //     "type":1,
        //     "msg":"现在手机发",
        //     "to_wxid":"cengzhiyang4294",
        //     "to_name":"zengzhiyang",
        //     "clientid":0,
        //     "robot_type":0,
        //     "msg_id":"7532735423531046036"
        // }
        $time = time();
        $year = date("Y");
        $data = [
            "robot_wxid" => "cengzhiyang4294",
            "type" => 1,
            "msg" => "现在手机发",
            "to_wxid" => "wxid_53fet7200ygs22",
            "to_name" => "crush",
            "clientid" => 0,
            "robot_type" => 0,
            "msg_id" => "753273542" . time(),
        ];
        $botM = new Bot();
        $bot = $botM->where(['id' => 22])->find();
        $member_model = new BotMember();
        $member = $member_model->where(['wxid' => $data['to_wxid']])->find();
        //更改好友最后聊天时间
        // $member_model->where(['id' => $member['id']])->update(['last_chat_time' => $time]);
        $member['last_chat_time'] = $time;
        $member['last_chat_content'] = "现在手机发";
        $msg = json_encode([
            'robot_wxid' => 'cengzhiyang4294',
            'to' => $data['to_wxid'],
            'create_time' => $time,
            'content' => "现在手机发",
            'year' => $year,
            'from_headimg' => $bot['headimgurl'],
            'msg_id' => $data['msg_id'],
            'type' => 'send',
            'msg_type' => $data['type'], //文本
            'event' => 'callback',
            'friend' => $member,
            'client' => 1,
        ]);
        $redis = get_redis();
        $key = 'receive_private_chat';
        $redis->rpush($key, $msg);
    }


    public function sendShareLink()
    {
        $msg = (new ChatLog())->where(['id' => 328])->value('content');
        $convert = $this->convertShareLink($msg);
        $last_chat_content = "[分享链接]";
        $msg_type = 49;
        $member_model = new BotMember();
        $member = $member_model->where(['wxid' => 'wxid_53fet7200ygs22'])->find();
        $member['last_chat_content'] = $last_chat_content;
        $member['last_chat_time'] = time();
        // $msg = json_decode($msg,true);
        $msg = json_encode([
            'msg' => $convert,
            'date' => date("Y-m-d H:i:s"),
            'msg_id' => '5791531156442254467',
            'headimgurl' => 'http://wx.qlogo.cn/mmhead/ver_1/sGCpic6YuOTdGoYdslG1riaCj5Vly5P33FbXibfG1guiaXW5OokPr1ltt3nurPpCosQcgibQNic60pond25zBczooPialXG891Lxk19arf3RicLkbdk/0',
            'from_wxid' => 'wxid_53fet7200ygs22',
            'robot_wxid' => 'cengzhiyang4294',
            'client' => 1,
            'friend' => $member,
            'msg_type' => $msg_type,
            'event' => 'msg',
            'last_chat_content' => $last_chat_content,
        ]);
        $redis = get_redis();
        $key = 'receive_private_chat';
        $redis->rpush($key, $msg);
        echo "ok";
    }

    public function convertShareLink($msg)
    {
        preg_match('/<title><!\[CDATA\[(.*?)]]><\/title>/ism', $msg, $title_res);
        preg_match('/<des><!\[CDATA\[(.*?)]]><\/des>/ism', $msg, $des_res);
        preg_match('/<url><!\[CDATA\[(.*?)]]><\/url>/ism', $msg, $url_res);
        $title = '';
        $des = '';
        $url = '';
        if (isset($title_res[1])) {
            $title = $title_res[1];
        }
        if (isset($des_res[1])) {
            $des = $des_res[1];
        }
        if (isset($url_res[1])) {
            $url = $url_res[1];
        }

        return ['title' => $title, 'des' => $des, 'url' => $url];
    }


    /**
     * 
     * 发送名片
     */
    public function sendBusinessCard()
    {
        $msg = '<?xml version="1.0"?>
        <msg bigheadimgurl="http://wx.qlogo.cn/mmhead/ver_1/5Wt9sZZ0leGpUAiaC9kZUnUqHc4QWPibic6lz6P5ic7T91qtiarOsMjTSUmh5GQOZgSv68XARWMfVvcD4tvLkyiabkDds2jbOicIpKFoqhY4BF6qMk/0" smallheadimgurl="http://wx.qlogo.cn/mmhead/ver_1/5Wt9sZZ0leGpUAiaC9kZUnUqHc4QWPibic6lz6P5ic7T91qtiarOsMjTSUmh5GQOZgSv68XARWMfVvcD4tvLkyiabkDds2jbOicIpKFoqhY4BF6qMk/132" username="cengzhiyang4294" nickname="zengzhiyang" fullpy="zengzhiyang" shortpy="" alias="" imagestatus="3" scene="17" province="冰岛" city="冰岛" sign="" sex="1" certflag="0" certinfo="" brandIconUrl="" brandHomeUrl="" brandSubscriptConfigUrl="" brandFlags="0" regionCode="IS" biznamecardinfo="" antispamticket="cengzhiyang4294" />
        ';

        $convert = $this->convertBusinessCard($msg);
        $last_chat_content = '向你推荐了' . $convert['nickname'];
        $msg_type = 42;
        $this->send(json_encode($convert), $last_chat_content, $msg_type);
    }

    public function convertBusinessCard($msg)
    {
        preg_match('/bigheadimgurl="(.*?)"/ism', $msg, $headimgurl_res);
        preg_match('/nickname="(.*?)"/ism', $msg, $nickname_res);
        preg_match('/username="(.*?)"/ism', $msg, $username_res);
        preg_match('/sex="(.*?)"/ism', $msg, $sex_res);
        preg_match('/province="(.*?)"/ism', $msg, $province_res);
        preg_match('/city="(.*?)"/ism', $msg, $city_res);
        $headimgurl = '';
        $nickname = '';
        $username = '';
        $sex = '';
        $province = ''; 
        $city='';
        if (isset($headimgurl_res[1])) {
            $headimgurl = $headimgurl_res[1];
        }
        if (isset($nickname_res[1])) {
            $nickname = $nickname_res[1];
        }
        if (isset($username_res[1])) {
            $username = $username_res[1];
        }
        if (isset($sex_res[1])) {
            $sex = $sex_res[1] == 1 ? '男' : '女';
        }
        if (isset($province_res[1])) {
            $province = $province_res[1];
        }
        if (isset($city_res[1])) {
            $city = $city_res[1];
        }
        return ['headimgurl' => $headimgurl, 'nickname' => $nickname, 'username' => $username, 'sex' => $sex, 'province' => $province, 'city' => $city];
    }


    public function send($convert, $last_chat_content, $msg_type)
    {
        $member_model = new BotMember();
        $member = $member_model->where(['wxid' => 'wxid_53fet7200ygs22'])->find();
        $member['last_chat_content'] = $last_chat_content;
        $member['last_chat_time'] = time();
        // $msg = json_decode($msg,true);
        $msg = json_encode([
            'msg' => $convert,
            'date' => date("Y-m-d H:i:s"),
            'msg_id' => '5791531156442254467',
            'headimgurl' => 'http://wx.qlogo.cn/mmhead/ver_1/sGCpic6YuOTdGoYdslG1riaCj5Vly5P33FbXibfG1guiaXW5OokPr1ltt3nurPpCosQcgibQNic60pond25zBczooPialXG891Lxk19arf3RicLkbdk/0',
            'from_wxid' => 'wxid_53fet7200ygs22',
            'robot_wxid' => 'cengzhiyang4294',
            'client' => 1,
            'friend' => $member,
            'msg_type' => $msg_type,
            'event' => 'msg',
            'last_chat_content' => $last_chat_content,
        ]);
        $redis = get_redis();
        $key = 'receive_private_chat';
        $redis->rpush($key, $msg);
        echo "ok";
    }
}
