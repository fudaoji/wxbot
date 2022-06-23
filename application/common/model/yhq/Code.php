<?php
/**
 * Created by PhpStorm.
 * Script Name: Coupon.php
 * Create: 2022/4/6 16:05
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\yhq;

class Code extends Yhq
{
    protected $isCache = true;
    protected $table = 'code';

    /**
     * 获取可用码
     * @param array $params
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getCode($params = []){
        $coupon_id = intval($params['coupon_id']);
        $refresh = isset($params['refresh']) ? $params['refresh'] : false;
        return $this->getOneJoin([
            'alias' => 'code',
            'join' => [
                ['yhq_coupon coupon', 'coupon.id=code.coupon_id']
            ],
            'field' => ['code.*'],
            'where' => ['coupon.id' => $coupon_id, 'code.status' => 1],
            'refresh' => $refresh
        ]);
    }

    /**
     * 码标记已使用
     * @param array $params
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\Exception
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function offCode($params = []){
        $code = $this->updateOne(['id' => $params['code_id'], 'wxid' => $params['wxid'], 'send_time' => time()]);
        return $this->getCode([
            'coupon_id' => $code['coupon_id'],
            'refresh' => true
        ]);
    }
}