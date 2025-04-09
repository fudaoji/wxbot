<?php

/**
 * Created by PhpStorm.
 * Script Name: Qyk.php
 * Create: 2021/12/29 17:34
 * Description: 青云客聊天机器人
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\OpenAI\Driver;

use ky\Logger;
use ky\OpenAI\Base;

class Qianwen extends Base
{
    const API_AGENT = "/api/v1/apps/{APPID}/completion";
    const API_CHAT = '/compatible-mode/v1/chat/completions';
    const API_SMART_IMG = '/v1/images/generations';
    const API_EMBEDINGS = '/compatible-mode/v1/embeddings';
    protected $baseUri = 'https://dashscope.aliyuncs.com';
    protected $errMsg = '';
    protected $appKey = '';
    protected $proxy = '';
    protected $model = 'qwen-turbo';
    private $method = 'post';

    const MODEL_LIST = [
        "qwen-turbo" => "qwen-turbo"
    ];

    public function __construct($options = [])
    {
        parent::__construct($options);
        !empty($options['api_key']) && $this->appKey = $options['api_key'];
        !empty($options['proxy']) && $this->proxy = $options['proxy'];
        !empty($options['model']) && $this->model = $options['model'];
        !empty($options['agent_id']) && $this->agentId = $options['agent_id'];
    }

    /**
     * 理解图片
     * @param $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function readImg($params)
    {
        $message = [
            [
                'role' => 'user',
                "content" => [
                    ['type' => 'text', 'text' => $params['msg']],
                    ['type' => 'image_url', 'image_url' => $params['img']]
                ]
            ]
        ];

        $stream = empty($params['stream']) ? false : true;
        $model = $params['model'] ?? $this->model;
        $web_search = empty($params['web_search']) ? false : true;

        $payload = [
            'messages' => $message,
            'model' => $model,
            'stream' => $stream,
            'enable_search' => $web_search
        ];

        isset($params['stream_options']) && $payload['stream_options'] = $params['stream_options'];
        isset($params['temperature']) && $payload['temperature'] = $params['temperature'];
        isset($params['top_p']) && $payload['top_p'] = $params['top_p'];
        isset($params['presence_penalty']) && $payload['presence_penalty'] = $params['presence_penalty'];
        isset($params['response_format']) && $payload['response_format'] = $params['response_format'];
        isset($params['max_tokens']) && $payload['max_tokens'] = $params['max_tokens'];
        isset($params['n']) && $payload['n'] = $params['n'];
        isset($params['seed']) && $payload['seed'] = $params['seed'];
        isset($params['stop']) && $payload['stop'] = $params['stop'];
        isset($params['tools']) && $payload['tools'] = $params['tools'];
        isset($params['tool_choice']) && $payload['tool_choice'] = $params['tool_choice'];
        isset($params['parallel_tool_calls']) && $payload['parallel_tool_calls'] = $params['parallel_tool_calls'];
        isset($params['search_options']) && $payload['search_options'] = $params['search_options'];

        return $this->doRequest($payload, self::API_CHAT);
    }

    /**
     * 文本向量化
     * @param $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function embedding($params)
    {
        $payload = [
            'model' => $params['model'] ?? $this->model,
            'input' => $params['msg'],
            'encoding_format' => $params['encoding_format'] ?? 'float'
        ];

        return $this->doRequest($payload, self::API_EMBEDINGS);
    }

    /**
     * 根据参数自动判断是使用chat还是agent
     * @param $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @return array
     */
    public function smart($params){
        $app_id = $params['agent_id'] ?? $this->agentId;
        return $app_id ? $this->agent($params) : $this->chat($params);
    }

    /**
     * 智能体对话
     * @param $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function agent($params)
    {
        $message = [];
        if (!empty($params['background'])) {
            array_push($message, ['role' => 'system', 'content' => $params['background']]);
        }

        if (!empty($params['context'])) {
            $message = array_merge_recursive($message, $params['context']);
        }
        array_push($message, ['role' => 'user', 'content' => $params['msg']]);

        $stream = empty($params['stream']) ? false : true;

        $input = [
            'messages' => $message
        ];

        $parameters = [];
        $debug = [];
        $headers = [];
        !empty($params['session_id']) && $input['session_id'] = $params['session_id'];
        !empty($params['biz_params']) && $input['biz_params'] = $params['biz_params'];
        !empty($params['memory_id']) && $input['memory_id'] = $params['memory_id'];
        !empty($params['image_list']) && $input['image_list'] = $params['image_list'];
        !empty($params['workspace']) && $headers['X-DashScope-WorkSpace'] = $params['workspace'];
        !empty($params['has_thoughts']) && $parameters['has_thoughts'] = $params['has_thoughts'];
        !empty($params['rag_options']) && $parameters['rag_options'] = $params['rag_options'];

        if($stream){
            $headers['X-DashScope-SSE'] = 'enable';
            $parameters['incremental_output'] = $params['incremental_output'] ?? false;
            !empty($params['flow_stream_mode']) && $parameters['flow_stream_mode'] = $params['flow_stream_mode'];//flow_stream_mode
        }

        $payload = [
            'input' => $input,
            "debug" => $debug
        ];
        !empty($parameters) && $payload['parameters'] = $parameters;
        $api = str_replace("{APPID}", $this->agentId, self::API_AGENT);
        return $this->doRequest($payload, $api, $headers);
    }

    /**
     * 普通对话
     * @param $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function chat($params)
    {
        $message = [];
        if (!empty($params['background'])) {
            array_push($message, ['role' => 'system', 'content' => $params['background']]);
        }

        if (!empty($params['context'])) {
            $message = array_merge_recursive($message, $params['context']);
        }
        array_push($message, ['role' => 'user', 'content' => $params['msg']]);

        $stream = empty($params['stream']) ? false : true;
        $web_search = empty($params['web_search']) ? false : true;

        $payload = [
            'messages' => $message,
            'model' => $this->model,
            'stream' => $stream,
            'enable_search' => $web_search
        ];

        isset($params['stream_options']) && $payload['stream_options'] = $params['stream_options'];
        isset($params['max_tokens']) && $payload['max_tokens'] = $params['max_tokens'];
        isset($params['presence_penalty']) && $payload['presence_penalty'] = $params['presence_penalty'];
        isset($params['frequency_penalty']) && $payload['frequency_penalty'] = $params['frequency_penalty'];
        isset($params['temperature']) && $payload['temperature'] = $params['temperature'];
        isset($params['response_format']) && $payload['response_format'] = $params['response_format'];
        isset($params['top_p']) && $payload['top_p'] = $params['top_p'];
        isset($params['top_k']) && $payload['top_k'] = $params['top_k'];
        isset($params['tools']) && $payload['tools'] = $params['tools'];
        isset($params['tool_choice']) && $payload['tool_choice'] = $params['tool_choice'];
        isset($params['stop']) && $payload['stop'] = $params['stop'];
        isset($params['seed']) && $payload['seed'] = $params['seed'];
        isset($params['n']) && $payload['n'] = $params['n'];
        //dump($payload);exit;
        return $this->doRequest($payload, self::API_CHAT);
    }

    private function doRequest($params = [], $api = '', $headers = [])
    {
        $options = [
            'url' => $api,
            'method' => $this->method,
            'headers' => array_merge(["Authorization" => "Bearer " . $this->appKey], $headers),
            'proxy' => $this->proxy,
        ];
        !empty($params) && $options['data'] = $params;
        //dump($options);exit;
        //Logger::error(json_encode($options, JSON_UNESCAPED_UNICODE));
        return $this->request($options);
    }

    public function errors($code = 200)
    {
        $list = [
            401 => '获取token失败',
            404 => '接口路径与请求方式错误',
            429 => '接口请求频率超过限制',
            500 => '服务端错误'
        ];
        $this->errMsg = isset($list[$code]) ? ($code . ':' . $list[$code]) : ($code . ':未知错误');
    }

    public function dealRes($params, $url)
    {
        $res = [
            'code' => 1,
            'answer' => '',
            'answer_type' => self::ANSWER_TEXT
        ];
        if(strpos($url, "/api/v1/apps/") !== false){
            if (!empty($params['output']['text'])) {
                $res['answer'] = $params['output']['text'];
            } else {
                $res['code'] = 0;
                $res['errmsg'] = $params['message'] ?? "出错啦！";
            }
        }elseif(strpos($url, self::API_CHAT) !== false){
            if (!empty($params['choices'][0]['message']['content'])) {
                $res['answer'] = $params['choices'][0]['message']['content'];
                $res['usage'] = $params['usage'];
            } else {
                $res['code'] = 0;
                $res['errmsg'] = $params['error']['message'] ?? "出错啦！";
            }
        }elseif (strpos($url, self::API_EMBEDINGS) !== false){
            $res = array_merge($res, $params);
        }
        return $res;
    }
}
