<?php
/**
 * @file   : NumberHelper.php
 * @time   : 10:16
 * @date   : 2025/6/1
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\Data;

class NumberHelper
{
    /**
     * 使用千分位的方式格式化数字为字符串
     * @param float|int $number             带格式化的数字
     * @param int       $decimalLength      小数位数（缺省为0)
     * @param string    $decimalPoint       小数点符号(缺省为".")
     * @param string    $thousandsSeparator 千分位分隔符(缺省为逗号",")
     * @return string
     */
    public static function format($number, int $decimalLength = 0, string $decimalPoint = ".", string $thousandsSeparator = ","): string
    {
        return number_format($number, $decimalLength, $decimalPoint, $thousandsSeparator);
    }
}