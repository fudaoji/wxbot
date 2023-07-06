<?php
/**
 * Created by PhpStorm.
 * Script Name: MemberTag.php
 * Create: 4/22/23 11:12 AM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;


class MemberTag extends Base
{

    /**
     * {title:title ...}
     * @param int $bot_id
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function getTitleToTitle($bot_id = 0){
        $list = $this->getField(['title'], ['bot_id' => $bot_id]);
        return  array_combine($list, $list);
    }
}