<?php
/**
 * @file   : LoggerHelper.php
 * @time   : 17:27
 * @date   : 2025/4/24
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Wanren\IO;

use JsonException;
use Monolog\Logger;

class LoggerHelper
{
    private static ?LoggerMate $loggerMate = null;

    /**
     *
     * @param int $logLevel
     * @param mixed ...$messages
     * @return void
     */
    private static function store(int $logLevel, mixed ...$messages): void
    {
        if (self::$loggerMate === null) {
            self::$loggerMate = new LoggerMate();
        }

        foreach ($messages as $message) {
            if (!is_string($message)) {
                try {
                    $message = json_encode($message, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
                } catch (JsonException $e) {
                    $message = 'json_encode error: ' . $e->getMessage();
                }
            }

            self::$loggerMate->save($message, $logLevel);
        }
    }

    /**
     * 记录调试数据
     * @param mixed ...$messages
     * @return void
     */
    public static function debug(mixed ...$messages): void
    {
        self::store(Logger::DEBUG, $messages);
    }


    /**
     * 记录信息
     * @param mixed ...$messages
     * @return void
     */
    public static function info(mixed ...$messages): void
    {
        self::store(Logger::INFO, $messages);
    }

    /**
     * 记录信息
     * 为了保持兼容性，这里保留了save方法名。实际为 info 方法的别名。
     * @param mixed ...$messages
     * @return void
     */
    public static function save(mixed ...$messages): void
    {
        self::info($messages);
    }

    /**
     * 记录错误
     * @param mixed ...$messages
     * @return void
     */
    public static function error(mixed ...$messages): void
    {
        self::store(Logger::ERROR, $messages);
    }


}