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

    public function __construct(string $channel_name = "", string $log_file_basename = "",string $log_file_dirname = "")
    {
        if (empty($log_file_basename)) {
            $log_file_basename = date('Y-m-d');
        }

        if (empty($log_file_dirname)) {
            $log_file_dirname = "logs";
        }

        // 创建日志目录
        if (!is_dir($log_file_dirname) && !mkdir($log_file_dirname, 0777, true) && !is_dir($log_file_dirname)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $log_file_dirname));
        }

        $log_file_full_path = $log_file_dirname. "/local-". $log_file_basename. ".log";

        // 创建日志记录器
        $this->logger = new Logger($channel_name);

        // 添加日志处理器
        $this->logger->pushHandler(new StreamHandler($log_file_full_path, Logger::DEBUG));
    }


    public function log($level, Stringable|string $message, array $context = []): void
    {
        // 具体记录日志
        $this->logger->addRecord($level, $message, $context);
    }
}