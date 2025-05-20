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
            $paddingLength = strlen($padding);
            $subString     = substr($wholeStringData, -$paddingLength);
            if ($subString === $padding) {
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
            $paddingLength = strlen($padding);
            $subString     = substr($wholeStringData, 0, $paddingLength);
            if ($subString === $padding) {
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
        $functionName = $ignoreCaseSensitive? "mb_stripos" : "mb_strpos";

        $_search_pos = $functionName($wholeStringData, $subStringData);

        $_arr_positions = array();
        while ($_search_pos > -1) {
            $_arr_positions[] = $_search_pos;
            $_search_pos = $functionName($wholeStringData, $subStringData, $_search_pos + 1);
        }

        return $_arr_positions;
    }



}