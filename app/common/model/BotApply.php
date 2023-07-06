<?php
/**
 * Created by PhpStorm.
 * Script Name: BotApply.php
 * Create: 2023/2/22 9:16
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;

use app\admin\model\Admin as AdminM;
use app\constants\Common;

class BotApply extends Base
{

    /**
     * 获取审核通过的微信号
     * @param int $staff_id
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getActiveWx($staff_id = 0){
        $data = self::where('staff_id', $staff_id)
            ->where('deadline', '>', time())
            ->where('status', Common::VERIFY_SUCCESS)
            ->column('wx_num');
        return $data;
    }
}