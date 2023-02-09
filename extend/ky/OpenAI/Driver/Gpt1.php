<?php
/**
 * Created by PhpStorm.
 * Script Name: Qyk.php
 * Create: 2021/12/29 17:34
 * Description: 青云客聊天机器人
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\OpenAI\Driver;

use ky\OpenAI\Base;

class Gpt1 extends Base
{
    //http://chat.h2ai.cn/api/trilateral/openAi/completions?prompt=%E7%94%A8PHP%E4%BB%A3%E7%A0%81%E6%BC%94%E7%A4%BA%E5%B7%A5%E5%8E%82%E6%A8%A1%E5%BC%8F&openaiId=126334723108039235977447039012068514425338170296383
    protected $baseUri = 'http://chat.h2ai.cn/';
    protected $errMsg = '';
    protected $appKey = 'free';

    const API_SMART = '/api/trilateral/openAi/completions';

    public function __construct($options = [])
    {
        parent::__construct($options);
        !empty($options['app_key']) && $this->appKey = $options['app_key'];
    }

    /**
     * 智能聊天
     * @param $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function smart($params){
        return  $this->doRequest($params, self::API_SMART);
    }

    private function doRequest($params = [], $api = ''){
        $params['prompt'] = $params['msg'];
        $params['openaiId'] = $this->appId;
        return $this->request([
            'url' => $api,
            'data' => $params,
            'method' => 'get'
        ]);
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
        $res['ori_res'] = $params;
        if($params['code'] == 200){
            $res['code'] = 1;
            $res['answer_type'] = self::ANSWER_TEXT;
            $text = isset($params['data']['choices'][0]['text']) ? $params['data']['choices'][0]['text'] : '';
            $res['answer'] = str_replace('<br/>',"\n", trim($text, "<br/>"));
        }else{
            $res['code'] = 0;
            $res['errmsg'] = $params['msg'];
        }
        return $res;
    }
}