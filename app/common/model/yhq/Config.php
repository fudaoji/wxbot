<?php
/**
 * Created by PhpStorm.
 * Script Name: Config.php
 * Create: 2022/4/6 16:05
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\yhq;

class Config extends Yhq
{
    protected $isCache = true;
    protected $table = 'config';

    /**
     * 全局设置
     * @param array $where
     * @param string $key
     * @param int $refresh
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
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