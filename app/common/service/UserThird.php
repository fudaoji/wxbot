<?php
/**
 * Created by PhpStorm.
 * Script Name: UserThird.php
 * Create: 2025/4/18 下午6:25
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;


use app\common\model\UserThird as ThirdM;

class UserThird
{

    const WX = 'wx';
    const QQ = 'qq';
    const USER_ID_KEY = 'admin_id';

    static $model = null;

    static function model(){
        if(is_null(self::$model)){
            self::$model = new ThirdM();
        }
        return self::$model;
    }

    /**
     * 获取数据
     * @param int $id
     * @return bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getById($id = 0){
        return self::model()->getOne($id);
    }

    /**
     * 获取微信数据
     * @param int $user_id
     * @return bool|mixed
     * @throws \think\exception\DbException
     */
    static function getWxByUserId($user_id = 0){
        return self::model()->getOneByMap([self::USER_ID_KEY => $user_id, 'type' => self::WX]);
    }

    /**
     * 新增/更新数据
     * @param $data
     * @param string $type
     * @return bool|mixed
     * @throws \think\Exception
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function insertOrUpdate($data, $type= ''){
        $insert = [
            'type' => $type,
            'nickname' => $data['nickname'],
            'openid' => $data['social_uid'],
            'access_token' => $data['access_token'],
            'headimgurl' => $data['faceimg'],
            'location' => $data['location'],
            'gender' => self::transferGender($data['gender']),
            'ip' => $data['ip']
        ];

        if($res = self::model()->getOneByMap(['type' => $type, 'openid' => $data['social_uid']])){
            $insert['id'] = $res['id'];
            $res = self::model()->updateOne($insert);
        }else{
            $res = self::model()->addOne($insert);
        }

        return $res;
    }

    static function types($id = null){
        $list = [
            self::WX => '微信',
            self::QQ => 'QQ'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    /**
     * 绑定
     * @param $id
     * @param $user_id
     * @return bool|mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function bindUserId($id, $user_id){
        return self::model()->updateOne(['id' => $id, self::USER_ID_KEY => $user_id]);
    }

    static function transferGender($gender = "男"){
        return $gender == "男" ? 0 : 1;
    }
}