<?php
/**
 * @file   : AbstractLogicTestingTest.php
 * @time   : 11:04
 * @date   : 2025/6/13
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Biz;

use WanRen\Test\Biz\AbstractLogicTestingModel;
use PHPUnit\Framework\TestCase;

class AbstractLogicTestingTest extends TestCase
{
    public function testGetSome(): void
    {
        $expect = 3;

        $model = new AbstractLogicTestingModel();
        $list  = $model->select();
        $actual = count($list);
        self::assertEquals($expect, $actual);
    }
}