<?php
/**
 * @file   : StringHelper.php
 * @time   : 08:38
 * @date   : 2025/4/22
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\Data;

use Closure;

class StringHelper
{
    /**
     * 去除字符串两端的空格，并移除特殊字符
     * @param string $stringValue
     * @param string ...$specialChars
     * @return string
     */
    public static function trimAndRemoveSpecialChar(string $stringValue, string ...$specialChars): string
    {
        $stringValue = trim($stringValue);

        if (empty($specialChars)) {
            $specialChars = [];
        }

        foreach ($specialChars as $char) {
            $stringValue = str_replace($char, '', $stringValue);
        }

        return $stringValue;
    }

    /**
     * @param string $wholeStringData 全句
     * @param string ...$paddingStringData 待测试的结尾字符
     * @return bool
     */
    public static function isEndWith(string $wholeStringData, string ...$paddingStringData): bool
    {
        foreach ($paddingStringData as $padding) {
            if (str_ends_with($wholeStringData, $padding)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $wholeStringData 全句
     * @param string ...$paddingStringData 待测试的开始字符
     * @return bool
     */
    public static function isStartWith(string $wholeStringData, string ...$paddingStringData): bool
    {
        foreach ($paddingStringData as $padding) {
            if (str_starts_with($wholeStringData, $padding)) {
                return true;
            }
        }

        return false;
    }


    /**
     * 判断一个字符串是否被包含在另外一个字符串内
     * @param string $wholeStringData 查找的母体字符串
     * @param string ...$subStringData 被查找的子字符串
     * @return boolean
     */
    public static function isContains(string $wholeStringData, string ...$subStringData): bool
    {
        foreach ($subStringData as $subString) {
            if (str_contains($wholeStringData, $subString)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 判断一个字符串是否被包含在另外一个字符串内
     * @param string $wholeStringData 查找的母体字符串
     * @param string ...$subStringData 被查找的子字符串
     * @return boolean
     */
    public static function contains(string $wholeStringData, string ...$subStringData): bool
    {
        return self::isContains($wholeStringData, ...$subStringData);
    }


    /**
     * 获取某个子字符串在全字符串中出现的各个位置
     * (因为一个全串可以包含多个子串，所以返回是一个有各个位置组成的一维数组)
     * @param string $wholeStringData 被查找的全字符串
     * @param string $subStringData 要查找的子字符串
     * @param bool $ignoreCaseSensitive 忽略字符大小写
     * @return array 子字符串在全字符串中出现的各个位置的数组
     */
    public static function getPositions(string $wholeStringData, string $subStringData, bool $ignoreCaseSensitive = false): array
    {
        $functionName = $ignoreCaseSensitive ? "mb_stripos" : "mb_strpos";

        $_search_pos = $functionName($wholeStringData, $subStringData);

        $_arr_positions = array();
        while ($_search_pos > -1) {
            $_arr_positions[] = $_search_pos;
            $_search_pos      = $functionName($wholeStringData, $subStringData, $_search_pos + 1);
        }

        return $_arr_positions;
    }

    /**
     * 交互(如果字集合中不存在$itemName则创建，如果存在则更新)字符串表示的集合中的某个元素的值
     * @param string|null $collectionString 字符串表示的集合
     * @param string $itemKey 元素键名称
     * @param string $itemValue 元素值
     * @param string $kvSeparator 同一个元素的键值对之间的分隔符
     * @param string $itemsSeparator 多个元素之间的分隔符
     * @return string
     */
    public static function interactCollectionItem(?string $collectionString, string $itemKey, string $itemValue, string $kvSeparator = ':', string $itemsSeparator = ','): string
    {
        return self::dealCollectionItem($collectionString, $kvSeparator, $itemsSeparator, static function (array &$collectionFixed) use ($itemKey, $itemValue) {
            $collectionFixed[$itemKey] = $itemValue;
        });
    }

    /**
     * 删除字符串表示的集合中的某个元素
     * @param string|null $collectionString
     * @param string $itemKey
     * @param string $kvSeparator
     * @param string $itemsSeparator
     * @return string
     */
    public static function deleteCollectionItem(?string $collectionString, string $itemKey, string $kvSeparator = ':', string $itemsSeparator = ','): string
    {
        return self::dealCollectionItem($collectionString, $kvSeparator, $itemsSeparator, static function (array &$collectionFixed) use ($itemKey) {
            unset($collectionFixed[$itemKey]);
        });
    }

    /**
     * 将表示集合的字符串转换为数组；调用回调函数对数组进行处理；并将处理后的数组转换回字符串。
     * @param string|null $collectionString
     * @param string $kvSeparator
     * @param string $itemsSeparator
     * @param Closure $callback 回调函数，参数为整理成的数组的引用
     * @return string
     */
    private static function dealCollectionItem(?string $collectionString, string $kvSeparator = ':', string $itemsSeparator = ',', Closure $callback): string
    {
        if (empty($collectionString)) {
            $collectionString = '';
        }

        $collection = explode($itemsSeparator, $collectionString);

        $kvpCollection = array();
        foreach ($collection as $item) {
            $itemArr = explode($kvSeparator, $item);
            if (count($itemArr) === 2) {
                $kvpCollection[$itemArr[0]] = $itemArr[1];
            }
        }

        $callback($kvpCollection);

        return implode($itemsSeparator, array_map(static function ($key, $value) use ($kvSeparator) {
            return $key . $kvSeparator . $value;
        }, array_keys($kvpCollection), array_values($kvpCollection)));
    }

}