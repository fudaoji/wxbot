<?php
/**
 * Created by PhpStorm.
 * Script Name: Tpzs.php
 * Create: 2022/3/28 11:38
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\kefu;

use app\common\model\Base;

class Kefu extends Base
{
    protected $isCache = false;

    public function __construct($data = [])
    {
        $this->table = $this->getTablePrefix() . 'kefu_'.$this->table;
        parent::__construct($data);
    }
}