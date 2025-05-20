<?php
/**
 * @file   : DateHelperTest.php
 * @time   : 10:54
 * @date   : 2025/4/28
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Environment;

use DateTime;
use PHPUnit\Framework\TestCase;
use WanRen\Environment\ConfigHelper;
use WanRen\Environment\EnvHelper;


class ConfigHelperTest extends TestCase
{
    public function testGetEnv(): void
    {
        $actual = ConfigHelper::getEnv("testing_string");
        $expect = "china";
        self::assertEquals($expect, $actual);

        $actual = ConfigHelper::getEnv("testing_number");
        $expect = 123;
        self::assertEquals($expect, $actual);

        $actual = ConfigHelper::getEnv("testing_array");
        $expect = "[1,2,3,4,5]";
        self::assertEquals($expect, $actual);

        $actual = ConfigHelper::getEnv("testing_nonexistent_key");
        $expect = null;
        self::assertEquals($expect, $actual);

        $actual = ConfigHelper::getEnv("testing_nonexistent_key","default_value");
        $expect = "default_value";
        self::assertEquals($expect, $actual);
    }


}