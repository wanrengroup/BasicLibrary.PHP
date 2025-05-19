<?php
/**
 * @file   : DbAssert.php
 * @time   : 09:15
 * @date   : 2025/5/2
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Wanren\Test\Basic;

use think\facade\Db;


class DbAssert
{
    public static function initDb(): void
    {
        $setting = [
            // 默认使用的数据库连接配置
            'default' => 'mysql',

            // 数据库连接配置信息
            'connections' => [
                'mysql' => [
                    // 数据库类型
                    'type' => 'mysql',
                    // 服务器地址
                    'hostname' => '127.0.0.1',
                    // 数据库名
                    'database' => 'my_wr_basic_library',
                    // 用户名
                    'username' => 'root',
                    // 密码
                    'password' => '123456',
                    // 端口
                    'hostport' => '3306',
                    // 数据库连接参数
                    'params' => [],
                    // 数据库编码默认采用utf8
                    'charset' => 'utf8',
                    // 数据库表前缀
                    'prefix' => 'm_',
                ],

                // 更多的数据库配置信息
            ],
        ];

        /** @noinspection all */
        Db::setConfig($setting);
    }

}