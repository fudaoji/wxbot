<?php
/**
 * Created by PhpStorm.
 * Script Name: AuthTest.php
 * Create: 9:51 下午
 * Description:
 * Author: Jason<dcq@kuryun.cn>
 */

namespace cases;


use tests\HttpTestCase;

class AuthTest extends HttpTestCase
{
    public function testToken() {
        $res = $this->request(['openid' => 'oWlb45WmmhU_X_PBcxVuzMhdvRi0'], '/api/auth/tokenPost', false);
        controller('common/base', 'event')->getRedis()->setex('dcq_test', 86400 * 7, json_encode(['token' => $res['data']['token']]));
        dump($res);
        $this->assertSame(1, $res['code']);
    }

    public function testWxAuth() {
        $params = [
            'username' => '燕南天2',
            'nickname' => '江小鱼2',
            'headimgurl' => 'https://zyx.images.huihuiba.net/FjIw_L1Pmha-5gZqGVdZUhRNr4yg',
            'sex' => 1,
            'country' => '中国',
            'province' => '福建省',
            'city' => '厦门市'
        ];
        $res = $this->request($params, '/api/auth/wxAuthPost');
        dump($res);
        $this->assertSame(1, $res['code']);
    }
}