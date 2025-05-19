<?php
/**
 * @file   : WhereHelper.php
 * @time   : 13:50
 * @date   : 2025/4/17
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\Data;

//TODO:xiedali@2025/04/24 Or条件待实现
use function PHPUnit\Framework\isNull;

/**
 * Where条件构造器
 */
class WhereHelper
{
    public static function Equal(string $field, $value): array
    {
        return array($field, "=", $value);
    }

    public static function NotEqual(string $field, $value): array
    {
        return array($field, "<>", $value);
    }

    public static function GreaterThan(string $field, $value): array
    {
        return array($field, ">", $value);
    }

    public static function GreaterThanOrEqual(string $field, $value): array
    {
        return array($field, ">=", $value);
    }

    public static function LessThan(string $field, $value): array
    {
        return array($field, "<", $value);
    }

    public static function LessThanOrEqual(string $field, $value): array
    {
        return array($field, "<=", $value);
    }

    /**
     *
     * @param string $field
     * @param array|string $values 数组或者用逗号分隔的字符串
     * @return array
     */
    public static function In(string $field, array|string $values): array
    {
        return array($field, "IN", $values);
    }

    /**
     *
     * @param string $field
     * @param array|string $values 数组或者用逗号分隔的字符串
     * @return array
     */
    public static function NotIn(string $field, array|string $values): array
    {
        return array($field, "NOT IN", $values);
    }

    /**
     *
     * @param string $field
     * @param string $value
     * @param $align_position string 字符串$value跟目标字符串的对齐关系。取值为："left"|"right"|"middle"，缺省值为"middle"
     * @return array
     */
    public static function Like(string $field, string $value, string $align_position = "middle"): array
    {
        if ($align_position == "left") {
            $value .= "%";
        } elseif ($align_position == "right") {
            $value = "%" . $value;
        } elseif ($align_position == "middle") {
            $value = "%" . $value . "%";
        }

        return array($field, "LIKE", $value);
    }

    /**
     *
     * @param string $field
     * @param string $value
     * @param $align_position string 字符串$value跟目标字符串的对齐关系。取值为："left"|"right"|"middle"，缺省值为"middle"
     * @return array
     */
    public static function NotLike(string $field, string $value, string $align_position = "middle"): array
    {
        if ($align_position == "left") {
            $value .= "%";
        } elseif ($align_position == "right") {
            $value = "%" . $value;
        } elseif ($align_position == "middle") {
            $value = "%" . $value . "%";
        }

        return array($field, "NOT LIKE", $value);
    }

    /**
     * 查询区间（包含min_value和max_value）
     * @param string $field
     * @param $min_value
     * @param $max_value
     * @return array
     */
    public static function Between(string $field, $min_value, $max_value): array
    {
        return array($field, "BETWEEN", array($min_value, $max_value));
    }

    /**
     * 查询区间外（不包含min_value和max_value）
     * @param string $field
     * @param $min_value
     * @param $max_value
     * @return array
     */
    public static function NotBetween(string $field, $min_value, $max_value): array
    {
        return array($field, "NOT BETWEEN", array($min_value, $max_value));
    }

    /**
     * 查询字段为NULL
     * @param string $field
     * @return array
     */
    public static function Null(string $field): array
    {
        return array($field, "NULL", NULL);
    }

    /**
     * 查询字段不为NULL
     * @param string $field
     * @return array
     */
    public static function NotNull(string $field): array
    {
        return array($field, "NOT NULL", NULL);
    }
}