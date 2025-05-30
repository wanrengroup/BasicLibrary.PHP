<?php
/**
 * @file   : DateHelperTest.php
 * @time   : 10:54
 * @date   : 2025/4/28
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\Test\Environment;

use DateTime;
use PHPUnit\Framework\TestCase;
use WanRen\Environment\ConfigHelper;
use WanRen\Environment\EnvHelper;


class ConfigHelperTest extends TestCase
{
    public function testGetEnv(): void
    {
        $actual = ConfigHelper::getEnv("TESTING_STRING");
        $expect = "china";
        self::assertEquals($expect, $actual);

        $actual = ConfigHelper::getEnv("TESTING_NUMBER");
        $expect = 123;
        self::assertEquals($expect, $actual);

        $actual = ConfigHelper::getEnv("TESTING_ARRAY");
        $expect = "[1,2,3,4,5]";
        self::assertEquals($expect, $actual);

        $actual = ConfigHelper::getEnv("testing_nonexistent_key");
        $expect = null;
        self::assertEquals($expect, $actual);

        $actual = ConfigHelper::getEnv("testing_nonexistent_key", "default_value");
        $expect = "default_value";
        self::assertEquals($expect, $actual);
    }

    public function testGetEnv2(): void
    {
        $actual = ConfigHelper::getEnv("WHERE_CONDITION_OR_PREFIX");
        $expect = "__or__";
        self::assertEquals($expect, $actual);
    }


}