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
    const API_SMART = '/compatible-mode/v1/chat/completions';
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
     * 智能聊天
     * @param $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function smart($params)
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
        return $this->doRequest($payload, self::API_SMART);
    }

    private function doRequest($params = [], $api = '')
    {
        $options = [
            'url' => $api,
            'method' => $this->method,
            'headers' => ["Authorization" => "Bearer " . $this->appKey],
            'proxy' => $this->proxy,
        ];
        !empty($params) && $options['data'] = $params;
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
        if(strpos($url, self::API_SMART) !== false){
            if (!empty($params['choices'][0]['message']['content'])) {
                $res['answer'] = $params['choices'][0]['message']['content'];
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
