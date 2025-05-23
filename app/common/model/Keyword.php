<?php
/**
 * Created by PhpStorm.
 * Script Name: Reply.php
 * Create: 2022/4/15 10:23
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;

use think\facade\Db;

class Keyword extends Base
{
    protected $isCache = true;

    static function matchTypes($id = null){
        $list = [
            0 => '完全匹配',
            1=>'模糊匹配'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    /**
     * 根据关键词查询
     * @param array $params
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function searchByKeyword($params = []){
        $refresh = $params['refresh'] ?? false;
        unset($params['refresh']);
        ksort($params);
        $cache_key = md5(__FUNCTION__ . serialize($params));
        $data = cache($cache_key);
        if($refresh || empty($data)){
            $data = $this->whereRaw("bot_id=? and status=1 and ((match_type=0 and keyword=?) OR (match_type=1 and LOCATE(`keyword`, ?)))", [
                $params['bot_id'], // 第一个?的值
                $params['keyword'], // 第二个?的值，对应keyword=?
                $params['keyword'] // 第三个?的值，对应LOCATE(?, keyword)
            ])
                ->order(['sort' => 'desc'])
                ->select()
                ->toArray();
        }
        cache($cache_key, $data);
        return $data;
    }
}