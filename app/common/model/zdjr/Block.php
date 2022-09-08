<?php
/**
 * Created by PhpStorm.
 * Script Name: Block.php
 * Create: 2022/9/6 17:57
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\zdjr;


class Block extends Zdjr
{
    protected $table = 'block';

    /**
     * 获取可用机器人
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getSaveBots($params = []){
        $refresh = isset($params['refresh']) ? $params['refresh'] : false;
        $bots = is_string($params['bots']) ? explode(',', $params['bots']) : $params['bots'];
        $blocks = $this->getField(['bot_id'], ['admin_id' => $params['admin_id'], 'bot_id' => ['in', $bots], 'status' => 1], $refresh);
        return array_diff($bots, $blocks);
    }

    /**
     * 锁定加人频繁的bot
     * @param array $params
     * @return array|bool|false|mixed|\PDOStatement|string|\think\Model
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function blockBot($params = []){
        if($data = $this->getOneByMap(['bot_id' => $params['bot_id']])){
            $data = $this->updateOne(['id' => $data['id'], 'status' => 1]);
        }else{
            $data = $this->addOne([
                'admin_id' => $params['admin_id'],
                'bot_id' => $params['bot_id'],
                'status' => 1
            ]);
        }
        $this->getSaveBots(array_merge($params, ['refresh' => true]));
        return $data;
    }
}