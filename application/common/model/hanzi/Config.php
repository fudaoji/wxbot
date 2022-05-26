<?php
/**
 * Created by PhpStorm.
 * User: yyp
 * Date: 2022/1/30
 * Time: 14:03
 */

namespace app\common\model\hanzi;

class Config extends Hanzi
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