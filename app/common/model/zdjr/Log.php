<?php
/**
 * Created by PhpStorm.
 * Script Name: Log.php
 * Create: 2022/9/7 16:10
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\zdjr;

class Log extends Zdjr
{
    protected $table = 'log';
    protected $isCache = false;

    public static function statusList($id = null){
        $list = [
            -1 => '未知内容',
            0 => '成功',
            1 => '找不到相关账号',
            2 => '对方已隐藏账号',
            3 => '操作频繁',
            4 => '用户不存在',
            5 => '用户异常'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    /**
     * 判断每日加友次数是否达到上限
     * @param array $params
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function checkLimit(array $params)
    {
        $config_m = new Config();
        $limit = max(10, intval($config_m->getConf(['admin_id' => $params['admin_id']], 'apply_perday')));
        return $limit > $this->total([
            'bot_id' => $params['bot_id'],
            'create_time' => ['between', [strtotime(date('Y-m-d')), time()]]
        ]);
    }

    public static function types($id = null){
        $list = [
            1 => '搜索账号',
            2 => '添加'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }
}