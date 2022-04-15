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

class BotGroupmember extends Base
{
    /**
     * 新增群员回调
     * @param array $params
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function addMember($params = []){
        if(!$gm = $this->getOneByMap(['bot_id' => $params['bot_id'], 'group_id' => $params['group_id'],'wxid' => $params['wxid']])){
            $this->addOne($params);
            //todo 记录进群统计数据
        }else{
            $params['id'] = $gm['id'];
            $this->updateOne($params);
        }
    }

    /**
     * 移除群员回调
     * @param array $params
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function rmMember($params = []){
        $this->delByMap(['bot_id' => $params['bot_id'], 'wxid' => $params['wxid']]);
        //todo 记录退群统计数据
    }

    /**
     * 拉取最新群成员
     * @param $bot
     * @param $group
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException Author: fudaoji<fdj@kuryun.cn>
     */
    public function pullMembers($bot, $group){
        /**
         * @var $bot_client Vlw|Wxwork
         */
        $bot_client = model('admin/bot')->getRobotClient($bot);
        switch ($bot['protocol']) {
            case Bot::PROTOCOL_WXWORK:
                $res = $bot_client->getGroupMember([
                    'robot_wxid' => $bot['uin'],
                    'group_wxid' => $group['wxid'],
                ]);

                if($res['code'] && !empty($res['ReturnJson']['data']['member_list'])) {
                    $list = $res['ReturnJson']['data']['member_list'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nickname']);
                        $username = filter_emoji($v['username']);
                        $nickname = $nickname ? $nickname : $username;
                        $wxid = $v['user_id'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['group_id' => $group['id'], 'wxid' => $wxid], ['id'])){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $username,
                                'group_nickname' => $nickname,
                            ]);
                        }else{
                            $this->addOne([
                                'bot_id' => $bot['id'],
                                'group_id' => $group['id'],
                                'nickname' => $username,
                                'group_nickname' => $nickname,
                                'wxid' => $wxid
                            ]);
                        }
                    }
                    //删除无效数据
                    $this->delByMap(['group_id' => $group['id'], 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                } else{
                    Logger::error("pullGroupMembers:" . json_encode($res, JSON_UNESCAPED_UNICODE));
                }
                break;
            case Bot::PROTOCOL_VLW:
                $res = $bot_client->getGroupMember([
                        'robot_wxid' => $bot['uin'],
                        'group_wxid' => $group['wxid'],
                        'is_refresh' => 1
                    ]);

                if($res['code'] && count($res['ReturnJson'])) {
                    $list = $res['ReturnJson']['member_list'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nickname']);
                        $group_nickname = filter_emoji($v['group_nickname']);
                        $username = $v['wx_num'];
                        $wxid = $v['wxid'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['group_id' => $group['id'], 'wxid' => $wxid], ['id'])){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'group_nickname' => $group_nickname,
                                'username' => $username
                            ]);
                        }else{
                            $this->addOne([
                                'bot_id' => $bot['id'],
                                'group_id' => $group['id'],
                                'nickname' => $nickname,
                                'group_nickname' => $group_nickname,
                                'username' => $username,
                                'wxid' => $wxid
                            ]);
                        }
                    }
                    //删除无效数据
                    $this->delByMap(['group_id' => $group['id'], 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                } else{
                    Logger::error("pullGroupMembers:" . json_encode($res, JSON_UNESCAPED_UNICODE));
                }
                break;
            default:
                $bot_client = new Wx(['appKey' => $bot['app_key']]);
                $res = $bot_client->pullGroupMembers([
                    'uuid' => $bot['uuid'],
                    'data' => ['group' => $group['username']]
                ]);
                //Logger::record("pullGroupMembers:" . $res['msg']);
                if($res['code'] && !empty($res['data']['total'])){
                    $list = $res['data']['members'];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nick_name']);
                        $remark_name = filter_emoji($v['remark_name']);
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'nickname' => $nickname, 'remark_name' => $remark_name,'group_id' => $group['id']])){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $v['user_name']
                            ]);
                        }else{
                            $this->addOne([
                                'uin' => $bot['uin'],
                                'group_id' => $group['id'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $v['user_name']
                            ]);
                        }
                    }
                    //删除无效数据
                    $this->delByMap(['uin' => $bot['uin'],'group_id' => $group['id'], 'update_time' => ['lt', time() - 120]]);
                    return count($list);
                }else{
                    Logger::error("pullGroupMembers:" . $res['msg']);
                }
                break;
        }

        return 0;
    }
}