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
 * Create: 2020/5/27 上午12:47
 * Description: 上传控制器
 * Author: fudaoji<fdj@kuryun.cn>
 */
namespace app\admin\controller;

use app\common\model\Upload;

class Uploader extends Base
{
    /**
     * @var \app\common\model\Upload
     */
    protected $model;
    public function initialize(){
        parent::initialize();
        $this->model = new Upload();
        config('log', []);
    }

    /**
     * 图片
     * @return mixed
     * @throws \think\exception\DbException
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function image(){
        $field = input('field', ''); //目标input框
        $where = [];

        $data_list = $this->model->page(12, $where, ['id' => 'desc'], 'id,url,title', 1);
        $pager = $data_list->appends([])->render();
        $assign = ['data_list' => $data_list, 'pager' => $pager, 'field' => $field];
        return $this->show($assign, __FUNCTION__);
    }

    /**
     * 上传文件到项目根目录
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function fileToRootPost(){
        if(request()->isPost()){
            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('file');
            // 移动到服务器的上传目录 并且使用原文件名
            $res = $file->validate([
                    'size'=>config('system.upload.file_size'),
                    'ext'=> config('system.upload.file_ext')]
            )->move(ROOT_PATH,'');

            if($res){
                $this->success('上传成功', '', ['src' => '/' . $res->getFilename()]);
            }
            $this->error($file->getError());
        }
    }

    /**
     * 图片上传
     * Author: Doogie <461960962@qq.com>
     */
    public function picturePost()
    {
        $upload_config_pic = Upload::config();
        return self::upload($upload_config_pic);
    }

    /**
     * 文件上传
     * Author: Doogie <461960962@qq.com>
     */
    public function filePost(){
        $upload_config_file = Upload::config('file');
        return self::upload($upload_config_file);
    }

    /**
     * 最终的上传操作
     * @param array $config
     * @return mixed
     * @Author  Doogie<461960962@qq.com>
     */
    private function upload($config = []){
        /* 调用文件上传组件上传文件 */
        $return = $this->model->upload($_FILES, $config, ['uid' => $this->aid]);

        return response()->create($return, 'json')->send();
    }

    /**
     * ueditor的服务端接口
     * @Author: Doogie <461960962@qq.com>
     */
    public function editorPost(){
        $action = input('get.action');
        $ue_config = Upload::ueConfig();
        switch ($action) {
            case 'config':
                $return = $ue_config;
                break;
            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $return = $this->model->ueUpload($action, ['from' => 2, 'uid' => $this->aid]);
                break;

            /* 列出图片 */
            case 'listimage':
                /* 列出文件 */
            case 'listfile':
                $return = $this->model->ueList($action, ['uid' => $this->aid]);
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $return['state'] = '请求地址出错';
                break;

            default:
                $return['state'] = '请求地址出错';
                break;
        }

        return json($return);
    }
}