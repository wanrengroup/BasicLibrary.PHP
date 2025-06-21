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
    public static function addDays(mixed $dateValue, int $days): DateTime
    {
        return self::modifyDatePart($dateValue, "+$days days");
    }

    /**
     * 在给定的日期的基础上加上指定添加的月数
     * @param mixed $dateValue 日期值，可以是时间戳、日期字符串、DateTime对象
     * @param int $months
     * @return DateTime
     */
    public static function addMonths(mixed $dateValue, int $months): DateTime
    {
        return self::modifyDatePart($dateValue, "+$months months");
    }

    /**
     * 在给定的日期的基础上加上指定添加的年数
     * @param mixed $dateValue 日期值，可以是时间戳、日期字符串、DateTime对象
     * @param int $years
     * @return DateTime
     */
    public static function addYears(mixed $dateValue, int $years): DateTime
    {
        return self::modifyDatePart($dateValue, "+$years years");
    }

    private static function modifyDatePart(mixed $dateValue, string $modifier): DateTime
    {
        $date_object = self::getDateTime($dateValue);
        $date_object_clone = clone $date_object;
        $date_object_clone->modify($modifier);
        return $date_object_clone;
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
        } catch (Exception) {
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
    public static function format(mixed $dateValue = "", string $format = "Y-m-d H:i:s"): string
    {
        $date_object = self::getDateTime($dateValue);
        return $date_object->format($format);
    }
}