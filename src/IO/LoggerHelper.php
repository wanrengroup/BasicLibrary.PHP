<?php
/**
 * @file   : LoggerHelper.php
 * @time   : 17:27
 * @date   : 2025/4/24
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\IO;

use JsonException;
use Monolog\Logger;
use WanRen\Environment\ConfigHelper;

class LoggerHelper
{
    private static ?LoggerMate $loggerMate = null;

    /**
     *
     * @param int $logLevel
     * @param mixed ...$messages
     * @return void
     */
    private static function log(int $logLevel, ...$messages): void
    {
        if (self::$loggerMate === null) {
            // 这里可以根据需要自定义日志配置
            $logDirName      = ConfigHelper::getEnv('LOG_DIR_NAME', '');
            $logFileBaseName = ConfigHelper::getEnv('LOG_FILE_BASE_NAME', '');
            $logChannelName  = ConfigHelper::getEnv('LOG_CHANNEL_NAME', '');

            self::$loggerMate = new LoggerMate($logChannelName, $logFileBaseName, $logDirName);
        }

        foreach ($messages as $message) {
            if (!is_string($message)) {
                try {
                    $message = json_encode($message, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
                } catch (JsonException $e) {
                    $message = 'json_encode error: ' . $e->getMessage();
                }
            }

            self::$loggerMate->log($logLevel, $message);
        }
    }

    /**
     * 记录调试数据
     * @param mixed ...$messages
     * @return void
     */
    public static function debug(...$messages): void
    {
        self::log(Logger::DEBUG, $messages);
    }


    /**
     * 记录信息
     * @param mixed ...$messages
     * @return void
     */
    public static function info(...$messages): void
    {
        self::log(Logger::INFO, $messages);
    }

    /**
     * 记录信息
     * 为了保持兼容性，这里保留了save方法名。实际为 info 方法的别名。
     * @param mixed ...$messages
     * @return void
     */
    public static function save(...$messages): void
    {
        self::log(Logger::INFO, $messages);
    }

    /**
     * 记录错误
     * @param mixed ...$messages
     * @return void
     */
    public static function error(...$messages): void
    {
        self::log(Logger::ERROR, $messages);
    }


}