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
 * Script Name: Csv.php
 * Create: 2016/10/28 下午7:57
 * Description: 导出excel
 * Author: Doogie<461960962@qq.com>
 */

namespace ky;

class Csv
{
    private static $instance; //单例对象
    protected $fileName = ''; //文件名称

    /**
     * 单例对象
     * @return Csv
     * @author: Doogie<461960962@qq.com>
     */
    public static function getInstance() {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 导出数据
     * @param array $list 数组
     * @param array $title 字段名称数组
     * @author: Doogie<461960962@qq.com>
     */
    public function putCsv($list,$title){
        $file_name = ($this->fileName ? $this->fileName : "CSV".date("mdHis",time())).".csv";
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename='.$file_name );
        header ( 'Cache-Control: max-age=0' );
        $file = fopen('php://output',"a");
        $limit = 1000;
        $calc=0;
        $tit = $tarr = [];
        foreach ($title as $v){
            $tit[]=iconv('UTF-8', 'GB2312//IGNORE',$v);
        }
        fputcsv($file, $tit);
        foreach ($list as $v){
            $calc++;
            if($limit==$calc){
                ob_flush();
                flush();
                $calc=0;
            }
            foreach ($v as $t){
                $tarr[]=iconv('UTF-8', 'GB2312//IGNORE', $t);
            }
            fputcsv($file,$tarr);
            unset($tarr);
        }
        unset($list);
        fclose($file);
        exit();
    }

    /**
     * 设置导出的文件名称
     * @param string $file_name 文件名称
     * @return $this
     * @author: Doogie<461960962@qq.com>
     */
    public function setFileName($file_name=''){
        if($file_name){
            $this->fileName = $file_name;
        }
        return $this;
    }
}