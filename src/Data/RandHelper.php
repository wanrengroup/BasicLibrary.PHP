<?php
/**
 * @file   : randHelper.php
 * @time   : 11:27
 * @date   : 2025/1/23
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Wanren\Data;

class RandHelper
{
    /**
     * 生成随机字符串
     * @param $length int 字符串长度
     * @param $type string 类型，upper: 大写字母，lower: 小写字母，number: 数字，all: 所有字符
     * @return string
     */
    public static function generateRandomString(int $length = 3, string $type = 'number'): string
    {
        $type = strtolower($type);

        $upperLetter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowerLetter = 'abcdefghijklmnopqrstuvwxyz';
        $number      = '0123456789';
        $special     = '~!@#$%^&*()_+|}{<>?-=\/,.';

        $charset = '';
        if ($type === 'number' || $type === 'digital') {
            $charset = $number;
        }

        if ($type === 'upper') {
            $charset = $upperLetter;
        }

        if ($type === 'lower') {
            $charset = $lowerLetter;
        }

        if ($type === 'special') {
            $charset = $special;
        }

        if ($type === 'all') {
            $charset = $upperLetter . $lowerLetter . $number . $special;
        }

        $result    = '';
        $charCount = strlen($charset) - 1;
        for ($i = 0; $i < $length; $i++) {
            $position = mt_rand(0, $charCount);
            $result   .= $charset[$position];
        }
        return $result;
    }
}