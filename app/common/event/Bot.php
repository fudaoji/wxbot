<?php
/**
 * Created by PhpStorm.
 * Script Name: Bot.php
 * Create: 2022/7/12 11:07
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\event;

use think\Db;
use think\facade\Log;

class Bot extends Base
{
    /**
     * 群成员增减统计
     * Author: fudaoji<fdj@kuryun.cn>
     * @param array $data
     */
    public function tjGroup($data = []){
        /**
         * @var \think\queue\Job
         */
        $job = $data['job'];
        if ($job->attempts() > 2) {
            //通过这个方法可以检查这个任务已经重试了几次了
            $job->delete();
        }
        $field = $data['type'] == 'add' ? 'add_num' : 'decr_num';
        if(! $tj = model('common/TjGroup')->getOneByMap(['group_id' => $data['group_id'], 'day' => $data['day']], true, true)){
            $bot = model('admin/bot')->getOne($data['bot_id']);
            $insert = [
                'admin_id' => $bot['admin_id'],
                'group_id' => $data['group_id'],
                'day' => $data['day'],
                'bot_id' => $data['bot_id'],
                $field => 1
            ];
            model('common/TjGroup')->addOne($insert);
        }else{
            model('common/TjGroup')->updateOne(['id' => $tj['id'],  $field => $tj[$field] + 1]);
        }
        $job->delete();
    }
}