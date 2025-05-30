<?php
/**
 * @file   : DateHelperTest.php
 * @time   : 10:54
 * @date   : 2025/4/28
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\Test\Data;

use PHPUnit\Framework\TestCase;
use WanRen\Data\StringHelper;


class StringHelperTest extends TestCase
{
    public function testIsStartWith(): void
    {
        $original = "i like to eat apple!";
        $actual = StringHelper::isStartWith($original,"apple","boy");
        self::assertFalse($actual);

        $actual = StringHelper::isStartWith($original,"apple","i");
        self::assertTrue($actual);

        $actual = StringHelper::isStartWith($original,"apple","i ");
        self::assertTrue($actual);
    }

    public function testIsEndWith(): void
    {
        $original = "i like to eat apple!";
        $actual = StringHelper::isEndWith($original,"apple","boy");
        self::assertFalse($actual);

        $actual = StringHelper::isEndWith($original,"apple","!");
        self::assertTrue($actual);

        $actual = StringHelper::isEndWith($original,"apple!","i ");
        self::assertTrue($actual);
    }

    public function testIsContains(): void
    {
        $original = "i like to eat apple!";
        $actual = StringHelper::isContains($original,"apple1","boy");
        self::assertFalse($actual);

        $actual = StringHelper::isContains($original,"apple","!");
        self::assertTrue($actual);

        $actual = StringHelper::isContains($original,"apple!","i ");
        self::assertTrue($actual);
    }



}