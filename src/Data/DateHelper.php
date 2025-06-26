<?php
/**
 * @file   : DateHelper.php
 * @time   : 10:10
 * @date   : 2025/1/23
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\Data;

use DateTime;
use Exception;

class DateHelper
{
    /**
     * 时间戳转日期字符串
     * @param int $timestamp
     * @param string $format
     * @return string
     */
    public static function timestamp2string(int $timestamp = 0, string $format = "Y-m-d H:i:s"): string
    {
        if ($timestamp === 0) {
            return "";
        }

        return date($format, $timestamp);
    }

    /**
     * 日期字符串转时间戳
     * @param string $dateString
     * @return int
     */
    public static function string2timestamp(string $dateString = ""): int
    {
        if (empty($dateString)) {
            return 0;
        }

        $date_object = self::getDateTime($dateString);
        return $date_object->getTimestamp();
    }

    /**
     * 在给定的日期的基础上加上指定添加的天数
     * @param mixed $dateValue 日期值，可以是时间戳、日期字符串、DateTime对象
     * @param int $days
     * @return DateTime
     */
    public static function addDays($dateValue, int $days): DateTime
    {
        return self::modifyDatePart($dateValue, "+$days days");
    }

    /**
     * 在给定的日期的基础上加上指定添加的月数
     * @param mixed $dateValue 日期值，可以是时间戳、日期字符串、DateTime对象
     * @param int $months
     * @return DateTime
     */
    public static function addMonths($dateValue, int $months): DateTime
    {
        return self::modifyDatePart($dateValue, "+$months months");
    }

    /**
     * 在给定的日期的基础上加上指定添加的年数
     * @param mixed $dateValue 日期值，可以是时间戳、日期字符串、DateTime对象
     * @param int $years
     * @return DateTime
     */
    public static function addYears($dateValue, int $years): DateTime
    {
        return self::modifyDatePart($dateValue, "+$years years");
    }

    private static function modifyDatePart($dateValue, string $modifier): DateTime
    {
        $date_object       = self::getDateTime($dateValue);
        $date_object_clone = clone $date_object;
        $date_object_clone->modify($modifier);
        return $date_object_clone;
    }

    /**
     * 获取日期对象
     * @param mixed $dateValue 日期值，可以是时间戳、日期字符串、DateTime对象
     * @return DateTime
     */
    public static function getDateTime($dateValue = ""): DateTime
    {
        if (empty($dateValue)) {
            return new DateTime();
        }

        if ($dateValue instanceof DateTime) {
            // 防止修改原对象，克隆一个新的对象。
            return clone $dateValue;
        }

        if (is_float($dateValue) || is_int($dateValue)) {
            $date_object = new DateTime();
            $date_object->setTimestamp($dateValue);
            return $date_object;
        }

        try {
            $date_object = new DateTime($dateValue);
        } catch (Exception $e) {
            $date_object = new DateTime();
        }

        return $date_object;
    }

    /**
     * 格式化日期字符串
     * @param mixed $dateValue 日期值，可以是时间戳、日期字符串、DateTime对象
     * @param string $format 日期格式，默认为 "Y-m-d H:i:s"
     * @return string
     */
    public static function format($dateValue = "", string $format = "Y-m-d H:i:s"): string
    {
        $date_object = self::getDateTime($dateValue);
        return $date_object->format($format);
    }

    /**
     * 系统默认会读取php.ini中的date.timezone配置，如果没有配置，则会使用UTC时间。
     * 该方法可以改写系统默认使用的时区（尤其是在无法修改php.ini的环境下使用）。
     * @param string $timezone
     * @return void
     */
    public static function setTimeZone(string $timezone = 'Asia/Shanghai'): void
    {
        // 1. 校验时区有效性
        if (!in_array($timezone, timezone_identifiers_list(), false)) {
            // 2. 熔断机制：使用UTC兜底
            $timezone = "UTC";
        }

        // 3. 设置时区并添加日志
        date_default_timezone_set($timezone);
    }

    /**
     * 获取系统当前使用的时区字符串
     * @return string
     */
    public static function getTimeZoneString(): string
    {
        return date_default_timezone_get();
    }

    /**
     * 获取时区对象
     * @return DateTimeZone
     */
    public static function getTimeZoneObject(): DateTimeZone
    {
        $zoneName = self::getTimeZoneString();

        try {
            return new DateTimeZone($zoneName);
        } catch (Exception $e) {
            return new DateTimeZone("UTC");
        }
    }
}