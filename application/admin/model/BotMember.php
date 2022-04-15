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
use app\constants\Bot;
use ky\Bot\Vlw;
use ky\Bot\Wx;
use ky\Bot\Wxwork;
use ky\Logger;

class BotMember extends Base
{
    protected $isCache = true;

    /**
     * 拉取最新群组列表
     * @param $bot
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException Author: fudaoji<fdj@kuryun.cn>
     */
    public function pullGroups($bot){
        /**
         * @var $client Vlw|Wxwork
         */
        $client = model('bot')->getRobotClient($bot);
        switch ($bot['protocol']) {
            case Bot::PROTOCOL_WEB:
                $bot_client = new Wx(['app_key' => $bot['app_key']]);
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
                break;
            case Bot::PROTOCOL_WXWORK:
                $res = $client->getGroups(['data' => ['robot_wxid' => $bot['uin']]]);

                if($res['code'] && !empty($res['ReturnJson']['data'])){
                    $list = $res['ReturnJson']['data'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nickname']);
                        $wxid = $v['conversation_id'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'])){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                            ]);
                        }else{
                            $this->addOne([
                                'uin' => $bot['uin'],
                                'nickname' => $nickname,
                                'wxid' => $wxid,
                                'type' => \app\constants\Bot::GROUP
                            ]);
                        }
                    }
                    //删除无效
                    $this->delByMap(['uin' => $bot['uin'], 'type' => \app\constants\Bot::GROUP, 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                }
                break;
            default:
                $res = $client->getGroups(['data' => ['robot_wxid' => $bot['uin'], 'is_refresh' => 1]]);

                if($res['code'] && count($res['ReturnJson'])){
                    $list = $res['ReturnJson'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nickname']);
                        $wxid = $v['wxid'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'])){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                            ]);
                        }else{
                            $this->addOne([
                                'uin' => $bot['uin'],
                                'nickname' => $nickname,
                                'wxid' => $wxid,
                                'type' => \app\constants\Bot::GROUP
                            ]);
                        }
                    }
                    //删除无效
                    $this->delByMap(['uin' => $bot['uin'], 'type' => \app\constants\Bot::GROUP, 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                }
                break;
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
        /**
         * @var $client Vlw|Wxwork
         */
        $client = model('bot')->getRobotClient($bot);
        switch ($bot['protocol']){
            case Bot::PROTOCOL_WEB:
                $bot_client = new Wx(['app_key' => $bot['app_key']]);
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
            break;
            case Bot::PROTOCOL_WXWORK:
                $res = $client->getFriends(['data' => ['robot_wxid' => $bot['uin']]]);
                if($res['code'] && !empty($res['ReturnJson']['data'])){
                    $list = $res['ReturnJson']['data'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['username']);
                        $remark_name = filter_emoji($v['remark']);
                        $username = $v['conversation_id'];
                        $wxid = $v['user_id'];
                        $wxid_arr[] = $wxid;
                        $internal = $v['internal'];
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'])){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $username,
                                'internal' => $internal
                            ]);
                        }else{
                            $this->addOne([
                                'uin' => $bot['uin'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $username,
                                'wxid' => $wxid,
                                'type' => \app\constants\Bot::FRIEND,
                                'internal' => $internal
                            ]);
                        }
                    }
                    //删除无效好友
                    $this->delByMap(['uin' => $bot['uin'],'type' => \app\constants\Bot::FRIEND, 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                }
                break;
            default:
                $res = $client->getFriends(['data' => ['robot_wxid' => $bot['uin'], 'is_refresh' => 1]]);

                if($res['code'] && count($res['ReturnJson'])){
                    $list = $res['ReturnJson'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nickname']);
                        $remark_name = filter_emoji($v['note']);
                        $username = $v['wx_num'];
                        $wxid = $v['wxid'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'])){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $username
                            ]);
                        }else{
                            $this->addOne([
                                'uin' => $bot['uin'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $username,
                                'wxid' => $wxid,
                                'type' => \app\constants\Bot::FRIEND
                            ]);
                        }
                    }
                    //删除无效好友
                    $this->delByMap(['uin' => $bot['uin'],'type' => \app\constants\Bot::FRIEND, 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                }
                break;
        }
        return 0;
    }

    /**
     * 保存新好友
     * @param array $params
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function addFriend($params = [])
    {
        $bot = $params['bot'];
        $insert = [
            'uin' => $bot['uin'],
            'nickname' => $params['nickname'],
            'username' => empty($params['username']) ? '' : $params['username'],
            'wxid' => $params['wxid'],
            'type' => \app\constants\Bot::FRIEND,
            'internal' => 1
        ];
        switch ($bot['protocol']){
            case Bot::PROTOCOL_WXWORK:
                $insert['internal'] = 2;
                break;
        }
        $this->addOne($insert);
        //refresh
        return $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $params['wxid']], true, true);
    }
}