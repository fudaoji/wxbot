<?php
/**
 * Script name: Qiniu.php
 * Created by PhpStorm.
 * Create: 2016/4/26 12:20
 * Description: 七牛上传驱动
 * Author: Doogie<461960962@qq.com>
 */

namespace ky\Upload\Driver;

use ky\ErrorCode;
use Qiniu\Auth;  // 引入鉴权类
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use Qiniu\Cdn\CdnManager;
use Qiniu\Config;
use Qiniu\Processing\PersistentFop;
use think\facade\Log;

class Qiniu
{
    /**
     * 上传文件根目录
     * @var string
     */
    private $rootPath;

    /**
     * 上传错误信息
     * @var string
     */
    private $error = '';

    private $config = array(
        'secrectKey'     => '', //七牛服务器
        'accessKey'      => '', //七牛用户
        'domain'         => '', //七牛密码
        'bucket'         => '', //空间名称
        'timeout'        => 300, //超时时间
    );
    private $auth;

    /**
     * 构造函数，用于设置上传根路径
     * @param array  $config FTP配置
     */
    public function __construct($config){
        $this->config = array_merge($this->config, $config);
        /* 设置根目录 */
        $this->auth = new Auth($this->config['accessKey'], $this->config['secrectKey']);  // 构建鉴权对象
    }

    /**
     * 检测上传根目录(七牛上传时支持自动创建目录，直接返回)
     * @param string $rootpath   根目录
     * @return boolean true-检测通过，false-检测失败
     */
    public function checkRootPath($rootpath){
        $this->rootPath = trim($rootpath, './') . '/';
        return true;
    }

    /**
     * 检测上传目录(七牛上传时支持自动创建目录，直接返回)
     * @param  string $savepath 上传目录
     * @return boolean          检测结果，true-通过，false-失败
     */
    public function checkSavePath($savepath){
        return true;
    }

    /**
     * 创建文件夹 (七牛上传时支持自动创建目录，直接返回)
     * @param  string $savepath 目录名称
     * @return boolean          true-创建成功，false-创建失败
     */
    public function mkdir($savepath){
        return true;
    }

