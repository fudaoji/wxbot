<?php
/**
 * Created by PhpStorm.
 * Script Name: MediaGather.php
 * Create: 2023/11/20 10:09
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;
use app\common\model\MsgLog as LogM;
use app\constants\Bot as BotConst;
use app\common\service\MsgGather as GatherService;
use ky\Logger;
use ky\WxBot\Driver\Extian;

class MsgLog
{
    static $model = null;

    static function model(){
        if(is_null(self::$model)){
            self::$model = new LogM();
        }
        return self::$model;
    }

    function addLogTask($params = []){
        /**
         * @var \think\queue\Job
         */
        $job = $params['job'];
        if ($job->attempts() > 2) {
            $job->delete();
        }
        self::saveData($params);
        $job->delete();
    }

    /**
     * 保存消息
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function saveData($params = []){
        $content = $params['content'];
        $bot = $params['bot'];
        $rules = GatherService::check([
            'bot_id' => $bot['id'],
            'wxid' => empty($params['group_wxid']) ? $params['from_wxid'] : $params['group_wxid'],
            'type' => $content['type'],
            'content' => $content
        ]);
        //Logger::error(count($rules));
        if(count($rules)){
            $log_content = $content['msg'];

            /**
             * @var $bot_client Extian
             */
            $bot_client = model('admin/bot')->getRobotClient($bot);

            foreach ($rules as $rule){
                //Logger::error($rule);
                if(empty($rule['to_media']) || !in_array($content['type'], [
                    BotConst::MSG_TEXT, BotConst::MSG_APP, BotConst::MSG_IMG, BotConst::MSG_FILE, BotConst::MSG_VIDEO
                    ])){
                    continue;
                }
                $media_model = Media::msgTypeToModel($content['type']);
                $media_data = [
                    'admin_id' => $bot['staff_id'],
                ];

                switch ($content['type']){
                    case BotConst::MSG_IMG:
                        $res = $bot_client->downloadFile(['path' => $content['msg_id']]);
                        if(!empty($res['data']['data'])){
                            $filename = rand(1000, 9999) . time() . '.png';
                            $url = upload_base64($filename, $res['data']['data']);
                            $media_data['title'] = $filename;
                            $media_data['url'] = $url;

                            $log_content = $url;
                        }else{
                            continue 2;
                        }
                        break;
                    case BotConst::MSG_VIDEO:
                    case BotConst::MSG_FILE:
                    case BotConst::MSG_APP:
                        $media_model = model('MediaXml');
                        $media_data['title'] = XmlMini::getInstance($content['msg'])->getTitle();
                        $media_data['content'] = $content['msg'];
                        //Logger::error($media_data['title']);
                        break;
                    case BotConst::MSG_TEXT:
                        $media_data['content'] = $content['msg'];
                        break;
                }
                $media_model->addOne($media_data);
            }
            $insert = [
                'admin_id' => $bot['staff_id'],
                'bot_id' => $bot['id'],
                'year' => intval(date('Y')),
                'msg_id' => $content['msg_id'],
                'content' => $log_content,
                'from_wxid' => $params['from_wxid'],
                'from_nickname' => $params['from_nickname'],
                'group_wxid' => $params['group_wxid'],
                'group_nickname' => $params['group_nickname'],
                'msg_type' => $content['type'],
                'gather_id' => $rules[0]['id'] ?? 0
            ];

            self::model()->addOne($insert);
        }
    }

    /**
     * 搜索规则
     * @param $where
     * @param bool $refresh
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function searchData($where, $refresh = false){
        $cache_key = serialize($where);
        $data = cache($cache_key);
        if(empty($data) || $refresh){
            $data = self::model()->getAll([
                'where' => $where
            ]);
        }
        cache($cache_key, $data);
        return $data;
    }
}