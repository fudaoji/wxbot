<?php
/**
 * Created by PhpStorm.
 * Script Name: App.php
 * Create: 2020/5/23 下午4:22
 * Description: 应用相关验证
 * Author: fudaoji<fdj@kuryun.cn>
 */
namespace app\common\validate;

class App extends Common
{
    public function __construct()
    {
        parent::__construct();
        $this->rule = array_merge([
            'type' => 'require',
            'name' => 'require',
            'title' => 'require',
            'version' => 'require',
            'logo' => 'require',
            'author' => 'require'
        ],
            $this->rule
        );
        $this->message = array_merge([
            'type.require' => '应用type不能为空',
            'title.require' => '应用名称不能为空',
            'name.require' => '应用标识不能为空',
            'version.require' => '版本不能为空',
            'logo.require' => 'Logo不能为空',
            'author.require' => '作者信息不能为空'
        ],
            $this->message
        );
    }

    public function sceneAdd()
    {
        return $this->only(['name','title', 'version','logo','author','type']);
    }
}