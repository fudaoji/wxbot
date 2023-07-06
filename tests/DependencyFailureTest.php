<?php
/**
 * Created by PhpStorm.
 * Script Name: DependencyFailureTest.php
 * Create: 2022/7/27 17:12
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class DependencyFailureTest extends TestCase
{
    public function testOne(): void
    {
        $this->assertTrue(false);
    }

    /**
     * @depends testOne
     */
    public function testTwo(): void
    {
    }
}