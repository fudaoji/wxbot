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
 * Script Name: Upload.php
 * Create: 2020/5/24 上午11:34
 * Description: 附件上传模型
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;

use ky\BaseModel;
use Dao\Upload\Upload as Uploader;

class Upload extends BaseModel
{
    protected $isCache = true;
    protected $expire = 0;
    protected $key = 'uid';
    public $rule = [
        'type' => 'mod', // 分表方式
        'num' => 5   // 分表数未传分表key时需使用
    ];
    private static $setting = [
        'driver' => 'local'
    ];

    public function __construct()
    {
        parent::__construct();
        if(config('system.upload')){
            self::$setting = array_merge(self::$setting, config('system.upload'));
        }
    }

    /**
     * 驱动字典
     * @param null $id
     * @return array|mixed
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public static function locations($id = null){
        $list = [
            Uploader::LOCAL => '本地',
            Uploader::QINIU => '七牛',
            Uploader::ALIYUN => '阿里云',
            Uploader::QCLOUD => '腾讯云'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    /**
     * 文件上传
     * @param  array  $files   要上传的文件列表（通常是$_FILES数组）
     * @param  array  $config  上传驱动配置
     * @param  array  $extra   额外的参数
     * @return array           文件上传成功后的信息
     */
    public function upload($files, $config = [], $extra = []){
        /* 上传文件 */
        //$config['callback'] = [$this, 'isFile'];
        $config['removeTrash'] = [$this, 'removeTrash'];

        $driver = self::$setting['driver'];
        $Upload = new Uploader($config, $driver, self::driverConfig($driver));
        $info   = $Upload->upload($files, $extra['uid'] . '-');
        $pics = [];

        if($info){
            foreach($info as $k => $v){
                $insert_data = [
                    'uid' => $extra['uid'],
                    'path' => empty($v['path']) ? $v['url'] : $v['path'],
                    'url' => strtolower($driver) == Uploader::LOCAL ? (request()->domain() . $v['url']) : $v['url'],
                    'size' => $v['size'],
                    'ext' => $v['ext'],
                    'md5' => $v['md5'],
                    'sha1' => $v['sha1'],
                    'location' => ucfirst(strtolower($driver))
                ];
                if(empty($v['id'])){  //新文件
                    $insert_data['name'] = $v['savename'];
                    $insert_data['original_name'] = $v['name'];
                }else{ //说明该文件已存在
                    $insert_data['name'] = $v['name'];
                    $insert_data['original_name'] = $v['original_name'];
                }
                //$pics[] = $this->addOne($insert_data);
                $pics[] = $insert_data;
            }
            $return['code'] = 1;
            $return['data'] = $pics;
        }else{
            $return['code'] = 0;
            $return['msg'] = $Upload->getError();
        }
        return $return;
    }

    /**
     * 检测当前上传的文件是否已经存在
     * @param $file
     * @return mixed
     * @throws \Exception
     * @Author  Doogie<461960962@qq.com>
     */
    public function isFile($file){
        if(empty($file['md5'])){
            throw new \Exception('缺少参数:md5');
        }
        /* 查找文件 */
        return $this->getByMd5Sha1($file['md5'], $file['sha1']);
    }

    /**
     * 清除数据库存在但本地不存在的数据
     * @param $data
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function removeTrash($data){
        $this->delOne($data['id']);
    }

    /**
     * 根据md5值和sha1获取文件数据
     * @param string $md5
     * @param string $sha1
     * @param int $refresh
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\exception\DbException
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function getByMd5Sha1($md5='', $sha1='', $refresh = 0){
        return $this->getOneByMap(['md5' => $md5,'sha1'=>$sha1], true, $refresh);
    }

    /**
     * ueeditor编辑器上传
     * @param string $action
     * @param array $extra
     * @return array
     * @Author: Doogie <461960962@qq.com>
     */
    public function ueUpload($action = '', $extra = []){
        $config = self::ueConfig();
        switch($action){
            case $config['imageActionName']:
                $upload_config = self::config();
                break;
            case $config['uploadvideo']:
                $upload_config = self::config('video');
                break;
            default:
                $upload_config = self::config('file');
                break;
        }

        $res = $this->upload($_FILES, $upload_config, $extra);
        if($res['code'] == 1){
            $file = $res['data'][0];
            $return = [
                "state" => "SUCCESS",
                'url' => $file['url'],
                'title' => $file['name'],
                'original' => $file['original_name'],
                'type' => $file['ext'],
                'size' => $file['size']
            ];
        }else{
            $return['state'] = $res['msg'];
        }
        return $return;
    }

