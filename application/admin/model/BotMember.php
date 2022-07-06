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
use ky\Logger;
use ky\WxBot\Driver\Cat;
use ky\WxBot\Driver\Vlw;
use ky\WxBot\Driver\Wxwork;

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
         * @var $client Wxwork|Cat|Vlw
         */
        $client = model('bot')->getRobotClient($bot);
        $res = $client->getGroups(['data' => ['robot_wxid' => $bot['uin']]]);
        switch ($bot['protocol']) {
            case Bot::PROTOCOL_CAT:
                if($res['code'] && count($res['data'])){
                    $list = $res['data'];
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
                                'type' => Bot::GROUP
                            ]);
                        }
                    }
                    //删除无效
                    $this->delByMap(['uin' => $bot['uin'], 'type' => Bot::GROUP, 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                }
                break;
            case Bot::PROTOCOL_WXWORK:
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
                                'type' => Bot::GROUP
                            ]);
                        }
                    }
                    //删除无效
                    $this->delByMap(['uin' => $bot['uin'], 'type' => Bot::GROUP, 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                }
                break;
            default:
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
                                'type' => Bot::GROUP
                            ]);
                        }
                    }
                    //删除无效
                    $this->delByMap(['uin' => $bot['uin'], 'type' => Bot::GROUP, 'wxid' => ['notin', $wxid_arr]]);
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
         * @var $client Vlw|Wxwork|Cat
         */
        $client = model('bot')->getRobotClient($bot);
        $res = $client->getFriends(['data' => ['robot_wxid' => $bot['uin'], 'is_refresh' => 1]]);
        switch ($bot['protocol']){
            case Bot::PROTOCOL_CAT:
                if($res['code'] && count($res['data'])){
                    $list = $res['data'];
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
            case Bot::PROTOCOL_WXWORK:
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
            'type' => Bot::FRIEND,
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