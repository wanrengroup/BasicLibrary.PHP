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


/**
 * Where条件构造器
 */
class WhereHelper
{
    public static function Equal(string $fieldName, $value): array
    {
        return array($fieldName, "=", $value);
    }

    public static function NotEqual(string $fieldName, $value): array
    {
        return array($fieldName, "<>", $value);
    }

    public static function GreaterThan(string $fieldName, $value): array
    {
        return array($fieldName, ">", $value);
    }

    public static function GreaterThanOrEqual(string $fieldName, $value): array
    {
        return array($fieldName, ">=", $value);
    }

    public static function LessThan(string $fieldName, $value): array
    {
        return array($fieldName, "<", $value);
    }

    public static function LessThanOrEqual(string $fieldName, $value): array
    {
        return array($fieldName, "<=", $value);
    }

    /**
     *
     * @param string $fieldName
     * @param array|string $values 数组或者用逗号分隔的字符串
     * @return array
     */
    public static function In(string $fieldName, array|string $values): array
    {
        return array($fieldName, "IN", $values);
    }

    /**
     *
     * @param string $fieldName
     * @param array|string $values 数组或者用逗号分隔的字符串
     * @return array
     */
    public static function NotIn(string $fieldName, array|string $values): array
    {
        return array($fieldName, "NOT IN", $values);
    }

    /**
     *
     * @param string $fieldName
     * @param string $value
     * @param $align_position string 字符串$value跟目标字符串的对齐关系。取值为："left"|"right"|"middle"，缺省值为"middle"
     * @return array
     */
    public static function Like(string $fieldName, string $value, string $align_position = "middle"): array
    {
        if ($align_position == "left") {
            $value .= "%";
        } elseif ($align_position == "right") {
            $value = "%" . $value;
        } elseif ($align_position == "middle") {
            $value = "%" . $value . "%";
        }

        return array($fieldName, "LIKE", $value);
    }

    /**
     *
     * @param string $fieldName
     * @param string $value
     * @param $align_position string 字符串$value跟目标字符串的对齐关系。取值为："left"|"right"|"middle"，缺省值为"middle"
     * @return array
     */
    public static function NotLike(string $fieldName, string $value, string $align_position = "middle"): array
    {
        if ($align_position == "left") {
            $value .= "%";
        } elseif ($align_position == "right") {
            $value = "%" . $value;
        } elseif ($align_position == "middle") {
            $value = "%" . $value . "%";
        }

        return array($fieldName, "NOT LIKE", $value);
    }

    /**
     * 查询字符串表示的集合的字段中是否包含指定的子字符串项目。
     * 比如，一个字段存储了字符串表示的水果集合，如："apple,banana,orange,pear"，则可以使用该方法查询是否包含"banana"。
     * @param string $fieldName
     * @param string $item
     * @param string $separator 各个子字符串之间的分隔符，缺省为","
     * @return array where条件的二维数组，这个数组要交给ThinkORM的 whereOr() 方法使用
     */
    public static function LikeAtStringCollection(string $fieldName, string $item, string $separator = ","): array
    {
        $whereOr   = [];
        $whereOr[] = [$fieldName, 'like', $item . $separator . '%'];
        $whereOr[] = [$fieldName, 'like', '%' . $separator . $item . $separator . '%'];
        $whereOr[] = [$fieldName, 'like', '%' . $separator . $item];
        $whereOr[] = [$fieldName, '=', $item];

        return $whereOr;
    }

    /**
     * 查询区间（包含min_value和max_value）
     * @param string $fieldName
     * @param $min_value
     * @param $max_value
     * @return array
     */
    public static function Between(string $fieldName, $min_value, $max_value): array
    {
        return array($fieldName, "BETWEEN", array($min_value, $max_value));
    }

    /**
     * 查询区间外（不包含min_value和max_value）
     * @param string $fieldName
     * @param $min_value
     * @param $max_value
     * @return array
     */
    public static function NotBetween(string $fieldName, $min_value, $max_value): array
    {
        return array($fieldName, "NOT BETWEEN", array($min_value, $max_value));
    }

    /**
     * 查询字段为NULL
     * @param string $fieldName
     * @return array
     */
    public static function Null(string $fieldName): array
    {
        return array($fieldName, "NULL", NULL);
    }

    /**
     * 查询字段不为NULL
     * @param string $fieldName
     * @return array
     */
    public static function NotNull(string $fieldName): array
    {
        return array($fieldName, "NOT NULL", NULL);
    }
}