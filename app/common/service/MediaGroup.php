<?php
/**
 * Created by PhpStorm.
 * Script Name: MediaGroup.php
 * Create: 2023/5/15 8:15
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;

class MediaGroup
{

    /**
     * Get hash list [{id:title, ...}]
     * @param array $where
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getIdToTitle($where = []){
        return model('mediaGroup')->getField(['id', 'title'], $where);
    }
}