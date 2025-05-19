<?php
/**
 * @file   : StringHelper.php
 * @time   : 08:38
 * @date   : 2025/4/22
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Wanren\Data;

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


}