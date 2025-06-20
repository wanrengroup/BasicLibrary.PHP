<?php
/**
 * @file   : AbstractLogicTestingTest.php
 * @time   : 11:04
 * @date   : 2025/6/13
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\Test\Basic;

use WanRen\WorkLayer\GeneralLogic;

class AnotherConnectionTest extends LocalTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $options = [
            'connection' => C('DB_CONFIG3'),
            'prefix' => C('DB_CONFIG3.DB_PREFIX'),
        ];

        $this->logic = new GeneralLogic("student", $options);
    }

    public function testGetCount(): void
    {
        $expect = 5;

        $list   = $this->logic->getModel()->select();
        $actual = count($list);
        self::assertEquals($expect, $actual);
    }
}