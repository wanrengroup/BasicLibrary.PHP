<?php
/**
 * @file   : DateHelper.php
 * @time   : 10:10
 * @date   : 2025/1/23
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Wanren\Data;

use DateTime;
use Exception;

class DateHelper
{
    public static function timestamp2string($timestamp = 0, string $format = "Y-m-d H:i:s"): string
    {
        if ($timestamp === 0) {
            return "";
        }

        return date($format, $timestamp);
    }

    public static function string2timestamp($dateString = ""): int
    {
        if (empty($dateString)) {
            return 0;
        }

        $date_object = self::getDateTime($dateString);
        return $date_object->getTimestamp();
    }

    public static function addDays($dateValue, int $days): DateTime
    {
        $date_object = self::getDateTime($dateValue);
        $date_object->modify("+$days days");
        return $date_object;
    }

    public static function addMonths($dateValue, int $months): DateTime
    {
        $date_object = self::getDateTime($dateValue);
        $date_object->modify("+$months months");
        return $date_object;
    }

    public static function addYears($dateValue, int $years): DateTime
    {
        $date_object = self::getDateTime($dateValue);
        $date_object->modify("+$years years");
        return $date_object;
    }

    /**
     * 获取日期对象
     * @param mixed $dateValue 日期值，可以是时间戳、日期字符串、DateTime对象
     * @return DateTime
     */
    public static function getDateTime(mixed $dateValue = ""): DateTime
    {
        if (empty($dateValue)) {
            return new DateTime();
        }

        if ($dateValue instanceof DateTime) {
            return $dateValue;
        }

        if (is_float($dateValue) || is_int($dateValue)) {
            $date_object = new DateTime();
            $date_object->setTimestamp($dateValue);
            return $date_object;
        }

        try {
            $date_object = new DateTime($dateValue);
        } catch (Exception) {
            $date_object = new DateTime();
        }

        return $date_object;
    }

    /**
     * 格式化日期字符串
     * @param mixed $dateValue
     * @param string $format
     * @return string
     */
    public static function format(mixed $dateValue = "", string $format = "Y-m-d H:i:s"): string
    {
        $date_object = self::getDateTime($dateValue);
        return $date_object->format($format);
    }
}