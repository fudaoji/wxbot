<?php
/**
 * Created by PhpStorm.
 * Script Name: Tpzs.php
 * Create: 2022/3/28 11:38
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\zdjr;

use app\common\model\Base;

class Zdjr extends Base
{
    protected $isCache = true;

    public function __construct($data = [])
    {
        $this->table = $this->getTablePrefix() . 'zdjr_'.$this->table;
        parent::__construct($data);
    }
}