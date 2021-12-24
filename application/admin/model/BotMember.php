<?php
/**
 * Created by PhpStorm.
 * Script Name: ${FILE_NAME}
 * Create: 12/21/21 12:28 AM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\model;


use app\common\model\Base;
use ky\Bot\Wx;

class BotMember extends Base
{
    /**
     * 拉取最新群组列表
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function pullGroups($bot){
        $bot_client = new Wx(['appKey' => $bot['app_key']]);
        $res = $bot_client->getGroups(['uuid' => $bot['uuid']]);

        if($res['code'] && !empty($res['data']['count'])){
            $list = $res['data']['groups'];
            foreach ($list as $k => $v){
                $nickname = filter_emoji($v['nick_name']);
                $remark_name = filter_emoji($v['remark_name']);
                if($data = $this->getOneByMap(['uin' => $bot['uin'], 'nickname' => $nickname, 'remark_name' => $remark_name])){
                    $this->updateOne([
                        'id' => $data['id'],
                        'nickname' => $nickname,
                        'remark_name' => $remark_name,
                        'username' => $v['user_name'],
                        'alias' => $v['alias']
                    ]);
                }else{
                    $this->addOne([
                        'uin' => $bot['uin'],
                        'nickname' => $nickname,
                        'remark_name' => $remark_name,
                        'username' => $v['user_name'],
                        'alias' => $v['alias'],
                        'type' => \app\constants\Bot::GROUP
                    ]);
                }
            }
            //删除无效群组
            $this->delByMap(['uin' => $bot['uin'], 'type' => \app\constants\Bot::GROUP, 'update_time' => ['lt', time() - 120]]);
            return count($list);
        }
        return 0;
    }

    /**
     * 拉取好友
     * @param $bot
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function pullFriends($bot){
        $bot_client = new Wx(['appKey' => $bot['app_key']]);
        $res = $bot_client->getFriends(['uuid' => $bot['uuid']]);

        if($res['code'] && !empty($res['data']['count'])){
            $list = $res['data']['friends'];
            $nickname_arr = [];
            foreach ($list as $k => $v){
                $nickname = filter_emoji($v['nick_name']);
                $remark_name = filter_emoji($v['remark_name']);
                $nickname_arr[] = $nickname;
                if($data = $this->getOneByMap(['uin' => $bot['uin'], 'nickname' => $nickname, 'remark_name' => $remark_name])){
                    $this->updateOne([
                        'id' => $data['id'],
                        'nickname' => $nickname,
                        'remark_name' => $remark_name,
                        'username' => $v['user_name'],
                        'alias' => $v['alias']
                    ]);
                }else{
                    $this->addOne([
                        'uin' => $bot['uin'],
                        'nickname' => $nickname,
                        'remark_name' => $remark_name,
                        'username' => $v['user_name'],
                        'alias' => $v['alias'],
                        'type' => \app\constants\Bot::FRIEND
                    ]);
                }
            }
            //删除无效好友
            $this->delByMap(['uin' => $bot['uin'],'type' => \app\constants\Bot::FRIEND, 'update_time' => ['lt', time() - 120]]);
            return count($list);
        }
        return 0;
    }
}