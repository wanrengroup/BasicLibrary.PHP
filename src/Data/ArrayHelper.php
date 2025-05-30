<?php
/**
 * @file   : ArrayHelper.php
 * @time   : 16:01
 * @date   : 2025/1/23
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\Data;

class ArrayHelper
{
    /**
     * 安全获取数组成员的值
     * @param $array
     * @param $key
     * @param $default
     * @return mixed|null
     */
    public static function get($array, $key, $default = null): mixed
    {
        return $array[$key] ?? $default;
    }

    /**
     * 获取数组的维度
     * @param array $arr
     * @return int
     */
    public static function getDimensionCount(array $arr): int
    {
        //如果不是数组，则维度为0
        /** @noinspection all */
        if (!is_array($arr)) {
            return 0;
        }

        $depth = 0;
        foreach ($arr as $value) {
            if (is_array($value)) {
                $depth = max($depth, self::getDimensionCount($value));
            }
        }
        return $depth + 1;
    }

    /**
     * 获取数组的维度(getDimensionCount的别名)
     * @param array $arr
     * @return int
     */
    public static function getLevel(array $arr): int
    {
        return self::getDimensionCount($arr);
    }

    /**
     * 判断是否为关联数组
     * @param mixed $targetObject
     * @return bool
     */
    public static function isAssoc(mixed $targetObject): bool
    {
        $result = self::getArrayType($targetObject);
        return $result === 'ASS_ARRAY';
    }

    /**
     * 判断是否为索引数组
     * @param mixed $targetObject
     * @return bool
     */
    public static function isIndex(mixed $targetObject): bool
    {
        $result = self::getArrayType($targetObject);
        return $result === 'IND_ARRAY';
    }

    /**
     * 判断数组是索引数组还是关联数组
     *(索引数组判定条件：
     * 1->所有键必须是从 0 开始的连续整数，即键的顺序必须严格递增（0,1,2,3...）
     * 2->空数组视为索引数组
     *)
     * @param mixed $targetObject 要检查的对象
     * @return string 返回 'IND_ARRAY'（索引数组）或 'ASS_ARRAY'（关联数组），否则返回 'NOT_ARRAY'
     */
    public static function getArrayType(mixed $targetObject): string
    {
        if (!is_array($targetObject)) {
            return 'NOT_ARRAY';
        }

        // 空数组视为索引数组
        if (empty($targetObject)) {
            return 'IND_ARRAY';
        }

        // 检查所有键是否是从0开始的连续整数
        $keys         = array_keys($targetObject);
        $expectedKeys = range(0, count($targetObject) - 1);

        return $keys === $expectedKeys ? 'IND_ARRAY' : 'ASS_ARRAY';
    }
}