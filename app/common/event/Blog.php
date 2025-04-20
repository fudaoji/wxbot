<?php
/**
 * Created by PhpStorm.
 * Script Name: GroupMember.php
 * Create: 2025/4/15 下午9:17
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\event;
use app\common\service\Blog as BlogService;

class Blog
{

    /**
     * 生成seo
     * @param $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function generateSEO($params)
    {
        /**
         * @var \think\queue\Job
         */
        $job = $params['job'];
        if ($job->attempts() > 2) {
            $job->delete();
        }
        $blog = $params['blog'];

        BlogService::generateSEO($blog);
        $job->delete();
    }

    public function incViewNum($params)
    {
        /**
         * @var \think\queue\Job
         */
        $job = $params['job'];
        if ($job->attempts() > 2) {
            $job->delete();
        }
        $blog = $params['blog'];

        BlogService::incViewNum($blog);
        $job->delete();
    }

}