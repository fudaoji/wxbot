<?php
/**
 * Created by PhpStorm.
 * Script Name: Ai.php
 * Create: 8/1/22 9:35 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\ai;


use app\common\model\Base;

class Ai extends Base
{
    protected $isCache = true;

    public function __construct($data = [])
    {
        $this->table = $this->getTablePrefix() . 'ai_'.$this->table;
        parent::__construct($data);
    }
}