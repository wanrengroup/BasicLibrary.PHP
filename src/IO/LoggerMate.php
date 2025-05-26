<?php
/**
 * @file   : LoggerMate.php
 * @time   : 17:31
 * @date   : 2025/4/24
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\IO;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\AbstractLogger;
use Stringable;


class LoggerMate extends AbstractLogger
{
    private Logger $logger;

    public function __construct(string $channel_name = "", string $log_file_basename = "")
    {
        if (empty($log_file_basename)) {
            $log_file_basename = date('Y-m-d');
        }

        // 创建日志记录器
        $this->logger = new Logger($channel_name);

        // 添加日志处理器
        $this->logger->pushHandler(new StreamHandler("logs/local-$log_file_basename.log", Logger::DEBUG));
    }


    public function log($level, Stringable|string $message, array $context = []): void
    {
        // 具体记录日志
        $this->logger->addRecord($level, $message, $context);
    }
}