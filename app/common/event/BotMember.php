<?php
/**
 * Created by PhpStorm.
 * Script Name: BotMember.php
 * Create: 2025/4/24 下午9:07
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\event;


use app\common\service\BotMember as MemberService;

class BotMember
{

    /**
     * 新增或更新
     * @param $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function insertOrUpdate($params)
    {
        /**
         * @var \think\queue\Job
         */
        $job = $params['job'];
        if ($job->attempts() > 2) {
            $job->delete();
        }
        $data = $params['data'];

        MemberService::insertOrUpdate($data);
        $job->delete();
    }
}