<?php
/**
 * Created by PhpStorm.
 * Script Name: Project.php
 * Create: 2022/9/8 17:04
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\zdjr;


class Project extends Zdjr
{
    protected $table = 'project';
    protected $isCache = false;

    public function getProjects($where = []){
        $where = array_merge(['status' => 1], $where);
        return $this->getField('id,title', $where);
    }
}