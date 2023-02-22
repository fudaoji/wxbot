<?php
/**
 * Created by PhpStorm.
 * Script Name: BotApply.php
 * Create: 2023/2/22 9:16
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;


use app\constants\Common;

class BotApply extends Base
{

    /**
     * 获取审核通过的微信号
     * @param int $admin_id
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getActiveWx($admin_id = 0){
        $data = self::where('admin_id', $admin_id)
            ->where('deadline', '>', time())
            ->where('status', Common::VERIFY_SUCCESS)
            ->column('wx_num');
        return $data;
    }
}