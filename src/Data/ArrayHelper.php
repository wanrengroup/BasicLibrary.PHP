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
     * @param array $array 数组
     * @param int|string $key 键名
     * @param mixed $default 默认值
     * @return mixed|null 返回数组成员的值，如果不存在则返回默认值
     */
    public static function get(array $array, $key, $default = null)
    {
        return $array[$key] ?? $default;
    }

    /**
     * 安全获取数组成员的值(get的别名)
     * * @param array $array 数组
     * * @param int|string $key 键名
     * * @param mixed $default 默认值
     * * @return mixed|null 返回数组成员的值，如果不存在则返回默认值
     */
    public static function getItem(array $array, $key, $default = null)
    {
        return self::get($array, $key, $default);
    }

    /**
     * 获取数组的维度
     * @param mixed $targetObject
     * @return int
     */
    public static function getDimensionCount($targetObject): int
    {
        //如果不是数组，则维度为0
        if (!is_array($targetObject)) {
            return 0;
        }

        $depth = 0;
        foreach ($targetObject as $value) {
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
    public static function isAssoc($targetObject): bool
    {
        $result = self::getArrayType($targetObject);
        return $result === 'ASS_ARRAY';
    }

    /**
     * 判断是否为索引数组
     * @param mixed $targetObject
     * @return bool
     */
    public static function isIndex($targetObject): bool
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
    public static function getArrayType($targetObject): string
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

    /**
     * 在数组中根据指定的列名生成键值对数组（通常用于生成下拉列表的列表项使用）
     * @param array $sourceArray 原始数组(原始数组的每个元素是都是一个关联数组)
     * @param string $columnNameAsName 作为名称的列名，其值作为显示给用户的名称
     * @param string $columnNameAsValue 作为值的列名，其值作为实际的值
     * @param bool $insertBlankItem 是否插入空白项
     * @param string $blankName 空白项的显示的名称
     * @param string $blankValue 空白项的实际的值
     * @return array
     */
    public static function generateNameValueItems(array $sourceArray, string $columnNameAsName='name', string $columnNameAsValue='value', bool $insertBlankItem = true, string $blankName = '请选择...', string $blankValue = ''): array
    {
        $targetItems = [];

        foreach ($sourceArray as $item) {
            $name  = $item[$columnNameAsName] ?? '';
            $value = $item[$columnNameAsValue] ?? '';

            // 跳过空白项
            if (empty($name) && empty($value)) {
                continue;
            }

            $targetItems[$value] = $name;
        }

        if ($insertBlankItem) {
            $targetItems = [$blankValue => $blankName] + $targetItems;
        }

        return $targetItems;
    }
}