<?php
/**
 * Created by PhpStorm.
 * Script Name: Tpzs.php
 * Create: 2022/3/28 11:38
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\tpzs;

use app\common\model\Base;

class Tpzs extends Base
{
    public function __construct($data = [])
    {
        $this->table = $this->getTablePrefix() .'tpzs_'.$this->table;
        parent::__construct($data);
    }
}