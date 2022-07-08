<?php
// +----------------------------------------------------------------------
// | [KyPHP System] Copyright (c) 2020 http://www.kuryun.com/
// +----------------------------------------------------------------------
// | [KyPHP] 并不是自由软件,你可免费使用,未经许可不能去掉KyPHP相关版权
// +----------------------------------------------------------------------
// | Author: fudaoji <fdj@kuryun.cn>
// +----------------------------------------------------------------------

/**
 * Created by PhpStorm.
 * Script Name: Setting.php
 * Create: 2020/3/2 下午8:56
 * Description:  配置
 * Author: fudaoji<fdj@kuryun.cn>
 */
namespace app\common\model;
use ky\BaseModel;

class Setting extends BaseModel
{
    protected $cacheTag = 'setting';

    public function __construct($data = [])
    {
        parent::__construct($data);
    }

    /**
     * 设置配置值
     * @param $map
     * @param $data
     * @return bool|mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function setDatas($map, $data){
        $record = $this->getOneByMap($map);
        $record['value'] = json_decode($record['value'], true);
        $record['value'] = array_merge($record['value'], $data);
        $record['value'] = json_encode($record['value'], JSON_UNESCAPED_UNICODE);
        return $this->updateOne(['id' => $record['id'], 'value' => $record['value']]);
    }

    /**
     * 全局设置
     * @param int $refresh
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function settings($refresh = 0){
        $list = $this->getAll(['refresh' => $refresh]);
        $data = [];
        foreach ($list as $v){
            $data[$v['name']] = json_decode($v['value'], true);
        }
        config(['system' => array_merge(config('system.'), $data)]);
        return $data;
    }
}