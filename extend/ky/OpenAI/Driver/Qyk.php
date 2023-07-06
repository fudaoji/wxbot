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

class Qyk extends Base
{
    //http://api.qingyunke.com/api.php?key=free&appid=0&msg=%E4%BD%A0%E5%A5%BD
    protected $baseUri = 'http://api.qingyunke.com/';
    protected $errMsg = '';
    protected $appKey = 'free';

    const API_SMART = '/api.php';

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
        $params['key'] = $this->appKey;
        $params['appid'] = $this->appId;
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
        if($params['result'] == 0){
            $res['code'] = 1;
            $res['answer_type'] = self::ANSWER_TEXT;
            $res['answer'] = $params['content'];
        }else{
            $res['code'] = 0;
            $res['errmsg'] = $this->getError();
        }
        return $res;
    }
}