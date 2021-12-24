<?php
/**
 * Created by PhpStorm.
 * Script Name: Bot.php
 * Create: 12/24/21 10:45 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\task;


use think\Db;
use think\facade\Log;

class Bot extends Base
{

    /**
     * 下拉通讯录
     * @param $params
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function pullMembers($params){
        /**
         * @var \think\queue\Job
         */
        $job = $params['job'];
        $bot = $params['bot'];
        model('admin/BotMember')->pullFriends($bot);
        model('admin/BotMember')->pullGroups($bot);

        $job->delete();
    }
}