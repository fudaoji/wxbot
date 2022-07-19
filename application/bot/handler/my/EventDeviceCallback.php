<?php
/**
 * Created by PhpStorm.
 * Script Name: EventDeviceCallback.php
 * Create: 3/19/22 11:33 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\My;

use app\bot\handler\Handler;

class EventDeviceCallback extends Handler
{
    /**
     * 设备回调消息接收器: 机器人自己发群消息
     * {
    "sdkVer": 5,  // SDK版本号
    "Event": "EventDeviceCallback", // 事件（易语言模板的子程序名）
    "content": {
    "robot_wxid": "",  // 机器人账号id
    "type": 1,  // 消息类型
    "msg": "",  // 消息内容
    "to_wxid": "",  // 接收用户ID
    "to_name": "",  // 接收用户昵称
    "clientid": 0,  // 企业微信可用
    "robot_type": 0,  // 来源微信类型 0 正常微信 / 1 企业微信
    "msg_id": 0  // 消息ID
    }
    }
     */
    public function handle(){
        $this->addon();
    }

    /**
     * 插件处理
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function addon(){
        controller('bot/tpzs')->init($this->getAddonOptions())->deviceCallbackHandle(); //推品助手，后期这部分应是动态获取
    }

}