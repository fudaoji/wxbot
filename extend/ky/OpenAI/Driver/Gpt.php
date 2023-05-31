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

class Gpt extends Base
{
    const API_SMART = '/v1/chat/completions';
    const API_SMART_IMG = '/v1/images/generations';
    protected $baseUri = 'https://api.openai.com';
    protected $errMsg = '';
    protected $appKey = '';
    protected $proxy = '';
    protected $model = 'gpt-3.5-turbo';
    private $method = 'post';

    public function __construct($options = [])
    {
        parent::__construct($options);
        !empty($options['appid']) && $this->appKey = $options['appid'];
        !empty($options['proxy']) && $this->proxy = $options['proxy'];
    }

    /**
     * 智能聊天
     * @param $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function smart($params){
        $message = [
            ['role' => 'system', 'content' => "你是一个智能助手assistant"],
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
        $length = mb_strlen($params['msg']);
        //Logger::error($message);
        $data = [
            'messages' => $message,
            'model' => $this->model,
            // 'temperature' => 0.2,
            // 'tokensLength' => $length
        ];
        $res = $this->doRequest($data, self::API_SMART);
        if($res['code']){
            $res['answer_type'] = self::ANSWER_TEXT;
            $text = isset($res['choices'][0]['text']) ? $res['choices'][0]['text'] : '';
            $res['answer'] = str_replace('<br/>',"\n", trim($text, "<br/>"));
        }
        return  $res;
    }

    private function doRequest($params = [], $api = ''){
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

    public function errors($code = 200){
        $list = [
            401 => '获取token失败',
            404 => '接口路径与请求方式错误',
            429 => '接口请求频率超过限制',
            500 => '服务端错误'
        ];
        $this->errMsg = isset($list[$code]) ? ($code . ':' .$list[$code]) : ($code.':未知错误');
    }

    public function dealRes($params){
        $res = $params;
        Logger::error($params);
        if(isset($res['choices'][0]['message']['content'])){
            $res['choices'][0]['text'] = $res['choices'][0]['message']['content'];
            $res['code'] = 1;
        }else{
            $res['code'] = 0;
            $res['errmsg'] = $params['msg'];
        }
        return $res;
    }
}
