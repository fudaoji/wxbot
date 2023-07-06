<?php
/**
 * Created by PhpStorm.
 * Script Name: BotTest.php
 * Create: 2022/7/27 16:00
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace tests\cases\openai;


use ky\OpenAI\Driver\AIEdu;
use tests\UnitTestCase;

class Base extends UnitTestCase
{
    protected $userId = 'wxid_xokb2ezu1p6t21';
    protected $appId = '1WSX842fqB1JHSL';
    /**
     * @var AIEdu
     */
    protected $aiClient;
}