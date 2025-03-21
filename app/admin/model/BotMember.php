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
use ky\WxBot\Driver\Extian;
use ky\WxBot\Driver\My;
use ky\WxBot\Driver\Mycom;
use ky\WxBot\Driver\Qianxun;
use ky\WxBot\Driver\Vlw;
use ky\WxBot\Driver\Webgo;
use ky\WxBot\Driver\Wxwork;
use ky\WxBot\Driver\Xbot;
use think\facade\Log;

class BotMember extends Base
{
    protected $isCache = true;

    /**
     * {wxid: headimgurl, ...}
     * @param null $bot_info
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function wxidToHead($bot_info = null){
        is_null($bot_info) && $bot_info = session(SESSION_BOT);
        return $this->getField(['wxid','headimgurl'], ['uin' => $bot_info['uin']], true);
    }

    /**
     * 拉取最新群组列表
     * @param $bot
     * @return int
     * @throws \think\Exception
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function pullGroups($bot){
        /**
         * @var $client Wxwork|Cat|Vlw|Webgo|My|Mycom|Qianxun
         */
        $client = model('admin/bot')->getRobotClient($bot);
        $res = $client->getGroups([
            'data' => ['robot_wxid' => $bot['uin'], 'is_refresh' => 1, 'uuid' => $bot['uuid']]
        ]);

