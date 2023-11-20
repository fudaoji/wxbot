<?php
/**
 * Created by PhpStorm.
 * Script Name: AdminLog.php
 * Create: 2023/7/6 10:09
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;

use ky\BaseModel;

class MsgLog extends BaseModel
{
    protected $isCache = true;
    protected $updateTime = false;
    protected $isPartition = true;
    protected $key = 'year';

    public function getPartition($data)
    {
        return 'p'.$data[$this->key];
    }
}