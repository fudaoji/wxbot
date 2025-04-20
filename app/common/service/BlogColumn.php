<?php
/**
 * Created by PhpStorm.
 * Script Name: BlogColumn.php
 * Create: 2025/4/20 下午12:33
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;


use app\common\model\BlogColumn as M;

class BlogColumn
{

    static $model = null;

    static function model(){
        if(is_null(self::$model)){
            self::$model = new M();
        }
        return self::$model;
    }

    static function getTitleToTitle($map = []){
        $list = self::model()->getFieldByOrder([
            'field' => ['title'],
            'where' => $map,
            'refresh' => true,
            'order' => ['sort' => 'desc']
        ]);
        $arr = [];
        foreach ($list as $item){
            $arr[$item] = $item;
        }
        return $arr;
    }

    /**
     * 获取热门
     * @param array $map
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getHots($map = []){
        $list = self::model()->getList([1, 5], ['status' => 1], ['sort' => 'desc'], ['id', 'title']);
        return $list;
    }
}