    /**
     * ueditor的列出文件和图片
     * @param string $action
     * @param array $extra
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @Author: Doogie <461960962@qq.com>
     */
    public function ueList($action='', $extra = []){
        $driver = self::$setting['driver'];
        $config = self::ueConfig();
        /* 判断类型 */
        switch ($action) {
            /* 列出文件 */
            case 'listfile':
                $listSize = $config['fileManagerListSize'];
                $path = $config['fileManagerListPath'];
                break;
            default:/* 默认列出图片 */
                $listSize = $config['imageManagerListSize'];
                $path = $config['imageManagerListPath'];
                break;
        }
        /* 获取参数 */
        $size = isset($_GET['size']) ? (int)htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? (int)htmlspecialchars($_GET['start']) : 0;
        $page = intval(($_GET['start'] / $size))+1;
        $total = 0;
        $files = [];
        switch($driver){
            //不同的上传驱动对应不同的列表
            case 'qiniu':
            default:
                $where = ['uid' => $extra['uid']];
                if($action === 'listimage'){
                    //$where['ext'] = ['in', explode(',', self::$setting['image_ext'])];
                    $total = model('mediaImage')->total($where);
                    $files = model('mediaImage')->getList([$page, $size], $where, ['id' => 'desc'], 'url,create_time');
                }
                if($files){
                    foreach($files as &$v){
                        $v['mtime'] = $v['create_time'];
                    }
                }
                break;
        }
        unset($size, $page, $driver, $config, $listSize, $path, $where, $extra);

        /* 返回数据 */
        return [
            "state" => "SUCCESS",
            "list" => $files,
            "start" => $start,
            "total" => $total
        ];
    }

    /**
     * 驱动需要的配置
     * @param string $driver
     * @return array
     * @Author  Doogie<461960962@qq.com>
     */
    public static function driverConfig($driver = 'local'){
        $driver = strtolower($driver);
        switch($driver){
            case Uploader::QCLOUD:  //七牛
                $config = [
                    'accessKey' => self::$setting['qcloud_ak'] ?: '',
                    'secrectKey' => self::$setting['qcloud_sk'] ?: '',
                    'bucket' => self::$setting['qcloud_bucket'] ?: '',
                    'domain' => self::$setting['qcloud_domain'] ?: '',
                    'region' => self::$setting['qcloud_region'] ?: '',
                    'timeout' => 3600,
                ];
                break;
            case Uploader::ALIYUN:  //七牛
                $config = [
                    'accessKey' => self::$setting['aliyun_ak'] ?: '',
                    'secrectKey' => self::$setting['aliyun_sk'] ?: '',
                    'bucket' => self::$setting['aliyun_bucket'] ?: '',
                    'domain' => self::$setting['aliyun_domain'] ?: '',
                    'timeout' => 3600,
                ];
                break;
            case Uploader::QINIU:  //七牛
                $config = [
                    'accessKey' => self::$setting['qiniu_ak'] ?: '',
                    'secrectKey' => self::$setting['qiniu_sk'] ?: '',
                    'bucket' => self::$setting['qiniu_bucket'] ?: '',
                    'domain' => self::$setting['qiniu_domain'] ?: '',
                    'timeout' => 3600,
                ];
                break;
            default: //本地
                $config = [];
        }
        return $config;
    }


    /**
     * 上传配置
     * @param  string $media_type
     * @return array
     * @Author  Doogie<461960962@qq.com>
     */
    public static function config($media_type = 'image'){
        $media_type = strtolower($media_type);
        $config = [
            'mimes'    => '', //允许上传的文件MiMe类型
            'maxSize'  => self::$setting['image_size'], //上传的文件大小限制 (0-不做限制)
            'exts'     => self::$setting['image_ext'] ?: 'jpg,gif,png,jpeg,bmp', //允许上传的文件后缀
            'autoSub'  => true, //自动子目录保存文件
            'subName'  => ['date', 'Y-m-d'], //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
            'rootPath' => './uploads', //保存根路径
            'savePath' => '/image/', //保存路径
            'saveName' => ['uniqid', ''], //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
            'saveExt'  => '', //文件保存后缀，空则使用原后缀
            'replace'  => false, //存在同名是否覆盖
            'hash'     => true, //是否生成hash编码
            'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
        ];
        switch ($media_type){
            case 'file':
                $config['savePath'] = '/file/';
                $config['maxSize'] = self::$setting['file_size'];
                $config['exts'] = self::$setting['file_ext'] ?:'jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml,mp3,mp4,xls,xlsx,pdf';
                break;
            case 'voice':
                $config['savePath'] = '/voice/';
                $config['maxSize'] = self::$setting['voice_size'];
                $config['exts'] = self::$setting['voice_ext'] ?:'mp3,wma,wav,amr';
                break;
            case 'video':
                $config['savePath'] = '/video/';
                $config['maxSize'] = self::$setting['video_size'];
                $config['exts'] = self::$setting['video_ext'] ?:'mp4';
                break;
        }

        return $config;
    }

