<?php
/**
 * Created by PhpStorm.
 * Script Name: BotMember.php
 * Create: 2025/4/24 下午9:07
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\event;

use app\common\service\Media as MediaService;
use app\constants\Bot as BotConst;
use app\common\service\BotMember as MemberService;
use ky\Logger;

class Media
{

    /**
     * 上传到cdn
     * @param $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function refresh($params)
    {
        /**
         * @var \think\queue\Job
         */
        $job = $params['job'];
        if ($job->attempts() > 2) {
            $job->delete();
        }
        $bot = $params['bot'];
        $media = $params['media'];
        $media_type = $params['media_type'];
        $need_cdn = ['image' => 1, 'video' => 4, 'file' => 5];

        if($bot['protocol'] == BotConst::PROTOCOL_EASYCOM && isset($need_cdn[$media_type])){
            $bot_client = \app\common\service\Bot::model()->getRobotClient($bot);
            $res = $bot_client->uploadCdn([
                'url' => $media['url'],
                'file_type' => $need_cdn[$media_type]
            ]);
            if(empty($res['code'])){
                Logger::error($res['errmsg']);
            }else{
                MediaService::types($media_type)->updateOne([
                    'admin_id' => $bot['admin_id'],
                    'id' => $media['id'],
                    'detail' => json_encode($res['data'])
                ]);
            }
        }

        MediaService::getMedia([
            'media_type' => $media_type,
            'staff_id' => $bot['staff_id'],
            'admin_id' => $bot['admin_id'],
            'media_id' => $media['id']
        ], true);

        $job->delete();
    }
}