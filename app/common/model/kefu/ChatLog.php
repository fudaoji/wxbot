<?php

/**
 * Created by PhpStorm.
 * Script Name: Config.php
 * Create: 2022/4/6 16:05
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\kefu;

use app\admin\controller\Groupmember;
use app\admin\model\Bot;
use app\admin\model\BotMember;
use app\common\model\EmojiCode;
use app\admin\model\BotGroupmember;
use ky\Logger;

class ChatLog extends Kefu
{
    protected $isCache = false;
    protected $table = 'chat_log';


    public function saveChat($data, $bot)
    {
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
        $member = $member_model->where(['uin' => $bot['uin'], 'wxid' => $data['from_wxid']])->find();
        //更改好友最后聊天时间
        $member_model->where(['id' => $member['id']])->update(['last_chat_time' => $time, 'new' => 1]);
        //信息转换
        $convert = $this->convertReceiveMsg($data['msg'], $data['type'], $bot);
        //Logger::write("收到信息" . json_encode($data['msg']) . "\n");
        //Logger::write("转化信息" . json_encode($convert) . "\n");
        $member['last_chat_time'] = $time;
        $member['last_chat_content'] = $convert['last_chat_content'];
        // $bot = $bot_model->where(['uin' => $data['robot_wxid']])->find();
        $msg = json_encode([
            'msg' => $convert['content'],
            'date' => date("Y-m-d H:i:s", $time),
            'msg_id' => $data['msg_id'],
            'headimgurl' => $member['headimgurl'],
            'from_wxid' => $data['from_wxid'],
            'robot_wxid' => $data['robot_wxid'],
            'client' => $bot['staff_id'], //对应用户id
            'friend' => $member,
            'msg_type' => $data['type'],
            'event' => 'msg',
            'last_chat_content' => $convert['last_chat_content'],
            'type' => 'receive',
            'friend_type' => 'friend'
        ]);
        $insert_data = [
            'from' => $data['from_wxid'],
            'to' => $data['robot_wxid'],
            'create_time' => $time,
            'content' => $convert['content'],
            'year' => $year,
            'from_headimg' => $member['headimgurl'],
            'msg_id' => $data['msg_id'],
            'type' => 'receive',
            'msg_type' => $data['type'],
            'from_name' => $data['from_name'],
        ];
        $id = $chat_model->partition('p' . $year)->insertGetId($insert_data);
        $redis->rpush($key, $msg);
        //Logger::error("消息入队列OK：" . json_encode($msg) . "\n");
        //视频转换失败
        if (in_array($data['type'], [43, 2004]) && $convert['content'] == '') {
            $delay_second = 10;
            $key_delay = 'receive_private_chat_delay';
            $r_data = [
                'msg_type' => $data['type'],
                'msg' => $data['msg'],
                'delay_second' => 10,
                'start_time' => $time + $delay_second,
                'num' => 0, //执行次数
                'msg_id' => $data['msg_id'],
                'id' => $id,
                'bot' => $bot,
                'client' => $bot['staff_id'], //对应用户id
                'robot_wxid' => $data['robot_wxid'],
                'from_wxid' => $data['from_wxid'],
            ];
            $redis->rpush($key_delay, json_encode($r_data));
            Logger::write("视频/文件转换失败,存延迟队列：" . json_encode($r_data) . "\n");
        }
    }

    /**
     * 
     * 接收消息转换
     */
    public function convertReceiveMsg($msg = '', $msg_type = 1, $bot = [])
    {
        model('common/setting')->settings();
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
                //Logger::write("收到图片消息" . "\n");
                $bot_model = new Bot();
                $bot_client = $bot_model->getRobotClient($bot);
                $path = mb_substr($msg, 5, -1);
                $res = $bot_client->downloadFile(['path' => $path]);
                // Logger::write("path:".$path."\n");
                // Logger::write("res:".json_encode($res)."\n");
                if(empty($res['ReturnStr'])){  //todo 其他框架的要再处理
                    $url = '';
                }else{
                    $base64 = $res['ReturnStr'];
                    $url = upload_base64('pic_' . rand(1000, 9999) . '_' . time(), $base64);
                }
                $content = $url;
                $last_chat_content = "[图片]";
                break;
                //文件
                //[file=E:\北遇框架(兼容我的框架)\Data\xxx]
            case 2004:
                //Logger::write("文件消息" . json_encode($msg) . "\n");
                $bot_model = new Bot();
                $bot_client = $bot_model->getRobotClient($bot);
                $path = mb_substr($msg, 6, -1);
                if ($path) {
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $file_name = substr(strrchr($path, "\\"), 1);
                    $res = $bot_client->downloadFile(['path' => $path]);
                    if ($res['Code'] != 0) {
                        //Logger::write("转换文件消息为base64错误:" . json_encode($res, JSON_UNESCAPED_UNICODE) . "\n");
                        $content = '';
                    } else {
                        $base64 = $res['ReturnStr'];
                        $url = upload_base64('file_' . rand(1000, 9999) . '_' . time() . $file_name, $base64);
                        $content = json_encode(['url' => $url, 'file_name' => $file_name, 'ext' => $ext]);
                    }
                } else {
                    echo "path地址为空:" . "\n";
                    Logger::write("path地址为空:" . "\n");
                    $content = '';
                }

                $last_chat_content = "[文件]";
                break;
                //语音
                //[mp3=C:\Users\Administrator\AppData\Local\Temp\2\wxmD189_tmp.mp3]
            case 34:
                $bot_model = new Bot();
                $bot_client = $bot_model->getRobotClient($bot);
                $path = mb_substr($msg, 5, -1);
                $res = $bot_client->downloadFile(['path' => $path]);
                $base64 = $res['ReturnStr'];
                $url = upload_base64('mp3_' . rand(1000, 9999) . '_' . time(), $base64);
                $content = $url;
                $last_chat_content = "[语音消息]";
                break;
            case 42:
                Logger::write("收到名片消息" . json_encode($msg) . "\n");
                $convert = $this->convertBusinessCard($msg);
                $content = json_encode($convert);
                $last_chat_content = '向你推荐了' . $convert['nickname'];
                break;
            case 43:
                //Logger::write("视频消息转换");
                //视频消息
                //[mp4=C:\Users\Administrator\Documents\WeChat Files\wxid_bg2yo1n6rh2m22\FileStorage\Video\2022-11\0777bb2b86444a5ac848234dd1071683.mp4]
                $bot_model = new Bot();
                $bot_client = $bot_model->getRobotClient($bot);
                $path = mb_substr($msg, 5, -1);
                if ($path) {
                    //Logger::write("视频消息path" .$path."\n");
                    $res = $bot_client->downloadFile(['path' => $path]);
                    //Logger::write("res:" .json_encode($res));
                    if ($res['Code'] != 0) {
                        echo "转换视频消息为base64错误:" . json_encode($res, JSON_UNESCAPED_UNICODE) . "\n";
                        //Logger::write("转换视频消息为base64错误:" . json_encode($res) . "\n");
                        $url = '';
                    } else {
                        //Logger::write("转换成功11111" . "\n");
                        $base64 = $res['ReturnStr'];
                        $url = upload_base64('mp4_' . rand(1000, 9999) . '_' . time(), $base64);
                    }
                } else {
                    Logger::write("视频地址为空:" . "\n");
                    echo "视频地址为空:" . "\n";
                    $url = '';
                }

                $content = $url;
                $last_chat_content = "[视频]";
                Logger::write("视频消息OK" . "\n");
                break;
            case 47:
                //gif
                //[gif=E:\北遇框架(兼容我的框架)\Data\wxid_uzmmu9jzsvjn12\bac239988eb3606f63dbebebeb15dcb4.gif]
                $bot_model = new Bot();
                $bot_client = $bot_model->getRobotClient($bot);
                $path = mb_substr($msg, 5, -1);
                $res = $bot_client->downloadFile(['path' => $path]);
                if(!empty($res['ReturnStr'])){
                    $base64 = $res['ReturnStr'];
                    $url = upload_base64('gif_' . rand(1000, 9999) . '_' . time(), $base64);
                    $content = $url;
                }else{
                    $content = '表情下载失败';
                }
                $last_chat_content = "[动态表情]";
                break;
            case 48:
                $content = "[地理位置]";
                $last_chat_content = "[地理位置]";
                break;
            case 49:
                $content = json_encode($this->convertShareLink($msg));
                // $content = $msg;
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
            case 10000:
                //可能是系统消息
                $content = $msg;
                $last_chat_content = $msg;
                break;
            case 10003:
                //引用消息
                //{"msg":"123","content":"6","svrid":"3026530822627612838","fromusr":"wxid_5fprdytoi1k612","chatusr":"","displayname":"zengzhiyang"}
                $content = $msg;
                $json_msg = json_decode($content, true);
                $last_chat_content = $json_msg['msg'];
                break;
            default:
                $content = "[链接]";
                $last_chat_content = "[链接]";
                break;
        }


        return ['content' => $content, 'last_chat_content' => $last_chat_content];
    }

    /**
     * 
     * 消息内容转换html代码
     * 1/文本消息 3/图片消息 34/语音消息  42/名片消息  43/视频 47/动态表情 48/地理位置  49/分享链接  2001/红包  2002/小程序  2003/群邀请  接收文件 2004
     */
    public function convertMsgToHtml($msg = '', $msg_type = 1)
    {
        $emojiM = new EmojiCode();
        $content = '';
        switch ($msg_type) {
                //文本消息
            case 1:
                $content = $emojiM->emojiText($msg, 'img');
                break;
                //     //图片消息
                // case 3:
                //     $content = $msg;
                //     break;
                //     //文件
            case 2004:
                $content = json_decode($msg, true);
                break;
                // case 34:
                //     $content = "[语音消息]";
                //     break;
            case 42:
                // [名片消息]
                $content = json_decode($msg, true);
                break;
                // case 43:
                //     $content = "[视频]";
                //     break;
                // case 47:
                //     $content = "[动态表情]";
                //     break;
                // case 48:
                //     $content = "[地理位置]";
                //     break;
            case 49:
                $content = json_decode($msg, true);
                break;
                // //转账
            case 2000:
                $content = json_decode($msg, true);
                break;
            case 10003:
                //引用信息
                $content = json_decode($msg, true);
                break;
                // case 2001:
                //     $content = "[红包]";
                //     break;
                // case 2003:
                //     $content = "[群邀请]";
                //     break;
            default:
                $content = $msg;
                break;
        }


        return $content;
    }


    /**
     * 
     * 保存手机端发送的消息
     */
    public function saveMobileMsg($data, $bot)
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
        //Logger::write("保存发送的消息" . json_encode($data) . "\n");
        $time = time();
        $year = date("Y");
        $chat_model = new ChatLog();
        $member_model = new BotMember();
        $member = $member_model->where(['uin' => $bot['uin'], 'wxid' => $data['to_wxid']])->find();
        //更改好友最后聊天时间
        $member_model->where(['id' => $member['id']])->update(['last_chat_time' => $time, 'new' => 1]);
        //信息转换
        $convert = $this->convertReceiveMsg($data['msg'], $data['type'], $bot);
        $member['last_chat_time'] = $time;
        $member['last_chat_content'] = $convert['last_chat_content'];
        $insert_data = [
            'from' => $data['robot_wxid'],
            'to' => $data['to_wxid'],
            'create_time' => $time,
            'content' => $convert['content'],
            'year' => $year,
            'from_headimg' => $bot['headimgurl'],
            'msg_id' => $data['msg_id'],
            'type' => 'send',
            'msg_type' => $data['type'] //文本
        ];
        $id = $chat_model->partition('p' . $year)->insertGetId($insert_data);

        $msg = json_encode([
            'robot_wxid' => $data['robot_wxid'],
            'to' => $data['to_wxid'],
            'create_time' => $time,
            'content' => $convert['content'],
            'year' => $year,
            'headimgurl' => $bot['headimgurl'],
            'msg_id' => $data['msg_id'],
            'type' => 'send',
            'msg_type' => $data['type'], //文本
            'event' => 'callback',
            'friend' => $member,
            'client' => $bot['staff_id'], //对应用户id
        ]);
        $redis = get_redis();
        $key = 'receive_private_chat';
        $redis->rpush($key, $msg);
        //Logger::write("保存发送的消息,推送前端:" . json_encode($msg) . "\n");
        //视频转换失败
        if (in_array($data['type'], [43, 2004]) && $convert['content'] == '') {
            $delay_second = 10;
            $key_delay = 'receive_private_chat_delay';
            $r_data = [
                'msg_type' => $data['type'],
                'msg' => $data['msg'],
                'delay_second' => $delay_second,
                'start_time' => $time + $delay_second,
                'num' => 0, //执行次数
                'msg_id' => $data['msg_id'],
                'id' => $id,
                'bot' => $bot,
                'client' => $bot['staff_id'], //对应用户id
                'robot_wxid' => $data['robot_wxid'],
                'from_wxid' => $data['to_wxid'],
            ];
            $redis->rpush($key_delay, json_encode($r_data));
            //Logger::write("视频/文件转换失败,存延迟队列：" . json_encode($r_data) . "\n");
        }
    }


    /**
     * 
     * 转换分享信息
     */
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
        } else {
            preg_match('/<title>(.*?)<\/title>/ism', $msg, $title_res2);
            if (isset($title_res2[1])) {
                $title = $title_res2[1];
            }
        }
        if (isset($des_res[1])) {
            $des = $des_res[1];
        } else {
            preg_match('/<des>(.*?)<\/des>/ism', $msg, $des_res2);
            $des = $des_res2[1];
        }
        if (isset($url_res[1])) {
            $url = $url_res[1];
        } else {
            preg_match('/<url>(.*?)<\/url>/ism', $msg, $url_res2);
            $url = $url_res2[1];
        }

        return ['title' => $title, 'des' => $des, 'url' => $url];
    }
    /**
     * 
     * 转换名片信息
     * 
     */
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
        $city = '';
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

    /**
     * 
     * 保存接收群里数据
     */
    public function saveGroupChat($data, $bot)
    {
        // {
        //     "robot_wxid": "wxid_5fprdytoi1k612",
        //     "type": 1,
        //     "from_group": "34907960925@chatroom",
        //     "from_group_name": "微信多客服测试优化",
        //     "from_wxid": "cengzhiyang4294",
        //     "from_name": "zengzhiyang",
        //     "msg": "还没弄好",
        //     "msg_source": null,
        //     "clientid": 0,
        //     "robot_type": 0,
        //     "msg_id": "1591024870470498640"
        // }
        //Logger::write("保存接收群里数据" . json_encode($data) . "\n");
        $redis = get_redis();
        $year = date("Y");
        $chat_model = new ChatLog();
        // $bot_model = new ModelBot();
        $key = 'receive_private_chat';
        $time = time();
        $member_model = new BotMember();
        $member = $member_model->where(['uin' => $bot['uin'], 'wxid' => $data['from_group']])->find();
        //更改好友最后聊天时间
        $member_model->where(['id' => $member['id']])->update(['last_chat_time' => $time, 'new' => 1]);
        //信息转换
        $convert = $this->convertReceiveMsg($data['msg'], $data['type'], $bot);
        //Logger::write("收到群信息" . json_encode($data['msg']) . "\n");
        //Logger::write("转化群信息" . json_encode($convert) . "\n");
        $member['last_chat_time'] = $time;
        $member['last_chat_content'] = $convert['last_chat_content'];
        // $bot = $bot_model->where(['uin' => $data['robot_wxid']])->find();
        //获取群成员信息
        $groupMemberM = new BotGroupmember();
        $group_headimg = '';
        $group_nickname = '';
        $groupMember = $groupMemberM->where(['wxid' => $data['from_wxid'], 'bot_id' => $bot['id']])->find();
        if ($groupMember) {
            $group_headimg = $groupMember['headimgurl'];
            $group_nickname = $groupMember['nickname'];
        }
        $msg = json_encode([
            'msg' => $convert['content'],
            'date' => date("Y-m-d H:i:s", $time),
            'msg_id' => $data['msg_id'],
            'headimgurl' => $member['headimgurl'],
            'from_wxid' => $data['from_group'],
            'robot_wxid' => $data['robot_wxid'],
            'client' => $bot['staff_id'], //对应用户id
            'friend' => $member,
            'msg_type' => $data['type'],
            'event' => 'msg',
            'last_chat_content' => $convert['last_chat_content'],
            'type' => 'receive',
            'friend_type' => 'group',
            'group_from_wxid' => $data['from_wxid'],
            'group_from_name' => $data['from_name'],
            'group_headimg' => $group_headimg,
            'group_nickname' => $group_nickname,

        ]);
        $insert_data = [
            'from' => $data['from_group'],
            'to' => $data['robot_wxid'],
            'create_time' => $time,
            'content' => $convert['content'],
            'year' => $year,
            'from_headimg' => $member['headimgurl'],
            'msg_id' => $data['msg_id'],
            'type' => 'receive',
            'msg_type' => $data['type'],
            'from_name' => $data['from_group_name'],
            'friend_type' => 'group',
            'group_from_wxid' => $data['from_wxid'],
            'group_from_name' => $data['from_name'],
            'group_headimg' => $group_headimg,
            'group_nickname' => $group_nickname,
        ];
        $id = $chat_model->partition('p' . $year)->insertGetId($insert_data);
        $redis->rpush($key, $msg);
        //Logger::write("存储数据OK：" . json_encode($msg) . "\n");
        //视频转换失败
        if (in_array($data['type'], [43, 2004]) && $convert['content'] == '') {
            $delay_second = 10;
            $key_delay = 'receive_private_chat_delay';
            $r_data = [
                'msg_type' => $data['type'],
                'msg' => $data['msg'],
                'delay_second' => $delay_second,
                'start_time' => $time + $delay_second,
                'num' => 0, //执行次数
                'msg_id' => $data['msg_id'],
                'id' => $id,
                'bot' => $bot,
                'client' => $bot['staff_id'], //对应用户id
                'robot_wxid' => $data['robot_wxid'],
                'from_wxid' => $data['from_group'],
                'friend_type' => 'group',
                'group_from_wxid' => $data['from_wxid'],
            ];
            $redis->rpush($key_delay, json_encode($r_data));
            //Logger::write("视频/文件转换失败,存延迟队列：" . json_encode($r_data) . "\n");
        }
    }
}
