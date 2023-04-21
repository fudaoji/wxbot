<?php
/**
 * Created by PhpStorm.
 * Script Name: Ai.php
 * Create: 8/1/22 9:35 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\bgf;


use app\common\model\Base;

class Bgf extends Base
{
    protected $isCache = false;

    public function __construct($data = [])
    {
        $this->table = $this->getTablePrefix() . 'bgf_'.$this->table;
        parent::__construct($data);
    }
}