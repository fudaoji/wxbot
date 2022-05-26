<?php
/**
 * Created by PhpStorm.
 * Script Name: Tpzs.php
 * Create: 2022/3/28 11:38
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\hanzi;

use app\common\model\Base;

class Hanzi extends Base
{
    public function __construct($data = [])
    {
        $this->table = config('database.prefix').'hanzi_'.$this->table;
        parent::__construct($data);
    }
}