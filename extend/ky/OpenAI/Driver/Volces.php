<?php

/**
 * Created by PhpStorm.
 * Script Name: Volces.php
 * Create: 2025/2/17 17:34
 * Description: 火山方舟
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\OpenAI\Driver;

use ky\Logger;
use ky\OpenAI\Base;

class Volces extends Base
{
    const API_CHAT = '/api/v3/chat/completions';
    const API_AGENT = '/api/v3/bots/chat/completions';
    const API_EMBEDINGS = '/api/v3/embeddings';
    protected $baseUri = 'https://ark.cn-beijing.volces.com';
    protected $errMsg = '';
    protected $appKey = '';
    protected $proxy = '';
    protected $model = '';
    private $method = 'post';
    private $useContext = true;

    public function __construct($options = [])
    {
        parent::__construct($options);
        !empty($options['api_key']) && $this->appKey = $options['api_key'];
        !empty($options['model']) && $this->model = $options['model'];
        isset($options['use_context']) && $this->useContext = $options['use_context'];
        !empty($options['agent_id']) && $this->agentId = $options['agent_id'];
    }

    /**
     * 文本向量化
     * @param $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function embedding($params){
        $data = [
            'input' => $params['msg'],
            'model' => $params['model'] ?? $this->model,
            'encoding_format' => $params['encoding_format'] ?? 'base64'
        ];

        $res = $this->doRequest($data, self::API_EMBEDINGS);
        $res['code'] = 1;
        if (empty($res['data'])) {
            $res['code'] = 0;
        }
        return  $res;
    }

    /**
     * 智能聊天,根据是否传递appid决定使用普通对话还是调用智能体
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

        $data = [
            'messages' => $message,
            'model' => $this->model,
        ];

        $agent_id = $params['agent_id'] ?? $this->agentId;
        if($agent_id){
            $api = self::API_AGENT;
            $data['model'] = $agent_id;
        }else{
            $api = self::API_CHAT;
        }

        $res = $this->doRequest($data, $api);
        return  $res;
    }

    private function doRequest($params = [], $api = '')
    {
        $options = [
            'url' => $api,
            'method' => $this->method,
            'headers' => ["Authorization" => "Bearer " . $this->appKey],
            //'proxy' => $this->proxy,
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

    public function dealRes($params, $url = '')
    {
        $res = [
            'code' => 1,
            'answer' => '',
            'answer_type' => self::ANSWER_TEXT
        ];
        if(strpos($url, self::API_CHAT) !== false || strpos($url, self::API_AGENT) !== false){
            if (!empty($params['choices'])) {
                $text = isset($params['choices'][0]['message']['content']) ? $params['choices'][0]['message']['content'] : '';
                $res['answer'] = trim(str_replace("<br/>", "\n", $text), "\n");
            } else {
                $res['code'] = 0;
            }
        }else{
            $res = $params;
        }
        return $res;
    }
}
