<?php
/**
 * @file   : DateHelperTest.php
 * @time   : 10:54
 * @date   : 2025/4/28
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\Test\Manual;

require "../../vendor/autoload.php";

use WanRen\Data\StringHelper;
use WanRen\IO\LoggerHelper;


class LoggerHelperTest
{
    public function testSave(): void
    {
        $info = ["this is a test log", "this is a test log2"];
        LoggerHelper::save($info, "aa");
    }


}

$test = new LoggerHelperTest();
$test->testSave();