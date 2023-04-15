<?php
/**
 * Created by PhpStorm.
 * Script Name: Moments.php
 * Create: 2022/9/16 14:10
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;


use app\constants\Media;
use ky\WxBot\Driver\My;

class Moments extends Base
{

    /**
     * 发送朋友圈
     * @param array $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\Exception
     */
    public function publishMoments($data = []){
        if(!empty($data['media_id'])){
            if($data['media_type'] == Media::IMAGE ){
                $media = model('media_' . $data['media_type'])->getField('url', [
                    'admin_id' => $data['admin_id'],
                    'id' => ['in', $data['media_id']]
                ], true);
            }else{
                $media = model('media_' . $data['media_type'])->getOneByMap([
                    'admin_id' => $data['admin_id'],
                    'id' => $data['media_id']
                ], true, true);
            }
        }
        $bots = explode(',', $data['bot_id']);
        foreach ($bots as $bot_id){
            if(! $bot = model('admin/bot')->getOne($bot_id)){
                continue;
            }
            /**
             * @var $client My
             */
            $client = model('admin/bot')->getRobotClient($bot);
            switch ($data['media_type']){
                case Media::IMAGE:
                    $send_res = $client->sendMomentsImg([
                        'robot_wxid' => $bot['uin'],
                        'content' => $data['content'],
                        'img' => implode(',', $media)
                    ]);
                    break;
                case Media::VIDEO:
                    $send_res = $client->sendMomentsVideo([
                        'robot_wxid' => $bot['uin'],
                        'content' => $data['content'],
                        'video' => $media['url']
                    ]);
                    break;
                case Media::LINK:
                    $send_res = $client->sendMomentsLink([
                        'robot_wxid' => $bot['uin'],
                        'content' => $data['content'],
                        'title' => $media['title'],
                        'img' => $media['image_url'],
                        'url' => $media['url']
                    ]);
                    break;
                default:
                    $send_res = $client->sendMomentsText([
                        'robot_wxid' => $bot['uin'],
                        'content' => $data['content']
                    ]);
                    break;
            }
        }
        $data = $this->updateOne(['id' => $data['id'], 'publish_time' => time()]);
        return $data;
    }
}