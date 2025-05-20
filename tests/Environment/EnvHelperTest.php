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


class EnvHelperTest extends TestCase
{
    public function testGetPhysicalRootPath(): void
    {
        $actual = EnvHelper::getPhysicalRootPath();

        // 每次测试时，请修改为实际的物理根目录
        $expect = ConfigHelper::getEnv('real_physical_root_path');
        self::assertEquals($expect, $actual);
    }

    public function testGetVendorLibraryVersion(): void
    {
        $actual = EnvHelper::getVendorLibraryVersion("topthink/think-orm");

        // 每次测试时，请修改为实际的物理根目录
        $expect = ConfigHelper::getEnv('real_think_orm_version', '');
        self::assertEquals($expect, $actual);
    }



}