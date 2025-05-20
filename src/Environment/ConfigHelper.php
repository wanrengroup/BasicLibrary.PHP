<?php
/**
 * @file   : ConfigHelper.php
 * @time   : 15:13
 * @date   : 2025/5/20
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\Environment;

use Dotenv\Dotenv;

class ConfigHelper
{
    private static bool $envLoaded = false;

    /**
     * .env文件配置项的读取
     * @param string $key
     * @param null $default
     * @return string|null 各种类型的数据都返回字符串，需要自己再根据实际情况转换类型
     */
    public static function getEnv(string $key, $default = null): ?string
    {
        if (self::$envLoaded === false) {
            // 获取项目根目录
            $root = EnvHelper::getPhysicalRootPath();

            // 实例化 Dotenv 对象
            $dotenv = Dotenv::createImmutable($root);
            $dotenv->load(); // 加载 .env 文件中的变量到环境变量中

            self::$envLoaded = true;
        }

        return $_ENV[$key] ?? $default;
    }
}