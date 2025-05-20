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
     * @param $arr
     * @return int
     */
    public static function getDimensionCount($arr): int
    {
        $depth = 0;
        foreach ($arr as $value) {
            if (is_array($value)) {
                $depth = max($depth, self::getDimensionCount($value));
            }
        }
        return $depth + 1;
    }
}