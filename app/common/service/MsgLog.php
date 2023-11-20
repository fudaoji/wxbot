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

class MsgLog
{
    static $model = null;

    static function model(){
        if(is_null(self::$model)){
            self::$model = new LogM();
        }
        return self::$model;
    }

    static function saveData($params = []){
        $content = $params['content'];
        $bot = $params['bot'];

        $rules = GatherService::check([
            'bot_id' => $bot['id'],
            'wxid' => empty($params['group_wxid']) ? $params['from_wxid'] : $params['group_wxid'],
            'type' => $content['type']
        ]);

        if(count($rules)){
            $insert = [
                'admin_id' => $bot['staff_id'],
                'bot_id' => $bot['id'],
                'year' => intval(date('Y')),
                'msg_id' => $content['msg_id'],
                'content' => $content['msg'],
                'from_wxid' => $params['from_wxid'],
                'from_nickname' => $params['from_nickname'],
                'group_wxid' => $params['group_wxid'],
                'group_nickname' => $params['group_nickname'],
                'msg_type' => $content['type']
            ];
            self::model()->addOne($insert);
            $bot_client = model('admin/bot')->getRobotClient($bot);

            foreach ($rules as $rule){
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
                    case BotConst::MSG_VIDEO:
                    case BotConst::MSG_FILE:
                    case BotConst::MSG_IMG:
                        $lens = [BotConst::MSG_FILE => 6, BotConst::MSG_IMG => 5, BotConst::MSG_VIDEO => 5];
                        $path = mb_substr($content['msg'], $lens[$content['type']], -1);
                        $count = 0;
                        do{
                            $count++;
                            if($count > 5){
                                break;
                            }
                            $res = $bot_client->downloadFile(['path' => $path]);
                            sleep(3);
                        }while(empty($res['ReturnStr']));

                        $filename = basename(str_replace("\\", "/",$path));
                        if(!empty($res['ReturnStr'])){
                            $base64 = $res['ReturnStr'];
                            $url = upload_base64(rand(1000, 9999) . time() . $filename, $base64);
                        }else{
                            $url = $path;
                        }

                        $media_data['title'] = $filename;
                        $media_data['url'] = $url;
                        break;
                    case BotConst::MSG_APP:
                        $media_data['title'] = XmlMini::getInstance($content['msg'])->getTitle();
                        $media_data['content'] = $content['msg'];
                        break;
                    default:
                        $media_data['content'] = $content['msg'];
                        break;
                }
                $media_model->addOne($media_data);
            }
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