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
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use ky\Quark;
use ky\VideoSpider;
use tests\cases\bot\AutoAuth;
use Symfony\Component\Panther\Client as PantherClient;
use zjkal\ChinaHoliday;

class Test
{
    function streamQvqMax() {
// 流式响应配置
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no');  // 禁用Nginx代理缓冲‌:ml-citation{ref="6" data="citationList"}
        ob_implicit_flush(true);
        ob_end_flush();


        $img = "http://images.kuryun.com/1-67e954ff4c252.png";
        $prompt = '以[xxxx]格式返回这张图片上的四位数验证码';
        $apiKey = "sk-2d304fb9100046fc9eabef7a848a4ff4"; // 替换为 API-KEY ‌:ml-citation{ref="6" data="citationList"}
        $client = new Client();

        // 请求参数（需确认模型名是否为 qvq-max，建议参考官方文档）‌:ml-citation{ref="8" data="citationList"}
        $data = [
            'model' => 'qvq-max', // 注意模型名称需与官方文档一致
            'messages' => [
                [
                    'role' => 'user',
                    "content" => [
                        ['type' => 'text', 'text' => $prompt],
                        ['type' => 'image_url', 'image_url' => ['url' => $img]]
                    ]
                ]
            ],
            'stream' => true // 启用流式输出 ‌:ml-citation{ref="1,5" data="citationList"}
        ];
        //dump($data);
        try {
            $response = $client->post('https://dashscope.aliyuncs.com/compatible-mode/v1/chat/completions', [ // 需确认 API 端点 ‌:ml-citation{ref="6" data="citationList"}
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'text/event-stream' // 指定流式响应格式 ‌:ml-citation{ref="5" data="citationList"}
                ],
                'json' => $data,
                'stream' => true // Guzzle 启用流式接收
            ]);

            // 逐块处理流式响应
            $stream = $response->getBody();
            //dump($stream->getContents());
            $buffer = '';
            while (!$stream->eof()) {
                $chunk = $stream->read(1024); // 读取数据块
                $lines = explode("\n\n", $chunk); // 按 SSE 协议分割事件
                foreach ($lines as $line) {
                    if (strpos($line, 'data:') === 0) {
                        $jsonData = trim(substr($line, 5)); // 提取 JSON 部分
                        $data = json_decode($jsonData, true);
                        //dump($data);
                        if (isset($data['choices'][0]['delta']['content'])) {
                            echo $data['choices'][0]['delta']['content'];
                        }elseif(isset($data['choices'][0]['delta']['reasoning_content'])){
                            echo $data['choices'][0]['delta']['reasoning_content'];
                        }
                        ob_flush(); // 实时输出到客户端
                        flush();
                    }
                }
            }

        } catch (RequestException $e) {
            echo '请求失败: ' . $e->getMessage();
        }
    }

    function qvqMax(){
        // 流式响应配置
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no');  // 禁用Nginx代理缓冲‌:ml-citation{ref="6" data="citationList"}
        ob_implicit_flush(true);
        ob_end_flush();

// 初始化cURL
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://dashscope.aliyuncs.com/compatible-mode/v1/chat/completions',
            CURLOPT_POST => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer sk-2d304fb9100046fc9eabef7a848a4ff4",
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode([
                "model" => "qvq-max",
                "messages" => [
                    [
                        "role" => "system",
                        "content" => [["type" => "text", "text" => "You are a helpful assistant."]]
                    ],
                    [
                        "role" => "user",
                        "content" => [
                            [
                                "type" => "image_url",
                                "image_url" => ["url" => "http://images.kuryun.com/1-67e954ff4c252.png"]
                            ],
                            ["type" => "text", "text" => "以[xxxx]格式返回这张图片上的四位数验证码"]
                        ]
                    ]
                ],
                "stream" => true,
                "stream_options" => ["include_usage" => true]
            ]),
            CURLOPT_WRITEFUNCTION => function ($ch, $data) {
                // 实时输出数据块‌:ml-citation{ref="2,6" data="citationList"}
                $data = trim($data, "\n\n");
                $lines = explode("\n\n", $data); // 按 SSE 协议分割事件
                foreach ($lines as $line) {
                    if (strpos($line, 'data:') === 0) {
                        $jsonData = trim(substr($line, 5)); // 提取 JSON 部分
                        $data = json_decode($jsonData, true);
                        if (isset($data['choices']['delta']['content'])) {
                            echo $data['choices']['delta']['content'];
                            //ob_flush(); // 实时输出到客户端
                            flush();
                        }
                    }
                }
                var_dump($data);
                flush();
                //echo $data;
                //flush();
                //return strlen($data);
            },
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 300  // 长连接超时设置‌:ml-citation{ref="3,6" data="citationList"}
        ]);

        curl_exec($ch);

        if (curl_errno($ch)) {
            // 错误处理保持流式特性‌:ml-citation{ref="5" data="citationList"}
            echo "data: [ERROR] " . curl_error($ch) . "\n\n";
            flush();
        }

        curl_close($ch);

    }

    public function index()
    {
        return response('hello thinkphp6');
    }

    function clearCache(){
        // 假设你的缓存配置标识符为cache_1
        $cache = get_redis();
        //dump(config('cache.stores')[config('cache.default')]['prefix']);exit;
        // 获取缓存的所有键
        $keys = $cache->keys(config('cache.stores')[config('cache.default')]['prefix'] . '*');
        // 遍历所有匹配的键，并删除它们
        foreach ($keys as $key) {
            dump($key);
            //$cache->del($key);
        }
    }
    function testAuth(){
        $checkToken = input('token', '');
        $data = "hfN3/Yx+anzdfMddTo+BdjkyWdNIz6z8YG6Z4P92dsTY5/6r4aNt1dRN5uGpVtx7Fl1zWz35Z6LI9OYwyhyRJySGHRwhPDPM7hIcK1zuOZPuiZB+bTuSShKWTY+ZM8j5wRG4OiyLwHX3q2PHtXwd/elpjWUs1JVSiRMy7HCZnUmsASJH3nfUqupH5j9E01w6jtVQ6shVtRgqm/Lg3E6/wjA4dmiDfIUFxBA62ghNUCh+pNmx7XNbx1/qRrZERfGSkkJ/PtGR6iVWgQS0aG41ZWJUBDjUxEcth+SQIhXJnR6EJHVze1fn/jCLeuU7sIgNRSlPb5H9W7Ahps0oR8hboA==";
        $pcid = 'D0DF70A3E9666164DE4FDD739B67F2A0BA4BF05596C8ABB434ECEB2BEB40048A';

        $client = new AutoAuth(['key' => '2B95B3BF370C8C09209E9909B1B6315737DABA14',
            'base_uri' => 'http://124.222.4.168:8203'
        ]);
        $client_token = 'd8b927447a96b2eae3cd43ce08a12df2';
        $params = [
            'app' => 1,
            'auth' => 'ls',
            'sid' => null,
            'wxid' => 'wxid_7v3b6hncdo6f12',
            'pcid' => $pcid,
            'data' =>  $data,
            'checkToken' => $checkToken
        ];
        $res = $client->auth($params);
        dump($res);
    }

    function testCheck(){
        $client = new AutoAuth(['key' => '2B95B3BF370C8C09209E9909B1B6315737DABA14',
            'base_uri' => 'http://124.222.4.168:8203'
        ]);
        $client_token = 'd8b927447a96b2eae3cd43ce08a12df2';
        $res = $client->check([
            'client_token' => $client_token,
            'index' => 1
        ]);
        dump($res);
    }

    function testGetCheckCode(){
        $client = new AutoAuth(['key' => '2B95B3BF370C8C09209E9909B1B6315737DABA14',
            'base_uri' => 'http://124.222.4.168:8203'
        ]);
        $client_token = md5(random_int(10, 10000));
        //dump($client_token);exit;
        $client_token = "dd26df30c64e6bd0efe9b5196f66d94f";
        $res = $client->getCheckCode(['client_token' => $client_token]);
        if(!empty($res['code']) && $res['code'] == 200 && !empty($res['data'])){
            $tries = 0;
            do{
                $res = $client->check([
                    'client_token' => $client_token,
                    'index' => 1
                ]);
                dump($res);
                sleep(random_int(3,5));
                $tries++;
            }while($res['code'] == 400 && $tries < 5);
            dump($res);
        }
    }

    function testPanther(){
        $client = new AutoAuth([
            'key' => 'A87CEE288D84730A082765755BBAECB3ED411133',
            'base_uri' => 'http://124.222.4.168:8203',
            'chromedriver' => '/usr/bin/chromedriver'
        ]);
        $res = $client->run();
        dump($res);
    }

    /**
     * https://packagist.org/packages/zjkal/time-helper
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testTimeHelper(){

        //判断指定日期是否为国内的工作日
        var_dump(ChinaHoliday::isWorkday('2024-01-10'));

        //判断指定日期是否为国内的节假日
        var_dump(ChinaHoliday::isHoliday(strtotime('2024-02-10')));
    }

    public function testVideo(){
        /*$parttern = "/http[s]?:\/\/(?:[a-zA-Z]|[0-9]|[$-_@.&+]|[!*\(\),]|(?:%[0-9a-fA-F][0-9a-fA-F]))+/";
        $content = "https://v.kuaishou.com/1WUK6C 这样的骗术你遇到过吗？一定要警惕！ 该作品在快手被播放过5,080.4万次，点击链接，打开【快手极速版】直接观看！";
        preg_match_all($parttern, $content, $matches);
        dump($matches);exit;*/
        $douyin_url = 'https://v.douyin.com/iJCGW2qT/';
        //$res = (new VideoSpider)->douyin($douyin_url);
        $xigua_url = 'https://v.ixigua.com/ieUR9nmW/';
        $xigua_url = "https://v.ixigua.com/ie5ypkNc/";
        $xiaohongshu_url = 'http://xhslink.com/zzSpxt';
        $kuaishou_url = 'https://v.kuaishou.com/1WUK6C';
        $kuaishou_url = 'https://v.kuaishou.com/5mphXc';
        $pipixia_url = 'https://h5.pipix.com/s/iJq6yrYX/';
        $weishi_url = 'https://video.weishi.qq.com/hzqN7rQt';
        $weibo_url = 'https://video.weibo.com/show?fid=1034:4934561977532466';
        $zuiyou_url = 'https://share.xiaochuankeji.cn/hybrid/share/post?pid=338456751&vid=2244242734';
        $lvzhou_url = "https://m.oasis.weibo.cn/v1/h5/share?sid=4907194933844490";
        $douyin_images = "https://v.douyin.com/hWrAWMd/";
        $douyin_url2 = "https://v.douyin.com/NrRah9w/";
        $douyin_url =
        $res = (new VideoSpider)->douyin($douyin_url);
        //$res = (new VideoSpider)->xigua($xigua_url);
        //$res = (new VideoSpider)->kuaishou($kuaishou_url);
        dump($res);exit;
    }

    function getVideoFromLink($link) {
        // 使用 file_get_contents() 函数获取页面内容
        $html = file_get_contents($link);

        // 使用正则表达式提取视频链接
        preg_match('/<meta property="og:video" content="(.*?)"/', $html, $matches);
        $videoLink = $matches[1];

        return $videoLink;
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
        // $msg = '[mp4=D:\weixinjilu\WeChat Files\wxid_5fprdytoi1k612\FileStorage\Video\2022-12\d97e4708ae1f7b3947c2d38c7c6976a8.mp4]';
        // $sub = 5;
        $msg = '[mp4=E:\weixinjilu\WeChat Files\wxid_3zmct0u931c522\FileStorage\Video\2023-03\5054599134b17483a67bd8f5975a2845.mp4]';

        $sub = 6;
        $bot_model = new Bot();
        $bot = $bot_model->where(['id' => 132])->find();
        dump($bot);
        dump($bot_model->getlastsql());
        $bot_client = $bot_model->getRobotClient($bot);
        $path = mb_substr($msg, $sub, -1);
        dump($path);
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        dump($ext);
        $file_name = substr(strrchr($path, "\\"), 1);
        dump($file_name);exit;
        $res = $bot_client->downloadFile(['path' => $path]);
        dump($res);
        $base64 = $res['ReturnStr'];
        $url = upload_base64('mp4_' . rand(1000, 9999) . '_' . time() . $file_name, $base64);
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
        $msg = (new ChatLog())->where(['id' => 420])->value('content');
        dump($msg);
        $convert = $this->convertShareLink($msg);
        dump($convert);
        exit;
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
        } else {
            preg_match('/<title>(.*?)<\/title>/ism', $msg, $title_res2);
            if (isset($title_res2[1])) {
                $title = $title_res[2];
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


    public function addFriend()
    {
        //         uin: wxid_5fprdytoi1k612
        // wxid: v3_020b3826fd03010000000000da49007ecff1d0000000501ea9a3dba12f95f6b60a0536a1adb6c5611a1f1a3700022df4a80f69c70d82b672be1adb5a9dc873492e572f9c6daed58594f4160913d24ab3b0ef27f1b35d27a17568512a2b7d62398577@stranger
        // bot_id: 54
        // msg: 假发教父：尚新假发创始人 郑启示
        $post_data = [
            'uin' => 'wxid_5fprdytoi1k612',
            'content' => '假发教父：尚新假发创始人 郑启示'
        ];
        $bot_model = new Bot();
        $bot = $bot_model->getOne(54);
        $bot_client = $bot_model->getRobotClient($bot);
        $account = $bot_client->searchAccount([
            'robot_wxid' => $post_data['uin'],
            'content' => $post_data['content']
        ]);
        dump($account);
        // $res = $bot_client->addFriendBySearch([
        //     'robot_wxid' => $post_data['uin'],
        //     'v1' => $post_data['wxid'],
        //     'msg' => $post_data['msg'],
        //     'scene' => Bot::SCENE_WXNUM,
        //     'type' => 1
        // ]);
    }


    public function botSend()
    {
        // {
        //     "sdkVer":6,
        //     "Event":"EventPrivateChat",
        //     "content":{
        //         "robot_wxid":"wxid_5fprdytoi1k612",
        //         "type":1,
        //         "from_wxid":"cengzhiyang4294",
        //         "from_name":"zengzhiyang",
        //         "msg":"123",
        //         "clientid":0,
        //         "robot_type":0,
        //         "msg_id":"7630416942713148939"
        //     }
        // }
        // {
        //     "sdkVer":6,
        //     "Event":"EventDeviceCallback",
        //     "content":{
        //         "robot_wxid":"wxid_3zmct0u931c522",
        //         "type":1,
        //         "msg":"123456",
        //         "to_wxid":"wxid_uzmmu9jzsvjn12",
        //         "to_name":"尚新假发 郑启示 新号",
        //         "clientid":0,
        //         "robot_type":0,
        //         "msg_id":"8770263085659840075"
        //     }
        // }
        $url = 'http://ty.sxwig.com:8181/bot/api/my';
        $params = [
            "sdkVer" => 6,
            "Event" => "EventDeviceCallback",
            "content" => [
                "robot_wxid" => "wxid_3zmct0u931c522",
                "type" => 1,
                "to_wxid" => "wxid_uzmmu9jzsvjn12",
                "to_name" => "尚新假发 郑启示 新号",
                "msg" => "123456",
                "clientid" => 0,
                "robot_type" => 0,
                "msg_id" => "8770263085659840075"
            ]
        ];

        $res = curl($url,json_encode($params));
        dump($res);
    }
}
