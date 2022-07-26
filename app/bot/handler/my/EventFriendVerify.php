<?php
/**
 * Created by PhpStorm.
 * Script Name: EventFrieneVerify.php
 * Create: 3/19/22 9:19 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\My;

use app\bot\handler\Handler;
use ky\Logger;

class EventFriendVerify extends Handler
{
    /**
     * 设备回调消息接收器
     * // HTTP(GET/POST)示例
    {
        "sdkVer": 5,  // SDK版本号
        "Event": "EventFrieneVerify", // 事件（易语言模板的子程序名）
        "content": {
            "robot_wxid": "",  // 机器人账号id
            "type": 1,  // 添加方式 请参考常量表
            "from_wxid": "",  // 请求者wxid
            "from_name": "",  // 请求者昵称
            "v1": "",
            "v2": "",
            "json_msg": {
                "to_wxid": "wxid_eu05e13ld28822",
                "to_name": "譬如朝露",
                "msgid": 1111250493,
                "from_wxid": "wxid_6ungmd6wtdh521",
                "from_nickname": "??[奸笑]??",
                "v1": "xxxxx",
                "v2": "xxxxx",
                "sex": 1,
                "from_content": "我是??[奸笑]??",
                "headimgurl": "http://wx.qlogo.cn/xxxxx",
                "type": 3
            },  // 友验证信息JSON(群内添加时，包含群id) (名片推荐添加时，包含推荐人id及昵称) (微信号、手机号搜索等添加时,具体JSON结构请查看日志）
            "robot_type": 0  // 来源微信类型 0 正常微信 / 1 企业微信
        }  // 内容（易语言模板的参数名）
    }
     */
    public function handle(){
        Logger::error($this->content);
    }

    /*============好友请求几种情况=================================*/
    //微信添加企业微信用户: type=11063
    /**
     * array (
        'robot_wxid' => '1688854317341474',
        'type' => 11063,
        'from_wxid' => '7881300915130002',
        'from_name' => '严选官-葡萄',
        'v1' => '',
        'v2' => '',
        'json_msg' =>
            array (
                'data' =>
                array (
                'avatar' => 'http://wx.qlogo.cn/mmhead/Mjzdia7evAzz1iaiaOIibe0cnov0AibxL5GtFicuSC0VbM69gzcMq98Y4D3A/0',
                'corp_id' => '1970325134026788',
                'nickname' => '严选官-葡萄',
                'sex' => 0,
                'user_id' => '7881300915130002',
                'verify' => '我是严选官-葡萄',
            ),
            'type' => 11063,
        ),
        'robot_type' => 1,
    )
     */
}