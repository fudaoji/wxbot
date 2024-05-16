<?php
/**
 * Created by PhpStorm.
 * Script Name: Aliyun.php
 * Create: 2023/6/26 9:49
 * Description: 阿里云 oss https://github.com/aliyun/aliyun-oss-php-sdk?spm=a2c4g.14484438.10004.1
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace Dao\Upload\Driver;

use OSS\Core\OssException;
use OSS\OssClient;

class Aliyun
{
    /**
     * 文件目录
     * @var string
     */
    private $savePath;

    /**
     * 上传错误信息
     * @var string
     */
    private $error = '';

    private $config = array(
        'accessKey'      => '', //ak
        'secrectKey'     => '', //sk
        'domain'         => '', //domain
        'bucket'         => '', //bucket
        'timeout'        => 1500, //超时时间
    );
    private $ossClient;

    /**
     * 构造函数，用于设置上传根路径
     * @param array  $config FTP配置
     */
    public function __construct($config){
        $this->config = array_merge($this->config, $config);
        try {
            $endpoint = str_replace('https://' . $this->config['bucket'] . '.', '', $this->config['domain']);
            $this->ossClient = new OssClient($this->config['accessKey'], $this->config['secrectKey'], $endpoint);
        }catch (OssException $e){
            die($e->getMessage());
        }
    }

    /**
     * 检测上传根目录(七牛上传时支持自动创建目录，直接返回)
     * @param string $rootpath   根目录
     * @return boolean true-检测通过，false-检测失败
     */
    public function checkRootPath($rootpath = ''){
        return true;
    }

    /**
     * 检测上传目录(七牛上传时支持自动创建目录，直接返回)
     * @param  string $savepath 上传目录
     * @return boolean          检测结果，true-通过，false-失败
     */
    public function checkSavePath($savepath){
        $this->savePath = trim($savepath, './') . '/';
        return true;
    }

    /**
     * 创建文件夹 (七牛上传时支持自动创建目录，直接返回)
     * @param  string $savepath 目录名称
     * @return boolean          true-创建成功，false-创建失败
     */
    public function mkdir($savepath = ''){
        return true;
    }

    /**
     * 保存指定文件
     * @param array $file 保存的文件信息
     * @param boolean $replace 同名文件是否覆盖
     * @return boolean          保存状态，true-成功，false-失败
     * @throws \Exception
     */
    public function save(&$file, $replace=false){
        // 要上传的空间
        $bucket = $this->config['bucket'];

        // 上传到七牛后保存的文件名
        $key = $this->savePath . $file['savename'];

        $content = $file['tmp_name'];
        try {
            $this->ossClient->uploadFile($bucket, $key, $content);
            $file['url'] = $this->downLink($key);
            return  true;
        } catch (OssException $e) {
            $this->error = '上传出错: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * 获取最后一次上传错误信息
     * @return string 错误信息
     */
    public function getError(){
        return $this->error;
    }

    /**
     * 获取文件下载资源链接
     * @param $key
     * @return string
     * @Author: Doogie <461960962@qq.com>
     */
    public function downLink($key){
        //$key = urlencode($key);
        $key = self::_escapeQuotes($key);
        //return $this->ossClient->signUrl($this->config['bucket'], $key, 3600); 临时权限
        return "{$this->config['domain']}/{$key}";
    }

    /**
     * @param $str
     * @return mixed
     * @Author: Doogie <461960962@qq.com>
     */
    static function _escapeQuotes($str){
        $find = array("\\", "\"");
        $replace = array("\\\\", "\\\"");
        return str_replace($find, $replace, $str);
    }

    /**
     * 文件直传
     * @param array $params
     * @return bool|string
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function putString($params = []){
        $key = empty($params['key']) ? uniqid() : $params['key'];
        $string = empty($params['string']) ? '' : $params['string'];
        // 要上传的空间
        $bucket = $this->config['bucket'];

        try {
            $this->ossClient->putObject($bucket, $key, $string);
            return $this->downLink($key);
        } catch (OssException $e) {
            $this->error = '上传出错: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * 删除对象
     * @param string $key
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function delete($key = ''){
        $bucket = $this->config['bucket'];
        try {
            $this->ossClient->deleteObject($bucket, $key);
            return true;
        } catch (OssException $e) {
            $this->error = '删除出错: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * 资源列表
     * @param int $current_page
     * @param int $page_size
     * @param string $prefix
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function listFile($current_page=1, $page_size=10, $prefix=''){
        $bucket = $this->config['bucket'];
        try {
            $options = [
                'max-keys'  => $page_size,//specifies max object count to return. By default is 100 and max value could be 1000.
                'prefix'    => $prefix, //specifies the key prefix the returned objects must have. Note that the returned keys still contain the prefix.
                'delimiter' => '',//The delimiter of object name for grouping object. When it's specified, listObjects will differeniate the object and folder. And it will return subfolder's objects.
                'marker'    => $current_page, //The key of returned object must be greater than the 'marker'.
            ];
            $res = $this->ossClient->listObjects($bucket, $options);
            return [
                'marker' => $res['marker'],
                'items' => $res['objectList']
            ];
        } catch (OssException $e) {
            $this->error = '获取文件列表错误: ' . $e->getMessage();
            return false;
        }
    }
}