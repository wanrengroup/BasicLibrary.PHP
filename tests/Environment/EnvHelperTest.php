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

use PHPUnit\Framework\TestCase;
use WanRen\Environment\ConfigHelper;
use WanRen\Environment\EnvHelper;


class EnvHelperTest extends TestCase
{
    public function testGetPhysicalRootPath(): void
    {
        $actual = EnvHelper::getPhysicalRootPath();

        // 每次测试时，请到根目录下的.env文件中修改real_physical_root_path的值为实际的物理根目录
        $expect = ConfigHelper::getEnv('real_physical_root_path');
        self::assertEquals($expect, $actual);
    }

    public function testGetVendorLibraryVersion(): void
    {
        $actual = EnvHelper::getVendorLibraryVersion("topthink/think-orm");

        // 每次测试时，请到根目录下的.env文件中修改real_think_orm_version的值为实际的ORM版本号
        $expect = ConfigHelper::getEnv('real_think_orm_version', '');
        self::assertEquals($expect, $actual);
    }



}