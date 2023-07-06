<?php
/**
 * Created by PhpStorm.
 * Script Name: CarPlateTest.php
 * Create: 2022/7/22 11:57
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace tests\cases;


use ky\Emoji;
use tests\HttpTestCase;

class EmojiTest extends HttpTestCase
{
    private $unified = 'U+2600';
    private $softBank = 'U+1F197';
    private $hex = 0x2600;

    public function testUnifiedToHtml(){
        $bytes = Emoji::getInstance()->utf8Bytes(Emoji::getInstance()->unifiedToHex($this->unified));
        $res = Emoji::getInstance()->emojiUnifiedToHtml($bytes);
        dump($res);
        $this->assertSame(1,1);
    }

    public function testGetName(){
        $res = Emoji::getInstance()->emojiGetName($this->softBank);
        dump($res);
        $this->assertSame(1,1);
    }

    public function testUnifiedToSoftBank(){
        $bytes = Emoji::getInstance()->utf8Bytes(Emoji::getInstance()->unifiedToHex($this->unified));
        $res = Emoji::getInstance()->emojiUnifiedToSoftbank($bytes);
        dump($res);
        $this->assertSame(1,1);
    }

    public function testUnifiedToDocomo(){
        $bytes = Emoji::getInstance()->utf8Bytes(Emoji::getInstance()->unifiedToHex($this->unified));
        $res = Emoji::getInstance()->emojiUnifiedToDocomo($bytes);
        dump($res);
        $this->assertSame(1,1);
    }

    public function testHexToUnified(){
        $res = Emoji::getInstance()->hexToUnified($this->hex);
        dump($res);
        $this->assertSame(1,1);
    }
}