<?php
/**
 * Created by PhpStorm.
 * Script Name: Common.php
 * Create: 2022/7/21 14:42
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\validate;

use think\Validate;

class Common extends Validate
{
    protected function __construct()
    {
    }

    protected $rule = [
        'mobile' =>  'mobile',
        'refresh'  => 'require|integer|in:0,1',
        'current_page'  => 'require|integer|min:1',
        'page_size'  => 'require|integer|min:1',
    ];

    //错误消息
    protected $message  =   [
        'refresh' => 'refresh参数错误',
        'current_page' => 'current_page参数错误',
        'page_size' => 'page_size参数错误',
        'mobile' =>  '手机号不合法',
    ];

    /**
     * 常规列表页
     * @return Common
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function scenePage()
    {
        return $this->only(['current_page','page_size', 'refresh']);
    }

    /**
     * refresh
     * @return Common
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sceneRefresh()
    {
        return $this->only(['refresh']);
    }
}