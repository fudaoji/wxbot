<?php

/**
 * Created by PhpStorm.
 * Script Name: Tpzs.php
 * Create: 2022/3/28 11:38
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\kefu;

use app\admin\controller\Kefu as ControllerKefu;
use app\common\model\Base;
use app\admin\model\BotMember;
use ky\Logger;
class Kefu extends Base
{
    protected $isCache = false;

    public function __construct($data = [])
    {
        $this->table = $this->getTablePrefix() . 'kefu_' . $this->table;
        parent::__construct($data);
    }

    /**
     * 
     * 自动通过验证
     */
    public function autoPass($content, $bot, $botClient,$config)
    {
        // "content": {
        //     "robot_wxid": "",  // 机器人账号id
        //     "type": 1,  // 添加方式 请参考常量表
        //     "from_wxid": "",  // 请求者wxid
        //     "from_name": "",  // 请求者昵称
        //     "v1": "",
        //     "v2": "",
        //     "json_msg": {
        //         "to_wxid": "wxid_eu05e13ld28822",
        //         "to_name": "譬如朝露",
        //         "msgid": 1111250493,
        //         "from_wxid": "wxid_6ungmd6wtdh521",
        //         "from_nickname": "??[奸笑]??",
        //         "v1": "xxxxx",
        //         "v2": "xxxxx",
        //         "sex": 1,
        //         "from_content": "我是??[奸笑]??",
        //         "headimgurl": "http://wx.qlogo.cn/xxxxx",
        //         "type": 3
        //     },  // 友验证信息JSON(群内添加时，包含群id) (名片推荐添加时，包含推荐人id及昵称) (微信号、手机号搜索等添加时,具体JSON结构请查看日志）
        Logger::write("好友自动通过Config:---".json_encode($config)."\n");
        if ($config['auto_pass']) {
            Logger::write("好友自动通过---"."\n");
            $v1 = $content['json_msg']['v1'];
            $v2 = $content['json_msg']['v2'];
            $type = $content['json_msg']['type'];
            $res = $botClient->agreeFriendVerify([
                'robot_wxid' => $content['robot_wxid'],
                'v1' => $v1,
                'v2' => $v2,
                'type' => $type
            ]);
            Logger::write("好友自动通过接口返回:".json_encode($res)."\n");
            //插入用户表
            $bot_menber_model = new BotMember();
            if($data = $bot_menber_model->getOneByMap(['uin' => $bot['uin'], 'wxid' => $content['json_msg']['from_wxid']], ['id'])){
                $id = $data['id'];
                $bot_menber_model->updateOne([
                    'id' => $data['id'],
                    'nickname' => $content['json_msg']['from_nickname'],
                    'remark_name' => $content['json_msg']['from_content'],
                    'wxid' => $content['json_msg']['from_wxid'],
                    'headimgurl' => $content['json_msg']['headimgurl'],
                ]);

            }else{
                $id = $bot_menber_model->addOne([
                    'uin' => $bot['uin'],
                    'nickname' => $content['json_msg']['from_nickname'],
                    'remark_name' => $content['json_msg']['from_content'],
                    'type' => 'friend',
                    'wxid' => $content['json_msg']['from_wxid'],
                    'headimgurl' => $content['json_msg']['headimgurl'],
                ]);
            }
            //发送自动回复
            $auto_reply = trim($config['auto_reply']);
            if($auto_reply) {
                Logger::write("发送自动回复"."\n");
                $ControllerKefu = new ControllerKefu();
                $param = ['bot_id' => $bot['id'],'type' => 1, 'to_wxid' => $content['from_wxid'], 'content' => $auto_reply, 'friend_id' => $id];
                $ControllerKefu->sendMsg($param);
                $ControllerKefu->sendMsgPost($param);
                //发一条好友请求事件到前端，刷新好友列表
                $this->sendToClinet([
                    'event' => 'new_friend',
                    'from_wxid' => $content['from_wxid'], 
                    'robot_wxid' => $content['robot_wxid'],
                    'admin_id' => $bot['admin_id']
                ]);
            }
        }

    }
    /**
     * 发送信息到前端
     */
    public function sendToClinet($param){
        $key = 'receive_private_chat';
        $redis = get_redis();
        $msg = json_encode([
            'event' => $param['event'],
            'from_wxid' => $param['from_wxid'],
            'robot_wxid' => $param['robot_wxid'],
            'client' => $param['admin_id'],//对应用户id
        ]);
        $redis->rpush($key,$msg);
        return true;
    }
}
