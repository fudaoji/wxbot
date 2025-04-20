<?php
/**
 * Created by PhpStorm.
 * Script Name: Blog.php
 * Create: 2025/4/20 下午12:32
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;


use app\common\model\Blog as M;
use ky\Logger;
use ky\SEOGenerator;
use think\facade\Db;

class Blog
{
    static $model = null;

    static function model(){
        if(is_null(self::$model)){
            self::$model = new M();
        }
        return self::$model;
    }

    /**
     * 生成seo
     * @param $data
     * @param int $keyword_num
     * @param int $des_len
     * @return bool|mixed
     * @throws \think\Exception
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function generateSEO($data, $keyword_num = 5, $des_len = 150){

        ini_set('memory_limit', '1024M');
        // 初始化
        $generator = new SEOGenerator();

        // 生成SEO数据
        $arr = $generator->generate($data['content'], $keyword_num, $des_len);
        $data = self::model()->updateOne([
            'id' => $data['id'],
            'seo_keywords' => $arr['keywords'],
            'seo_description' => $arr['description']
        ]);

        return $data;
    }

    /**
     * 新增view_num
     * @param $blog
     * @return bool|mixed
     * @throws \think\Exception
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function incViewNum($blog)
    {
        $blog = self::model()->updateOne([
            'id' => $blog['id'],
            'view_num' => Db::raw('view_num + 1')
        ]);
        return $blog;
    }
}