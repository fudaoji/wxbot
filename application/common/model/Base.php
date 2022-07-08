<?php
/**
 * Created by PhpStorm.
 * Script Name: model.php
 * Create: 2020/8/21 16:26
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;

use ky\BaseModel;

class Base extends BaseModel
{
    /**
     * 手动设置分表key，因为多表关联查询时会遇到
     * Author: fudaoji<fdj@kuryun.cn>
     * @param string $key
     * @return Base
     */
    public function setKey($key = ''){
        $this->key = $key;
        return $this;
    }
}