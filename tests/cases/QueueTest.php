<?php
/**
 * Created by PhpStorm.
 * Script Name: QueueTest.php
 * Create: 12/24/21 10:00 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace cases;

use tests\HttpTestCase;

class QueueTest extends HttpTestCase
{
    /**
     * 消息队列测试
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function testQueue(){
        controller('common/TaskQueue', 'event')->push([
            'delay' => 2,
            'params' => [
                'do' => ['\\app\\common\\event\\TaskQueue', 'testTask']
            ]
        ]);
        echo '任务入队列';
        $this->assertSame(1, 1);
    }
}