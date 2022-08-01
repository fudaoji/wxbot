<?php
/**
 * Created by PhpStorm.
 * Script Name: AiConfig.php
 * Create: 8/1/22 9:35 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\ai;


use ky\OpenAI\Driver\Weixin;

class Config extends Ai
{
    protected $table = 'config';

    /**
     * 获取AI client
     * @param $bot
     * @param string $driver
     * @return Weixin
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getAiClient($bot, $driver = 'weixin'){
        $configs = $this->getConf(['bot_id' => $bot['id']]);
        switch ($driver){
            default:
                $client = new Weixin([
                    'appid' => $configs['wx_appid'],
                    'token' => $configs['wx_token'],
                    'encoding_aes_key' => $configs['wx_encoding_aes_key']
                ]);
                break;
        }
        return $client;
    }

    /**
     * 全局设置
     * @param array $where
     * @param string $key
     * @param int $refresh
     * @return mixed
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function getConf($where = [], $key = '', $refresh = 0){
        $list = $this->getField(['key', 'value'], $where, ['refresh' => $refresh]);
        if(!empty($key)){
            return isset($list[$key]) ? $list[$key] : '';
        }
        return $list;
    }
}