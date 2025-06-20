<?php
/**
 * @file   : LocalTestCase.php
 * @time   : 07:57
 * @date   : 2025/6/15
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\Test\Basic;

use PHPUnit\Framework\TestCase;
use WanRen\Think\TPMocker;

class LocalTestCase extends TestCase
{
    static protected TPMocker $app;

    /**
     * 将TP系统引导起来
     * @return void
     */
    public function setUp(): void
    {
        // 下面四行代码模拟出一个应用实例, 每一行都很关键, 需正确设置参数
        self::$app = new TPMocker();
        self::$app->setMVC('domain.com', 'Home', 'Index');
        //self::$app->setTestConfig(['DB_NAME' => 'my_tp8_study', 'DB_HOST' => '127.0.0.1', 'DB_USER' => 'root', 'DB_PWD' => '123456', 'DB_PORT' => 3306, 'DB_PREFIX' => 'm_']); // 一定要设置一个测试用的数据库,避免测试过程破坏生产数据
        self::$app->start();
    }
}