        switch ($bot['protocol']) {
            case Bot::PROTOCOL_EXTIAN:
                if($res['code'] && count($res['data'])){
                    $list = $res['data'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['name']);
                        $wxid = $v['wxid'];
                        $remark_name = filter_emoji($v['reMark']);
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'], true)){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'type' => Bot::GROUP
                            ]);
                        }else{
                            $this->addOne([
                                'uin' => $bot['uin'],
                                'nickname' => $nickname,
                                'wxid' => $wxid,
                                'remark_name' => $remark_name,
                                'type' => Bot::GROUP
                            ]);
                        }
                    }

                    //删除无效
                    $this->delByMap(['uin' => $bot['uin'], 'type' => Bot::GROUP, 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                }
                break;
            case Bot::PROTOCOL_XBOTCOM:
                $page_num = 1;
                $wxid_arr = [];
                while ($res['code'] && !empty($res['data']['roomdata'])){
                    $list = $res['data']['roomdata']['datas'];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['roomname']);
                        $username = 'R:'.$v['roomid'];
                        $headimgurl = $v['roomurl'];
                        $wxid = $v['roomid'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'], true)){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'username' => $username,
                                'headimgurl' => $headimgurl
                            ]);
                        }else{
                            $this->addOne([
                                'uin' => $bot['uin'],
                                'nickname' => $nickname,
                                'username' => $username,
                                'wxid' => $wxid,
                                'type' => Bot::GROUP,
                                'headimgurl' => $headimgurl
                            ]);
                        }
                    }
                    $res = $client->getGroups([
                        'data' => [
                            'robot_wxid' => $bot['uin'], 'uuid' => $bot['uuid'],
                            'start_index' => ++$page_num
                        ]
                    ]);
                }
                //删除无效数据
                $this->delByMap(['uin' => $bot['uin'],'type' => Bot::GROUP, 'wxid' => ['notin', $wxid_arr]]);
                return count($list);
                break;
            case Bot::PROTOCOL_XBOT:
                if($res['code'] && count($res['data'])){
                    $list = $res['data'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nickname']);
                        $wxid = $v['wxid'];
                        $headimgurl = $v['avatar'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'])){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'headimgurl' => $headimgurl
                            ]);
                        }else{
                            $this->addOne([
                                'uin' => $bot['uin'],
                                'nickname' => $nickname,
                                'wxid' => $wxid,
                                'type' => Bot::GROUP,
                                'headimgurl' => $headimgurl
                            ]);
                        }
                    }
                    //删除无效
                    $this->delByMap(['uin' => $bot['uin'], 'type' => Bot::GROUP, 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                }
                break;
            case Bot::PROTOCOL_WEB:
                if($res['code'] && !empty($res['data']['total'])){
                    $list = $res['data']['groups'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nick_name']);
                        $remark_name = filter_emoji($v['remark_name']);
                        $wxid = $v['user_name'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'])){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $v['user_name'],
                                'alias' => $v['alias'],
                                'wxid' => $wxid,
                            ]);
                        }else{
                            $this->addOne([
                                'uin' => $bot['uin'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $v['user_name'],
                                'alias' => $v['alias'],
                                'type' => Bot::GROUP,
                                'wxid' => $wxid,
                            ]);
                        }
                    }
                    //删除无效群组
                    $this->delByMap(['uin' => $bot['uin'], 'type' => Bot::GROUP, 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                }
                break;
            case Bot::PROTOCOL_CAT:
                if($res['code'] && count($res['data'])){
                    $list = $res['data'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nickname']);
                        $wxid = $v['wxid'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'], true)){
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
            case Bot::PROTOCOL_MYCOM:
                if($res['code'] && !empty($res['ReturnJson']['data'])){
                    $list = $res['ReturnJson']['data'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nickname']);
                        $wxid = $v['conversation_id'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'], true)){
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
            case Bot::PROTOCOL_MY:
            case Bot::PROTOCOL_VLW:
                if($res['code'] && !empty($res['ReturnJson']) && count($res['ReturnJson']) && !empty($res['ReturnJson'][0])){
                    $list = $res['ReturnJson'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nickname']);
                        $headimgurl = $v['avatar'];
                        $wxid = $v['wxid'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'], true)){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'headimgurl' => $headimgurl
                            ]);
                        }else{
                            $this->addOne([
                                'uin' => $bot['uin'],
                                'nickname' => $nickname,
                                'wxid' => $wxid,
                                'type' => Bot::GROUP,
                                'headimgurl' => $headimgurl
                            ]);
                        }
                    }
                    //删除无效
                    $this->delByMap(['uin' => $bot['uin'], 'type' => Bot::GROUP, 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                }
                break;
            default:
                if($res['code'] && count($res['data'])){
                    $list = $res['data'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nickname']);
                        $wxid = $v['wxid'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'], true)){
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
                    //删除无效群聊
                    $this->delByMap(['uin' => $bot['uin'],'type' => Bot::GROUP, 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                }
                break;
        }
        return 0;
    }

    /**
     * 是否忽略
     * @param $data
     * @param string $driver
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function isIgnore($data, $driver = Bot::PROTOCOL_EXTIAN){
        switch ($driver){
            default:
                if(!in_array($data['type'], [3, 32771]) || $data['wxid'] == 'weixin' || strpos($data['wxid'], 'gh_') !== false){
                    return true;
                }
        }
        return false;
    }

    /**
     * 拉取好友
     * @param $bot
     * @return int
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function pullFriends($bot){
        /**
         * @var $client Vlw|Wxwork|Cat|Webgo|Qianxun|My|Mycom|Xbot|Extian
         */
        $client = model('admin/bot')->getRobotClient($bot);
        $res = $client->getFriends([
            'data' => ['robot_wxid' => $bot['uin'], 'is_refresh' => 1, 'uuid' => $bot['uuid']]
        ]);

        switch ($bot['protocol']){
            case Bot::PROTOCOL_EXTIAN:
                if($res['code'] && count($res['data'])){
                    $list = $res['data'];
                    $wxid_arr = [];
                    $count = 0;
                    //dump($list);exit;
                    foreach ($list as $k => $v){
                        if($this->isIgnore($v)){
                            continue;
                        }
                        $count++;
                        $nickname = filter_emoji($v['nickName']);
                        $remark_name = filter_emoji($v['reMark']);
                        $username = $v['alias'] ?? '';
                        $headimgurl = $v['headImg'] ?? '';
                        $wxid = $v['wxid'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'], true)){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $username,
                                'headimgurl' => $headimgurl
                            ]);
                        }else{
                            $this->addOne([
                                'uin' => $bot['uin'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $username,
                                'wxid' => $wxid,
                                'type' => Bot::FRIEND,
                                'headimgurl' => $headimgurl
                            ]);
                        }
                    }
                    //删除无效好友
                    $this->delByMap(['uin' => $bot['uin'],'type' => Bot::FRIEND, 'wxid' => ['notin', $wxid_arr]]);
                    //dump($this->getLastSql());exit;
                    return $count;
                }
                break;
            case Bot::PROTOCOL_XBOTCOM:
                $page_num = 1;
                $wxid_arr = [];
                while ($res['code'] && $page_num <= $res['data']['total_page']){
                    $list = $res['data']['user_list'];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['username']);
                        $remark_name = empty($v['nickname']) ? '' : filter_emoji($v['nickname']);
                        $username = $v['conversation_id'];
                        $headimgurl = $v['avatar'];
                        $wxid = $v['user_id'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'], true)){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $username,
                                'headimgurl' => $headimgurl
                            ]);
                        }else{
                            $this->addOne([
                                'uin' => $bot['uin'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $username,
                                'wxid' => $wxid,
                                'type' => Bot::FRIEND,
                                'headimgurl' => $headimgurl
                            ]);
                        }
                    }
                    $res = $client->getFriends([
                        'data' => [
                            'robot_wxid' => $bot['uin'], 'uuid' => $bot['uuid'],
                            'page_num' => ++$page_num
                        ]
                    ]);
                }
                //删除无效好友
                $this->delByMap(['uin' => $bot['uin'],'type' => Bot::FRIEND, 'wxid' => ['notin', $wxid_arr]]);
                return count($list);
                break;
            case Bot::PROTOCOL_XBOT:
                if($res['code'] && count($res['data'])){
                    $list = $res['data'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nickname']);
                        $remark_name = filter_emoji($v['remark']);
                        $username = $v['account'];
                        $headimgurl = $v['avatar'];
                        $wxid = $v['wxid'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'], true)){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $username,
                                'headimgurl' => $headimgurl
                            ]);
                        }else{
                            $this->addOne([
                                'uin' => $bot['uin'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $username,
                                'wxid' => $wxid,
                                'type' => Bot::FRIEND,
                                'headimgurl' => $headimgurl
                            ]);
                        }
                    }
                    //删除无效好友
                    $this->delByMap(['uin' => $bot['uin'],'type' => Bot::FRIEND, 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                }
                break;
            case Bot::PROTOCOL_WEB:
                if($res['code'] && !empty($res['data']['total'])){
                    $list = $res['data']['friends'];
                    $nickname_arr = [];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nick_name']);
                        $remark_name = filter_emoji($v['remark_name']);
                        $nickname_arr[] = $nickname;
                        $wxid = $v['user_name'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'], true)){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $v['user_name'],
                                'alias' => $v['alias'],
                                'wxid' => $wxid,
                            ]);
                        }else{
                            $this->addOne([
                                'uin' => $bot['uin'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $v['user_name'],
                                'alias' => $v['alias'],
                                'type' => Bot::FRIEND,
                                'wxid' => $wxid,
                            ]);
                        }
                    }
                    //删除无效好友
                    $this->delByMap(['uin' => $bot['uin'],'type' => Bot::FRIEND, 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                }
                break;
            case Bot::PROTOCOL_CAT:
                if($res['code'] && count($res['data'])){
                    $list = $res['data'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nickname']);
                        $remark_name = empty($v['remark']) ? '' : filter_emoji($v['remark']) ;
                        $username = $v['wxNum'] ?? '';
                        $wxid = $v['wxid'];
                        $headimgurl = $v['headimgurl'] ?? '';
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'], true)){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $username,
                                'headimgurl' => $headimgurl,
                            ]);
                        }else{
                            $this->addOne([
                                'uin' => $bot['uin'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $username,
                                'wxid' => $wxid,
                                'type' => Bot::FRIEND,
                                'headimgurl' => $headimgurl,
                            ]);
                        }
                    }
                    //删除无效好友
                    $this->delByMap(['uin' => $bot['uin'],'type' => Bot::FRIEND, 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                }
                break;
            case Bot::PROTOCOL_WXWORK:
            case Bot::PROTOCOL_MYCOM:
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
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'], true)){
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
            case Bot::PROTOCOL_MY:
            case Bot::PROTOCOL_VLW:
                if($res['code'] && count($res['ReturnJson'])){
                    $list = $res['ReturnJson'];
                    $wxid_arr = [];
                    //Logger::error($res);
                    foreach ($list as $k => $v){
                        if(empty($v['wxid']))  {
                            unset($list[$k]);
                            continue;
                        }
                        $nickname = filter_emoji($v['nickname'] ?? '');
                        $remark_name = filter_emoji($v['note'] ?? '');
                        $username = $v['wx_num'];
                        $headimgurl = $v['avatar'];
                        $wxid = $v['wxid'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'], true)){
                            $this->updateOne([
                                'id' => $data['id'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $username,
                                'headimgurl' => $headimgurl,
                                'province' => $v['province'],
                                'city' => $v['city']
                            ]);
                        }else{
                            $this->addOne([
                                'uin' => $bot['uin'],
                                'nickname' => $nickname,
                                'remark_name' => $remark_name,
                                'username' => $username,
                                'wxid' => $wxid,
                                'type' => Bot::FRIEND,
                                'headimgurl' => $headimgurl,
                                'province' => $v['province'],
                                'city' => $v['city']
                            ]);
                        }
                    }
                    //删除无效好友
                    $this->delByMap(['uin' => $bot['uin'],'type' => Bot::FRIEND, 'wxid' => ['notin', $wxid_arr]]);
                    return count($list);
                }
                break;
            default:
                if($res['code'] && count($res['data'])){
                    $list = $res['data'];
                    $wxid_arr = [];
                    foreach ($list as $k => $v){
                        $nickname = filter_emoji($v['nickname']);
                        $remark_name = filter_emoji($v['remark_name']);
                        $username = $v['username'];
                        $wxid = $v['wxid'];
                        $wxid_arr[] = $wxid;
                        if($data = $this->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['id'], true)){
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
                                'type' => Bot::FRIEND
                            ]);
                        }
                    }
                    //删除无效好友
                    $this->delByMap(['uin' => $bot['uin'],'type' => Bot::FRIEND, 'wxid' => ['notin', $wxid_arr]]);
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
        $type = Bot::FRIEND;
        if(strpos($params['wxid'], 'gh_') !== false){
            $type = Bot::MP;
        }
        $insert = [
            'uin' => $bot['uin'],
            'nickname' => $params['nickname'],
            'username' => empty($params['username']) ? $params['wxid'] : $params['username'],
            'wxid' => $params['wxid'],
            'type' => $type,
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