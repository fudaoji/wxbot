<?php
/**
 * Created by PhpStorm.
 * Script Name: GroupMember.php
 * Create: 2025/4/15 下午9:17
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\event;
use app\common\service\BotGroupMember as GroupMemberService;

class GroupMember
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
        $member = $params['member'];
        $group = $params['group'];
        $bot = $params['bot'];

        GroupMemberService::insertOrUpdate($member, $group, $bot);
        $job->delete();
    }

}