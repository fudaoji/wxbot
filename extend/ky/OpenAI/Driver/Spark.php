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
    const HOST_WORKFLOW = 'xingchen-api.xf-yun.com';
    const HOST_TEXT = 'spark-api.xf-yun.com';
    const HOST_CHAT = 'https://spark-api-open.xf-yun.com';
    const HOST_GENERATE_IMG = 'spark-api.cn-huabei-1.xf-yun.com';
    const HOST_READ_IMG = 'spark-api.cn-huabei-1.xf-yun.com';
    const HOST_EMB = 'emb-cn-huabei-1.xf-yun.com';
    const HOST_CHATDOC = 'chatdoc.xfyun.cn';
    const API_CHAT = '/v1/chat/completions';
    const API_WORKFLOW = '/workflow/v1/chat/completions';
    const API_CHATDOC = '/openapi/chat';
    const API_READ_IMG = '/v2.1/image';
    const API_GENERATE_IMG = '/v2.1/tti';
    const API_LITE = '/v1.1/chat';
    const API_PRO = '/v3.1/chat';
    const API_PRO_128 = '/chat/pro-128k';
    const API_MAX = '/v3.5/chat';
    const API_MAX_32 = '/chat/max-32k';
    const API_ULTRA = '/v4.0/chat';
    const MODEL_LITE = 'lite';
    const MODEL_PRO = 'pro';
    const MODEL_PRO_128 = 'pro_128';
    const MODEL_MAX = 'max';
    const MODEL_MAX_32 = 'max_32';
    const MODEL_ULTRA_4 = 'ultra_4';
    const MODEL_KJWX = 'kjwx';//kjwx

    protected $errMsg = '';
    protected $apiKey = '';
    protected $model = 'lite';
    private $method = 'post';
    private $apiSecret;
    private $domains = [
        self::MODEL_LITE => 'lite',
        self::MODEL_PRO => 'generalv3',
        self::MODEL_PRO_128 => 'pro-128k',
        self::MODEL_MAX => 'generalv3.5',
        self::MODEL_MAX_32 => 'max-32k',
        self::MODEL_ULTRA_4 => '4.0Ultra',
        self::MODEL_KJWX => 'kjwx'
    ];

    const MODEL_LIST = [
        self::MODEL_LITE => 'Lite',
        self::MODEL_PRO => 'Pro',
        self::MODEL_PRO_128 => 'Pro-128K',
        self::MODEL_MAX => 'Max',
        self::MODEL_MAX_32 => 'Max-32k',
        self::MODEL_ULTRA_4 => '4.0 Ultra',
        self::MODEL_KJWX => 'kjwx'
    ];

    /**
     * @var string
     * 指定访问的模型版本:
    lite 指向Lite版本;
    generalv3 指向Pro版本;
    pro-128k 指向Pro-128K版本;
    generalv3.5 指向Max版本;
    max-32k 指向Max-32K版本;
    4.0Ultra 指向4.0 Ultra版本;
    kjwx 指向科技文献大模型（重点优化论文问答、写作等垂直领域）;
     */
    private $domain = 'lite';
    private $apiPassword = '';

    public function __construct($options = [])
    {
        parent::__construct($options);
        !empty($options['api_key']) && $this->apiKey = $options['api_key'];
        !empty($options['api_secret']) && $this->apiSecret = $options['api_secret'];
        !empty($options['api_password']) && $this->apiPassword = $options['api_password'];
        !empty($options['agent_id']) && $this->agentId = $options['agent_id'];
        !empty($options['appid']) && $this->appId = $options['appid'];
        !empty($options['model']) && $this->model = $options['model'];
    }

    /**
     * 根据参数自动判断是使用chat还是agent
     * @param $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @return array
     */
    public function smart($params){
        $flow_id = $params['agent_id'] ?? $this->agentId;
        return $flow_id ? $this->agent($params) : $this->chat($params);
    }

    /**
     * 大模型对话
     * @param $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function chat($params){
        $message = [];
        if(! empty($params['background'])){
            array_push($message, ['role' => 'system', 'content' => $params['background']]);
        }
        if(! empty($params['context'])){
            $message = array_merge_recursive($message, $params['context']);
        }

        array_push($message, ['role' => 'user', 'content' => $params['msg']]);

        $this->baseUri = self::HOST_CHAT;
        $stream = empty($params['stream']) ? false : true;
        $web_search = empty($params['web_search']) ? false : true;
        $tools = [
            [
                'type' => 'web_search',
                'web_search' => [
                    'enable' => $web_search
                ]
            ]
        ];

        !empty($params['tools']) && $tools = array_merge($tools, $params['tools']);
        $payload = [
            'model' => $this->model,
            'user' => $params['userid'] ?? '',
            'messages' => $message,
            'stream' => $stream,
            'tools' => $tools
        ];

        isset($params['response_format']) && $payload['response_format'] = $params['response_format'];
        isset($params['suppress_plugin']) && $payload['suppress_plugin'] = $params['suppress_plugin'];
        isset($params['max_tokens']) && $payload['max_tokens'] = $params['max_tokens'];
        isset($params['presence_penalty']) && $payload['presence_penalty'] = $params['presence_penalty'];
        isset($params['frequency_penalty']) && $payload['frequency_penalty'] = $params['frequency_penalty'];
        isset($params['temperature']) && $payload['temperature'] = $params['temperature'];
        isset($params['top_p']) && $payload['top_p'] = $params['top_p'];
        isset($params['top_k']) && $payload['top_k'] = $params['top_k'];
        $options = [
            'url' => self::API_CHAT,
            'data' => $payload,
            'headers' => ["Authorization" => "Bearer ".$this->apiPassword]
        ];
        //Logger::error($options);
        $res = $this->request($options);
        if(!empty($res['choices'][0]['message']['content'])){
            $res['answer_type'] = self::ANSWER_TEXT;
            $res['code'] = 1;
            $res['answer'] = $res['choices'][0]['message']['content'];
        }else{
            $res = [
                'code' => 0,
                'errmsg' => $res['error']['message'] ?? ''
            ];
        }
        return $res;
    }

    /**
     * 智能体调用
     * @param $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function agent($params){
        $this->baseUri = 'https://' . self::HOST_WORKFLOW;
        $stream = empty($params['stream']) ? false : true;

        $payload = [
            "flow_id" => $params['agent_id'] ?? $this->agentId,
            "uid" => $params['userid'] ?? '',
            "parameters" => [
                "AGENT_USER_INPUT" => $params['msg']
            ],
            'stream' => $stream,
        ];

        $options = [
            'url' => self::API_WORKFLOW,
            'data' => $payload,
            'headers' => ["Authorization" => "Bearer {$this->apiKey}:{$this->apiSecret}"]
        ];
        //Logger::error($options);
        $res = $this->request($options);
        if($res['code'] == 0 && !empty($res['choices'][0]['delta']['content'])){
            $res['answer_type'] = self::ANSWER_TEXT;
            $res['code'] = 1;
            $res['answer'] = $res['choices'][0]['delta']['content'];
        }else{
            $res = [
                'code' => 0,
                'errmsg' => $res['error']['message'] ?? ''
            ];
        }
        return $res;
    }

    /**
     * {
    "header": {
    "app_id": appid,
    "uid": "39769795890",
    "status": 3,
    },
    "parameter": {
    "emb": {
    "domain": "query" # 可选值：query 和para
    "feature": {
    "encoding": "utf8",
    "compress": "raw",
    "format": "plain"
    }
    }
    },
    "payload": {
    "messages": {
    "encoding": "utf8",
    "compress": "raw",
    "format": "json",
    "status": 3,
    "text": ""
    }
    }
    }
     * @param $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function emb($params){
        $header = [
            'status' => 3
        ];
        $parameter = [
            "emb" => [
                "domain" => $params['domain'] ?? "query", # 可选值：query 和para
                "feature" => [
                    "encoding" => "utf8",
                    "compress" =>  "raw",
                    "format" => "plain"
                ]
            ]
        ];
        $message = [
            'text' => base64_encode(json_encode($params['msg']))
        ];
        $data = $this->createMsg(['messages' => $message], $parameter, $header);
        $this->baseUri = 'https://'.self::HOST_EMB;
        //Logger::error($data);
        $res = $this->httpRequest($data, '/', 'emb');
        //Logger::error($res);
        return  $res;
    }

    /**
     * 知识库问答
     * https://chatdoc.xfyun.cn/openapi/chat?appId=xxx&timestamp=xxx&signature=xxxxxx
     * @req:{
            "chatExtends": {
                "wikiPromptTpl": "请将以下内容作为已知信息：\n<wikicontent>\n请根据以上内容回答用户的问题。\n问题:<wikiquestion>\n回答:",
                "wikiFilterScore": 0.82,
                "temperature": 0.5,
                "spark": true
            },
            "topN: 5,  //向知识库查询数量
             "repoId: "知识库id",  //repoId和fileIds 二选一必填
            "fileIds": ["8b1b17171212121212114fd0806"],
            "messages": [
                {
                    "role": "user",
                    "content": "如何理赔"
                }
            ]
        }
     * @param $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function chatDoc($params){
        $time = time();
        // Base64编码后返回
        $generate_signature = function () use ($time){
            $auth = md5($this->appId . $time);
            // 生成HMAC-SHA1二进制数据
            $rawHmac = hash_hmac('sha1', $auth, $this->apiSecret, true);
            return base64_encode($rawHmac);
        };
        $signature = $generate_signature();

        $this->baseUri = "wss://".self::HOST_CHATDOC;
        $api = self::API_CHATDOC . "?appId={$this->appId}&timestamp={$time}&signature={$signature}";

        $messages = [];
        $wikiPromptTpl = "";
        if(! empty($params['background'])){
            $wikiPromptTpl = $params['background'];
        }
        if(! empty($params['context'])){
            $messages = array_merge_recursive($messages, $params['context']);
        }

        array_push($messages, ['role' => 'user', 'content' => $params['msg']]);
        $data = [
            "chatExtends" =>  [
                "wikiPromptTpl" => $wikiPromptTpl,
                //"wikiFilterScore" => 0.82,
                //"temperature" => 0.5,
                "spark" => true
            ],
            "topN" => 5,  //向知识库查询数量
            "repoId" => $params['repo_id'],  //repoId和fileIds 二选一必填
            //"fileIds" => ["936f3e0e92e64522bf5e64befb0c2e5a"],
            "messages" => $messages
        ];
        $client = new Client($this->baseUri . $api);
        try {
            $client->send(json_encode($data, true));
            $response = $client->receive();

            $response_arr = json_decode($response, true);
            $content = [];
            // 科大讯飞会分多次发送消息
            do {
                if ($response_arr['code'] != '0') {
                    throw  new \Exception($response_arr['message']); //错误
                    break;
                }

                $content[] = $response_arr['content'] ?? '';

                if ($response_arr['status'] == 2) {
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
            dump($e->getMessage());
            return [
                'code' => 0,
                'errmsg' => $e->getMessage(),
            ];
        } finally {
            $client->close();
        }
        //dump($api);exit;
        /*$res = $this->wssRequest([
            'headers' => [
                "appId" => $this->appId,
                "timestamp" => $time,
                "signature" => $signature
            ],
            'data' => $data,
            'url' => $api
        ]);
        //Logger::error($res);
        return  $res;*/
    }

    /**
     * 图片理解
     * {
    "header": {
    "app_id": "123456",
    "uid": "39769795890"
    },
    "parameter": {
    "chat": {
    "domain": "general",
    "temperature": 0.5,
    "top_k": 4,
    "max_tokens": 2028
    }
    },
    "payload": {
    "message": {
    "text": [
    {
    "role": "user",
    "content": "base64",
    "content_type": "image"
    },
    {
    "role": "user",
    "content": "这张图片是什么内容",
    "content_type": "text"
    }
    ]
    }
    }
    }
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

        $message = ['text' => $message];
        $parameter = ['chat' => [
            'domain' => 'general',
            "top_k" => 4,
            "max_tokens" => 8192
        ]];
        $data = $this->createMsg($message, $parameter);
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
     * {
    "header": {
    "app_id": "your_appid"
    },
    "parameter": {
    "chat": {
    "domain": "general",
    "width": 512,
    "height": 512
    }
    },
    "payload": {
    "message": {
    "text": [
    {
    "role": "user",
    "content": "帮我画一座山"
    }
    ]
    }
    }
    }
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function generateImg($params = []){
        $message = [
            'text' => ['role' => 'user', 'content' => $params['msg']]
        ];
        $parameter = [
            'chat' => [
                'domain' => 'general',
                'width' => 512,
                'height' => 512
            ]
        ];
        $data = $this->createMsg($message, $parameter);
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
     * # 参数构造示例如下
    {
    "header": {
    "app_id": "12345",
    "uid": "12345"
    },
    "parameter": {
    "chat": {
    "domain": "lite",
    "temperature": 0.5,
    "max_tokens": 1024,
    }
    },
    "payload": {
    "message": {
    # 如果想获取结合上下文的回答，需要开发者每次将历史问答信息一起传给服务端，如下示例
    # 注意：text里面的所有content内容加一起的tokens需要控制在8192以内，开发者如有较长对话需求，需要适当裁剪历史信息
    "text": [
    #如果传入system参数，需要保证第一条是system
    {"role":"system","content":"你现在扮演李白，你豪情万丈，狂放不羁；接下来请用李白的口吻和用户对话。"} #设置对话背景或者模型角色
    {"role": "user", "content": "你是谁"} # 用户的历史问题
    {"role": "assistant", "content": "....."}  # AI的历史回答结果
    # ....... 省略的历史对话
    {"role": "user", "content": "你会做什么"}  # 最新的一条问题，如无需上下文，可只传最新一条问题
    ]
    }
    }
    }
     * @param $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function smart1($params){
        $message = [];
        if(! empty($params['background'])){
            array_push($message, ['role' => 'system', 'content' => $params['background']]);
        }
        if(! empty($params['context'])){
            $message = array_merge_recursive($message, $params['context']);
        }

        array_push($message, ['role' => 'user', 'content' => $params['msg']]);
        //Logger::error($message);
        $message = ['text' => $message];
        $parameter = [
            'chat' => [
                'domain' => $this->domains[$this->model],
                'max_tokens' => 4096
            ]
        ];
        $_header = [];
        !empty($params['userid']) && $_header['uid'] = $params['userid'];
        $data = $this->createMsg($message, $parameter, $_header);
        //Logger::error($data);

        $res = $this->wssRequest($data, $this->model);
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
            dump($e->getMessage());
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
     * @param array $_parameter
     * @param array $_header
     * @return array
     */
    private function createMsg($message = [], $_parameter = [], $_header = [])
    {
        //Logger::error($message);
        $parameter = [
            /*'chat' => [
                "domain"=> "general",
                "temperature"=> 0.5,
                "max_tokens"=> 4096,
            ]*/
        ];

        if(!empty($_parameter)){
            $parameter = array_merge($parameter, $_parameter);
        }

        $header = [
            'app_id' => $this->appId,
        ];
        if(!empty($_header)){
            $header = array_merge($header, $_header);
        }

        if(isset($message['messages'])){
            $payload = $message;
        }else{
            $payload = !isset($message['message']) ? [
                "message" => $message
            ] : $message;
        }
        //var_dump($payload);exit;
        return [
            'header' => $header,
            'parameter' => $parameter,
            'payload' => $payload,
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
            case 'emb':
                $host = self::HOST_EMB;
                $url = $uri;
                $method = strtoupper('post');
                break;
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
            case self::MODEL_PRO:
                $host = self::HOST_TEXT;
                $uri = self::API_PRO;
                $url = 'wss://'.$host.$uri;
                $method = strtoupper('get');
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
     * "header": {
     * "code": 0,
     * "message": "Success",
     * "sid": "cht000704fa@dx16ade44e4d87a1c802",
     * "status": 0
     * },
     * "payload": {
     * "choices": {
     * "status": 2,
     * "seq": 0,
     * "text": [
     * {
     * "content": "base64",
     * "index": 0,
     * "role": "assistant"
     * }
     * ]
     * }
     * }
     * }
     * @param $res
     * Author: fudaoji<fdj@kuryun.cn>
     * @param string $url
     * @return array
     */
    public function dealRes($res, $url = '')
    {
        $return = [];
        if (strpos($url, self::HOST_CHAT) !== false || strpos($url, self::HOST_WORKFLOW) !== false){
            return  $res;
        }else{
            if($res['header']['code'] == 0){
                $return['code'] = 1;
                $return['payload'] = $res['payload'];
            }else{
                $return['code'] = 0;
                $return['errmsg'] = $res['header']['message'];
            }
        }
        return $return;
    }
}