    /**
     * 保存指定文件
     * @param  array   $file    保存的文件信息
     * @param  boolean $replace 同名文件是否覆盖
     * @return boolean          保存状态，true-成功，false-失败
     */
    public function save(&$file, $replace=false){
        // 要上传的空间
        $bucket = $this->config['bucket'];

        // 生成上传 Token
        $token = $this->auth->uploadToken($bucket);

        // 上传到七牛后保存的文件名
        $key = $file['savename'];

        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $file['tmp_name']);
        if ($err !== null) {
            $this->error = '上传配置出错';
            Log::error('ErrorCode: ' . ErrorCode::QiniuException . '; ErrorMsg: ' . $err->message());
            return false;
        } else {
            $file['url'] = $this->downLink($key);
            return true;
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
     * 获取文件下载资源链接
     * @param $key
     * @return string
     * @Author: Doogie <461960962@qq.com>
     */
    public function downLink($key){
        $key = urlencode($key);
        $key = self::_escapeQuotes($key);
        return "{$this->config['domain']}/{$key}";
    }

    /**
     * 列出空间的资源文件
     * @param int $current_page 上次列举返回的位置标记，作为本次列举的起点信息。
     * @param int $page_size 本次列举的条目数
     * @param string $prefix 要列取文件的公共前缀
     * @return array|bool
     * @Author: Doogie <461960962@qq.com>
     */
    public function listFile($current_page=1, $page_size=10, $prefix=''){
        $bucketMgr = new BucketManager($this->auth);

        // http://developer.qiniu.com/docs/v6/api/reference/rs/list.html#list-description

        // 列举文件
        list($iterms, $marker, $err) = $bucketMgr->listFiles($this->config['bucket'], $prefix, $current_page, $page_size);
        if ($err !== null) {
            Log::error('ErrorCode: ' . ErrorCode::QiniuException . '; ErrorMsg: ' . $err->message());
            return false;
        } else {
            return [
                'marker' => $marker,
                'items' => $iterms
            ];
        }
    }

    /**
     * 抓取网络文件存入七牛云
     * @param string $url
     * @param string $key
     * @return bool|string
     * @author: Doogie<461960962@qq.com>
     */
    public function fetch($url = '', $key = ''){
        $bmgr = new BucketManager($this->auth);

        list($ret, $err) = $bmgr->fetch($url, $this->config['bucket'], $key);
        if ($err !== null) {
            Log::error('ErrorCode: ' . ErrorCode::QiniuException . '; ErrorMsg: ' . $err->message());
            return false;
        } else {
            return $key;
        }
    }

    /**
     * 删除文件
     * @param string $key
     * @return bool|string
     * Author: Doogie<fdj@kuryun.cn>
     */
    public function delete($key = ''){
        $bucketManager = new BucketManager($this->auth);
        $err = $bucketManager->delete($this->config['bucket'], $key);
        if ($err !== null) {
            Log::write('ErrorCode: ' . ErrorCode::QiniuException . '; ErrorMsg: ' . $err->message());
            return false;
        } else {
            return $key;
        }
    }

    /**
     * 刷新文件
     * @param array $urls
     * @param array $dirs
     * Author: Doogie<fdj@kuryun.cn>
     */
    public function refresh($urls = [], $dirs = []){
        $cdnManager = new CdnManager($this->auth);
        if($urls){
            list($ret, $err) = $cdnManager->refreshUrls($urls);
            if ($err != null) {
                Log::write('ErrorCode: ' . ErrorCode::QiniuException . '; ErrorMsg: ' . $err->message());
            }
        }

        if($dirs){
            list($ret, $err) = $cdnManager->refreshDirs($dirs);
            if ($err != null) {
                Log::write('ErrorCode: ' . ErrorCode::QiniuException . '; ErrorMsg: ' . $err->message());
            }
        }
    }

    /**
     * 音视频转码
     * @param array $params
     * @return array|bool
     * Author: Doogie<fdj@kuryun.cn>
     */
    public function avThumb($params = []){
        $new_key = empty($params['new_key']) ? time() : $params['new_key'];

        //转码是使用的队列名称。 https://portal.qiniu.com/mps/pipeline
        $pipeline = empty($params['pipeline']) ? 'avpipeline' : $params['pipeline'];
        $force = false;

        //转码完成后通知到你的业务服务器。
        //$notifyUrl = 'http://375dec79.ngrok.com/notify.php';
        $config = new Config();
        $pfop = new PersistentFop($this->auth, $config);

        //要进行转码的转码操作。 http://developer.qiniu.com/docs/v6/api/reference/fop/av/avthumb.html
        $fops = $params['avthumb'] . "|saveas/" . \Qiniu\base64_urlSafeEncode($this->config['bucket'] . ":" . $new_key);

        list($id, $err) = $pfop->execute($this->config['bucket'], $params['key'], $fops, $pipeline, $params['notify_url'], $force);
        if ($err !== null) {
            dump($err->message());
            Log::error('ErrorCode: ' . ErrorCode::QiniuException . '; ErrorMsg: ' . $err->message());
            return false;
        } else {
            return ['id' => $id, 'key' => $new_key, 'url' => $this->downLink($new_key)];
        }
    }

    /**
     * 转码查询
     * @param string $id
     * @return bool
     * Author: Doogie<fdj@kuryun.cn>
     */
    public function avThumbStatus($id = ''){
        $config = new Config();
        $pfop = new PersistentFop($this->auth, $config);

        //查询转码的进度和状态
        list($ret, $err) = $pfop->status($id);
        if ($err !== null) {
            dump($err->message());
            Log::error('ErrorCode: ' . ErrorCode::QiniuException . '; ErrorMsg: ' . $err->message());
            return false;
        } else {
            return $ret;
        }
    }

    /**
     * 上传凭证
     * @param string/array $params

     * @return mixed
     * @author: Doogie<461960962@qq.com>
     */
    public function upToken($params = '')
    {
        if(! is_array($params)){
            $params = ['bucket' => $params];
        }
        $bucket = empty($params['bucket']) ? $this->config['bucket'] : $params['bucket'];
        $key_to_overwrite = isset($params['key_to_overwrite']) ? $params['key_to_overwrite'] : null;
        $expires = empty($params['expires']) ? 3600 : $params['expires'];
        $policy = empty($params['policy']) ? null : $params['policy'];

        // 生成上传 Token
        return $this->auth->uploadToken($bucket, $key_to_overwrite, $expires, $policy);
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
        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->put($this->upToken(), $key, $string);
        if ($err !== null) {
            $this->error = '上传配置出错';
            Log::error('ErrorCode: ' . ErrorCode::QiniuException . '; ErrorMsg: ' . $err->message());
            return false;
        } else {
            return $this->downLink($key);
        }
    }
}