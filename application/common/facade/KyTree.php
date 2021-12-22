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
 * Script Name: KyTree.php
 * Create: 2020/5/23 下午10:19
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\facade;
use think\Facade;

class KyTree extends Facade
{

    protected static function getFacadeClass()
    {
        return 'ky\Tree';
    }
}