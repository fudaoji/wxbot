<?php
/**
 * Created by PhpStorm.
 * Script Name: Spark.php
 * Create: 2024/5/23 8:23
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\OpenAI\Driver;

use ky\Logger;
use ky\OpenAI\Base;
use think\Exception;
use WebSocket\Client;

class Spark extends Base
{

    const HOST_TEXT = 'spark-api.xf-yun.com';
    const HOST_GENERATE_IMG = 'spark-api.cn-huabei-1.xf-yun.com';
    const HOST_READ_IMG = 'spark-api.cn-huabei-1.xf-yun.com';
    const API_READ_IMG = '/v2.1/image';
    const API_GENERATE_IMG = '/v2.1/tti';
    const API_LITE = '/v1.1/chat';
    //const API_CHECK_KEY = '/fc/verify-key?key=';
    //protected $baseUri = 'https://api.aigcfun.com';
    protected $errMsg = '';
    protected $apiKey = '';
    protected $model = 'gpt-3.5-turbo';
    private $method = 'post';
    private $apiSecret;

    public function __construct($options = [])
    {
        parent::__construct($options);
        !empty($options['api_key']) && $this->apiKey = $options['api_key'];
        !empty($options['api_secret']) && $this->apiSecret = $options['api_secret'];
        !empty($options['appid']) && $this->appId = $options['appid'];
    }

    /**
     * 图片理解
     * @param $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function readImg($params){
        if(strpos($params['img'], 'http') !== false){
            $params['img'] = base64_encode(file_get_contents($params['img']));
        }else{
            $params['img'] = str_replace(['data:image/jpeg;base64,', 'data:image/png;base64,', ' '],'', $params['img']);
        }
        $message = [
            ['role' => 'user', 'content' => $params['img'], 'content_type' => 'image'],
            ['role' => 'user', 'content' => $params['msg']],
        ];

        $data = $this->createMsg($message, [
            "top_k" => 4,
            "auditing" => "strict"
        ]);
        //Logger::error($data);
        $res = $this->wssRequest($data, 'read_img');
        if(!empty($res['code'])){
            $res['answer_type'] = self::ANSWER_TEXT;
        }
        //Logger::error($res);
        return  $res;
    }

    /**
     * 文生图
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function generateImg($params = []){
        $chat = [
            'width' => 512,
            'height' => 512
        ];
        $message = [
            ['role' => 'user', 'content' => $params['msg']]
        ];
        $data = $this->createMsg($message, $chat);
        $this->baseUri = 'https://'.self::HOST_GENERATE_IMG;
        $res = $this->httpRequest($data, self::API_GENERATE_IMG, 'generate_img');
        if(!empty($res['code'])){
            $res['answer_type'] = self::ANSWER_IMAGE;
            $res['answer'] = $res['payload']['choices']['text'][0]['content'] ?? '';
            unset($res['payload']);
        }
        return $res;
    }

    private function httpRequest($params = [], $api = '', $model = 'lite'){
        $options = [
            'url' => $this->createUrl($model, $api),
        ];
        !empty($params) && $options['data'] = $params;
        return $this->request($options);
    }

    /**
     * lite模型
     * @param $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function smart($params){
        $week = [
            0 => '周日',
            1 => '周一',
            2 => '周二',
            3 => '周三',
            4 => '周四',
            5 => '周五',
            6 => '周六'
        ];

        $message = [
            //['role' => 'system', 'content' => "你是一个智能助手assistant"],
            ['role' => 'system', 'content' => "请务必记住，今天是".$week[date('w')]."，日期是" . date("Y-m-d") . "，现在时间是" . date("H:i").'。有人问你时，请基于我给你的时间进行测算。'],
        ];
        if(! empty($params['content_rule'])){
            array_push($message, ['role' => 'system', 'content' => $params['content_rule']]);
        }
        if(! empty($params['background'])){
            array_push($message, ['role' => 'system', 'content' => $params['background']]);
        }
        if(! empty($params['context'])){
            $message = array_merge_recursive($message, $params['context']);
        }

        array_push($message, ['role' => 'user', 'content' => $params['msg']]);

        $data = $this->createMsg($message);
        //Logger::error($data);
        $res = $this->wssRequest($data, 'lite');
        if(!empty($res['code'])){
            $res['answer_type'] = self::ANSWER_TEXT;
        }
        //Logger::error($res);
        return  $res;
    }

    /**
     * 调用科大讯飞星火认知模型
     * @param $message
     * @param $model
     * @return array
     */
    private function wssRequest($message, $model = 'lite')
    {
        //拼接链接
        $url = $this->createUrl($model);
        $client = new Client($url);
        try {
            $client->send(json_encode($message, true));
            $response = $client->receive();

            $response_arr = json_decode($response, true);
            $content = [];
            // 科大讯飞会分多次发送消息
            do {
                if ($response_arr['header']['code'] != '0') {
                    throw  new \Exception($response_arr['header']['message']); //错误
                    break;
                }

                $content[] = $response_arr['payload']['choices']['text'][0]['content'] ?? '';

                if ($response_arr['header']['status'] == 2) {
                    //echo 'data: [DONE]'."\n\n"; //结束
                    break;
                }
                //继续接收消息
                $response = $client->receive();
                $response_arr = json_decode($response, true);
            } while (true);
            $content = implode('', $content);

            return [
                'code' => 1,
                'answer' => $content,
                'msg' => 'success',
            ];
        } catch (\Exception $e) {
            return [
                'code' => 0,
                'errmsg' => $e->getMessage(),
            ];
        } finally {
            $client->close();
        }
    }

    /**
     * 生成要发送的消息体
     * @param array $message
     * @param array $chat
     * @return array
     */
    private function createMsg($message = [], $chat = [])
    {
        //Logger::error($message);
        $_chat = array_merge([
            "domain"=> "general",
            "temperature"=> 0.5,
            "max_tokens"=> 4096,
        ], $chat);

        return [
            'header' => [
                'app_id' => $this->appId,
            ],
            'parameter' => [
                "chat"=> $_chat
            ],
            'payload' => [
                "message"=> [
                    "text"=> $message
                ]
            ],
        ];
    }

    /**
     * 拼接签名
     * @param string $host
     * @param string $api
     * @param string $time
     * @return string
     */
    private function sign($host = self::HOST_TEXT, $api = self::API_LITE, $time='', $method = 'GET')
    {
        $signature_origin = 'host: '.$host. "\n";
        $signature_origin .= 'date: ' . $time . "\n";
        $signature_origin .= $method.' '.$api.' HTTP/1.1';

        $signature_sha = hash_hmac('sha256', $signature_origin, $this->apiSecret, true);
        $signature_sha = base64_encode($signature_sha);
        $authorization_origin = 'api_key="' . $this->apiKey . '", algorithm="hmac-sha256", ';
        $authorization_origin .= 'headers="host date request-line", signature="' . $signature_sha . '"';
        $authorization = base64_encode($authorization_origin);
        return $authorization;
    }

    /**
     * 生成Url
     * @param string $model
     * @param string $uri
     * @return string
     */
    private function createUrl($model = 'lite', $uri = self::API_LITE)
    {
        switch ($model){
            case 'read_img':
                $host = self::HOST_READ_IMG;
                $uri = self::API_READ_IMG;
                $url = 'wss://'.$host.$uri;
                $method = strtoupper('get');
                break;
            case 'generate_img':
                $host = self::HOST_GENERATE_IMG;
                $url = $uri;
                $method = strtoupper('post');
                break;
            default:
                $host = self::HOST_TEXT;
                $url = 'wss://'.$host.$uri;
                $method = strtoupper('get');
        }

        $time = gmdate('D, d M Y H:i:s') . ' GMT';
        $authorization = $this->sign($host, $uri, $time, $method);
        $url .= '?' . 'authorization=' . $authorization . '&date=' . urlencode($time) . '&host='.$host;
        return $url;
    }

    /**
     * $res:{
        "header": {
            "code": 0,
            "message": "Success",
            "sid": "cht000704fa@dx16ade44e4d87a1c802",
            "status": 0
        },
        "payload": {
            "choices": {
                "status": 2,
                "seq": 0,
                "text": [
                    {
                        "content": "base64",
                        "index": 0,
                        "role": "assistant"
                    }
                ]
            }
        }
    }
     * @param $res
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function dealRes($res)
    {
        $return = [];
        if($res['header']['code'] == 0){
            $return['code'] = 1;
            $return['payload'] = $res['payload'];
        }else{
            $return['code'] = 0;
            $return['errmsg'] = $res['header']['message'];
        }
        return $return;
    }
}
