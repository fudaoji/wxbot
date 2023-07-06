<?php
/**
 * Created by PhpStorm.
 * Script Name: AiConfig.php
 * Create: 8/1/22 9:35 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\bgf;

class Config extends Bgf
{
    protected $table = 'config';

    /**
     * 全局设置
     * @param array $where
     * @param string $key
     * @param int $refresh
     * @return mixed
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function getConf($where = [], $key = '', $refresh = 0){
        $list = $this->getField(['key', 'value'], $where, $refresh);
        if(!empty($key)){
            return isset($list[$key]) ? $list[$key] : '';
        }
        return $list;
    }
}