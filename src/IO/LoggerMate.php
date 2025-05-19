<?php
/**
 * @file   : LoggerMate.php
 * @time   : 17:31
 * @date   : 2025/4/24
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Wanren\IO;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;


class LoggerMate
{
    private Logger $log;

    public function __construct(string $log_file_basename = "")
    {
        if (empty($log_file_basename)) {
            $log_file_basename = date('Y-m-d');
        }

        // 创建日志记录器
        $this->log = new Logger('');

        // 添加日志处理器
        $this->log->pushHandler(new StreamHandler("logs/local-$log_file_basename.log", Logger::INFO));
    }

    public function save(string $message, int $level = Logger::INFO): void
    {
        // 具体记录日志
        $this->log->addRecord($level, $message);
    }


}