<?php
/**
 * SCRIPT_NAME: Local.php
 * Created by PhpStorm.
 * Time: 2016/4/25 22:25
 * FUNCTION: 文件上传工具
 * @Author: Doogie <461960962>
 */

namespace ky\Upload\Driver;

class Local{
    /**
     * 上传文件根目录
     * @var string
     */
    private $rootPath;

    /**
     * 本地上传错误信息
     * @var string
     */
    private $error = ''; //上传错误信息

    /**
     * 构造函数，用于设置上传根路径
     * @param null $config
     */
    public function __construct($config = null){

    }

    /**
     * 检测上传根目录
     * @param string $rootpath   根目录
     * @return boolean true-检测通过，false-检测失败
     * @Author: Doogie <461960962>
     */
    public function checkRootPath($rootpath){
        if(!(is_dir($rootpath) && is_writable($rootpath))){
            $this->error = '上传根目录不存在或没有可写权限！请尝试手动创建:'.$rootpath;
            return false;
        }
        $this->rootPath = $rootpath;
        return true;
    }

    /**
     * 检测上传目录
     * @param  string $savepath 上传目录
     * @return boolean          检测结果，true-通过，false-失败
     */
    public function checkSavePath($savepath){
        /* 检测并创建目录 */
        if (!$this->mkdir($savepath)) {
            return false;
        } else {
            /* 检测目录是否可写 */
            if (!is_writable($this->rootPath . $savepath)) {
                $this->error = '上传目录 ' . $savepath . ' 不可写！';
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * 保存指定文件
     * @param  array   $file    保存的文件信息
     * @param  boolean $replace 同名文件是否覆盖
     * @return boolean          保存状态，true-成功，false-失败
     * @Author: Doogie <461960962>
     */
    public function save(&$file, $replace=true) {
        $filename = $this->rootPath . $file['savepath'] . $file['savename'];

        /* 不覆盖同名文件 */ 
        if (!$replace && is_file($filename)) {
            $this->error = '存在同名文件' . $file['savename'];
            return false;
        }

        /* 移动文件 */
        if (!move_uploaded_file($file['tmp_name'], $filename)) {
            $this->error = '文件上传保存错误！';
            return false;
        }
        $file['url'] = substr($filename, 1);
        $file['path'] = $filename;
        return true;
    }

    /**
     * 创建目录
     * @param  string $savepath 要创建的穆里
     * @return boolean          创建状态，true-成功，false-失败
     * @Author: Doogie <461960962>
     */
    public function mkdir($savepath){
        $dir = $this->rootPath . $savepath;
        if(is_dir($dir)){
            return true;
        }

        if(mkdir($dir, 0777, true)){
            return true;
        } else {
            $this->error = "目录 {$savepath} 创建失败！";
            return false;
        }
    }

    /**
     * 获取最后一次上传错误信息
     * @return string 错误信息
     * @Author: Doogie <461960962>
     */
    public function getError(){
        return $this->error;
    }

    /**
     * 遍历获取目录下的指定类型的文件
     * @param $path
     * @param $allowFiles
     * @param string $prefix 前缀
     * @param array $files
     * @return array|null
     * @Author: Doogie <461960962@qq.com>
     */
    function listFile($path, $allowFiles, $prefix='0-',&$files = [])
    {
        if (!is_dir($path)) return null;
        if(substr($path, strlen($path) - 1) != '/') $path .= '/';
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    $this->listFile($path2, $allowFiles, $prefix, $files);
                } else {
                    if (preg_match("/^".$prefix.".*\.(".$allowFiles.")$/i", $file)) {
                        $files[] = array(
                            'url'=> substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                            'mtime'=> filemtime($path2)
                        );
                    }
                }
            }
        }
        return $files;
    }

}