    /**
     * ueditor的配置
     * @return array
     * @Author: Doogie <461960962@qq.com>
     */
    public static function ueConfig(){
        $path_pre = '';
        return [
            /* 上传图片配置项 */
            "imageActionName" => "uploadimage", /* 执行上传图片的action名称 */
            "imageFieldName"=> "upfile", /* 提交的图片表单名称 */
            "imageMaxSize"=> self::$setting['image_size'], /* 上传大小限制，单位B */
            "imageAllowFiles"=> [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 上传图片格式显示 */
            "imageCompressEnable"=> true, /* 是否压缩图片,默认是true */
            "imageCompressBorder"=> 1600, /* 图片压缩最长边限制 */
            "imageInsertAlign"=> "none", /* 插入的图片浮动方式 */
            "imageUrlPrefix"=> "", /* 图片访问路径前缀 */
            "imagePathFormat"=> $path_pre."/uploads/image/{yyyy}-{mm}-{dd}/{time}{rand:6}",
            /*"/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",*/
            /* 上传保存路径,可以自定义保存路径和文件名格式 */
            /* {filename} 会替换成原文件名,配置这项需要注意中文乱码问题 */
            /* {rand:6} 会替换成随机数,后面的数字是随机数的位数 */
            /* {time} 会替换成时间戳 */
            /* {yyyy} 会替换成四位年份 */
            /* {yy} 会替换成两位年份 */
            /* {mm} 会替换成两位月份 */
            /* {dd} 会替换成两位日期 */
            /* {hh} 会替换成两位小时 */
            /* {ii} 会替换成两位分钟 */
            /* {ss} 会替换成两位秒 */
            /* 非法字符 \ : * ? " < > | */
            /* 具请体看线上文档: fex.baidu.com/ueditor/#use-format_upload_filename */

            /* 涂鸦图片上传配置项 */
            "scrawlActionName"=> "uploadscrawl", /* 执行上传涂鸦的action名称 */
            "scrawlFieldName"=> "upfile", /* 提交的图片表单名称 */
            "scrawlPathFormat"=>$path_pre."/uploads/image/{yyyy}-{mm}-{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "scrawlMaxSize"=> self::$setting['image_size'], /* 上传大小限制，单位B */
            "scrawlUrlPrefix"=> "", /* 图片访问路径前缀 */
            "scrawlInsertAlign"=> "none",

            /* 截图工具上传 */
            "snapscreenActionName"=>"uploadimage", /* 执行上传截图的action名称 */
            "snapscreenPathFormat"=> $path_pre."/uploads/image/{yyyy}-{mm}-{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "snapscreenUrlPrefix"=> "", /* 图片访问路径前缀 */
            "snapscreenInsertAlign"=> "none", /* 插入的图片浮动方式 */

            /* 抓取远程图片配置 */
            "catcherLocalDomain"=>["127.0.0.1", "localhost", "img.baidu.com"],
            "catcherActionName"=> "catchimage", /* 执行抓取远程图片的action名称 */
            "catcherFieldName"=> "source", /* 提交的图片列表表单名称 */
            "catcherPathFormat"=> $path_pre."/uploads/image/{yyyy}-{mm}-{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "catcherUrlPrefix"=> "", /* 图片访问路径前缀 */
            "catcherMaxSize"=> 2048000, /* 上传大小限制，单位B */
            "catcherAllowFiles"=> [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 抓取图片格式显示 */

            /* 上传视频配置 */
            "videoActionName"=> "uploadvideo", /* 执行上传视频的action名称 */
            "videoFieldName"=> "upfile", /* 提交的视频表单名称 */
            "videoPathFormat"=>$path_pre."/uploads/video/{yyyy}-{mm}-{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "videoUrlPrefix"=> "", /* 视频访问路径前缀 */
            "videoMaxSize"=> 102400000, /* 上传大小限制，单位B，默认100MB */
            "videoAllowFiles"=> [".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"], /* 上传视频格式显示 */

            /* 上传文件配置 */
            "fileActionName"=> "uploadfile", /* controller里,执行上传视频的action名称 */
            "fileFieldName"=> "upfile", /* 提交的文件表单名称 */
            "filePathFormat"=> $path_pre."/uploads/file/{yyyy}-{mm}-{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "fileUrlPrefix"=> "", /* 文件访问路径前缀 */
            "fileMaxSize"=> self::$setting['file_size'], /* 上传大小限制，单位B，默认50MB */
            "fileAllowFiles"=> [
                ".png", ".jpg", ".jpeg", ".gif", ".bmp",
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
                ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
                ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
            ], /* 上传文件格式显示 */

            /* 列出指定目录下的图片 */
            "imageManagerActionName"=> "listimage", /* 执行图片管理的action名称 */
            "imageManagerListPath"=> $path_pre."/uploads/image/", /* 指定要列出图片的目录 */
            "imageManagerListSize"=> 20, /* 每次列出文件数量 */
            "imageManagerUrlPrefix"=> "", /* 图片访问路径前缀 */
            "imageManagerInsertAlign"=> "none", /* 插入的图片浮动方式 */
            "imageManagerAllowFiles"=> [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 列出的文件类型 */

            /* 列出指定目录下的文件 */
            "fileManagerActionName"=> "listfile", /* 执行文件管理的action名称 */
            "fileManagerListPath"=>$path_pre."/uploads/file/", /* 指定要列出文件的目录 */
            "fileManagerUrlPrefix"=> "", /* 文件访问路径前缀 */
            "fileManagerListSize"=>20, /* 每次列出文件数量 */
            "fileManagerAllowFiles"=> [
                ".png", ".jpg", ".jpeg", ".gif", ".bmp",
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
                ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
                ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
            ] /* 列出的文件类型 */
        ];
    }
}