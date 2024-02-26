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
    const API_SMART = '/api/v1/services/aigc/text-generation/generation';
    const API_SMART_IMG = '/v1/images/generations';
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
        !empty($options['appid']) && $this->appKey = $options['appid'];
        !empty($options['proxy']) && $this->proxy = $options['proxy'];
        !empty($options['model']) && $this->model = $options['model'];
    }

    /**
     * 智能聊天
     * @param $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function smart($params)
    {
        $role = "你是一个智能助手assistant";
        !empty($params["role_context"]) && $role = $params["role_context"];

        $message = [
            ['role' => 'system', 'content' => $role]
        ];
        if (!empty($params['content_rule'])) {
            array_push($message, ['role' => 'system', 'content' => $params['content_rule']]);
        }
        if (!empty($params['background'])) {
            array_push($message, ['role' => 'system', 'content' => $params['background']]);
        }
        if (!empty($params['context'])) {
            $message = array_merge_recursive($message, $params['context']);
        }

        array_push($message, ['role' => 'user', 'content' => $params['msg']]);
        // $length = mb_strlen($params['msg']);
        // Logger::error($message);
        $data = [
            'input' => ['messages' => $message],
            'model' => $this->model,
        ];
        if (!empty($params['functions']) && is_array($params['functions'])) {
            $data['functions'] = $params['functions'];
        }
        $res = $this->doRequest($data, self::API_SMART);
        if ($res['code']) {
            $res['answer_type'] = self::ANSWER_TEXT;
            $text = isset($res['output']['text']) ? $res['output']['text'] : '';
            $res['answer'] = str_replace('<br/>', "\n", trim($text, "<br/>"));
        }
        return $res;
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
        Logger::error(json_encode($options, JSON_UNESCAPED_UNICODE));
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

    public function dealRes($params)
    {
        $res = $params;
        //Logger::error($params);
        $res['code'] = 1;
        if (!empty($res['output']['text'])) {
            $res['choices'][0]['text'] = $res['output']['text'];
        } else {
            $res['code'] = 0;
            $res['choices'][0]['text'] = "异常";
        }
        return $res;
    }
}
