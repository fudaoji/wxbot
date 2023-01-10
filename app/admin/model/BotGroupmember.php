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
use ky\WxBot\Driver\Cat;
use ky\WxBot\Driver\My;
use ky\WxBot\Driver\Mycom;
use ky\WxBot\Driver\Vlw;
use ky\WxBot\Driver\Webgo;
use ky\WxBot\Driver\Xbot;
use ky\WxBot\Wx;
use ky\WxBot\Driver\Wxwork;
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
            //记录进群统计数据
            invoke('\\app\\common\\event\\TaskQueue')->push([
                'delay' => 10,
                'params' => [
                    'do' => ['\\app\\common\\event\\Bot', 'tjGroup'],
                    'bot_id' => $params['bot_id'],
                    'day' => date('Y-m-d'),
                    'group_id' => $params['group_id'],
                    'type' => 'add'
                ]
            ]);
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
        //记录退群统计数据
        invoke('\\app\\common\\event\\TaskQueue')->push([
            'delay' => 10,
            'params' => [
                'do' => ['\\app\\common\\event\\Bot', 'tjGroup'],
                'bot_id' => $params['bot_id'],
                'day' => date('Y-m-d'),
                'group_id' => $params['group_id'],
                'type' => 'decr'
            ]
        ]);
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
         * @var $bot_client Vlw|Wxwork|Cat|Webgo|My|Mycom|Xbot
         */
        $bot_client = model('admin/bot')->getRobotClient($bot);
        $res = $bot_client->getGroupMembers([
            'robot_wxid' => $bot['uin'],
            'uuid' => $bot['uuid'],
            'group_wxid' => $group['wxid'],
        ]);
        switch ($bot['protocol']) {
            case Bot::PROTOCOL_XBOT:
                if($res['code'] && count($res['data']['member_list'])) {
                    $list = $res['data']['member_list'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nickname']);
                        $group_nickname = filter_emoji($v['display_name']);
                        $username = $v['account'];
                        $headimgurl = $v['avatar'];
                        $wxid = $v['wxid'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['group_id' => $group['id'], 'wxid' => $wxid], ['id'])){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'group_nickname' => $group_nickname,
                                'username' => $username,
                                'headimgurl' => $headimgurl
                            ]);
                        }else{
                            $this->addOne([
                                'bot_id' => $bot['id'],
                                'group_id' => $group['id'],
                                'nickname' => $nickname,
                                'group_nickname' => $group_nickname,
                                'username' => $username,
                                'wxid' => $wxid,
                                'headimgurl' => $headimgurl
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
            case Bot::PROTOCOL_WEB:
                if($res['code'] && !empty($res['data']['total'])){
                    $list = $res['data']['members'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nick_name']);
                        $remark_name = filter_emoji($v['remark_name']);
                        $username = $v['user_name'];
                        $wxid = $username;
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['group_id' => $group['id'], 'wxid' => $wxid], ['id'])){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'group_nickname' => $remark_name,
                                'username' => $username,
                                'wxid' => $wxid
                            ]);
                        }else{
                            $this->addOne([
                                'bot_id' => $bot['id'],
                                'group_id' => $group['id'],
                                'nickname' => $nickname,
                                'group_nickname' => $remark_name,
                                'username' => $username,
                                'wxid' => $wxid
                            ]);
                        }
                    }
                    //删除无效数据
                    $this->delByMap(['group_id' => $group['id'], 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                }else{
                    Logger::error("pullGroupMembers:" . $res['msg']);
                }
                break;
            case Bot::PROTOCOL_CAT:
                if($res['code'] && count($res['data'])) {
                    $list = $res['data'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nickname']);
                        $group_nickname = $nickname;
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
            case Bot::PROTOCOL_WXWORK:
            case Bot::PROTOCOL_MYCOM:
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
            case Bot::PROTOCOL_MY:
            case Bot::PROTOCOL_VLW:
                if($res['code'] && count($res['ReturnJson'])) {
                    $list = $res['ReturnJson']['member_list'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        // Logger::write("拉取群成员" . json_encode($v) . "\n");
                        $nickname = filter_emoji($v['nickname']);
                        $group_nickname = filter_emoji($v['nickname']);
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
                if($res['code'] && count($res['data'])) {
                    $list = $res['data'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = empty($v['nickname']) ? '' : filter_emoji($v['nickname']);
                        $group_nickname = empty($v['group_nickname']) ? $nickname : filter_emoji($v['group_nickname']);
                        $username = empty($v['username']) ? '' : $v['username'];
                        $wxid = $v['wxid'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['group_id' => $group['id'], 'wxid' => $wxid], ['id'])){
                            $update = [
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'group_nickname' => $group_nickname,
                                'username' => $username
                            ];
                            $nickname && $update['nickname'] = $nickname;
                            $group_nickname && $update['group_nickname'] = $group_nickname;
                            $username && $update['username'] = $username;
                            $this->updateOne($update);
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
        }

        return 0;
    }
}