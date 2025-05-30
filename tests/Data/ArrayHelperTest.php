<?php
/**
 * @file   : ArrayHelperTest.php
 * @time   : 10:54
 * @date   : 2025/4/28
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\Test\Data;

use PHPUnit\Framework\TestCase;
use WanRen\Data\ArrayHelper;


class ArrayHelperTest extends TestCase
{
    public function testIsIndexArray(): void
    {
        //空数组, 索引数组
        $original = [];
        $actual   = ArrayHelper::isIndex($original);
        self::assertTrue($actual);

        //连续数字键(0,1,2), 索引数组
        $original = ['a', 'b', 'c'];
        $actual   = ArrayHelper::isIndex($original);
        self::assertTrue($actual);

        //从0开始的连续键, 索引数组
        $original = [0 => 'a', 1 => 'b'];
        $actual   = ArrayHelper::isIndex($original);
        self::assertTrue($actual);

        //字符串类型, 不是索引数组
        $original = "hello world";
        $actual   = ArrayHelper::isIndex($original);
        self::assertFalse($actual);

        //包含字符串键, 不是索引数组
        $original = ['name' => 'John'];
        $actual   = ArrayHelper::isIndex($original);
        self::assertFalse($actual);

        //不从0开始, 不是索引数组
        $original = [1 => 'a', 2 => 'b'];
        $actual   = ArrayHelper::isIndex($original);
        self::assertFalse($actual);

        //键不连续, 不是索引数组
        $original = [0 => 'a', 2 => 'b'];
        $actual   = ArrayHelper::isIndex($original);
        self::assertFalse($actual);

        //混合键类型, 不是索引数组
        $original = ['a', 'key' => 'b', 'c'];
        $actual   = ArrayHelper::isIndex($original);
        self::assertFalse($actual);

        //键顺序错乱, 不是索引数组
        $original = [1 => 'a', 0 => 'b'];
        $actual   = ArrayHelper::isIndex($original);
        self::assertFalse($actual);
    }

    public function testIsAssocArray(): void
    {
        //空数组, 不是关联数组
        $original = [];
        $actual   = ArrayHelper::isAssoc($original);
        self::assertFalse($actual);

        //连续数字键(0,1,2), 不是关联数组
        $original = ['a', 'b', 'c'];
        $actual   = ArrayHelper::isAssoc($original);
        self::assertFalse($actual);

        //从0开始的连续键, 不是关联数组
        $original = [0 => 'a', 1 => 'b'];
        $actual   = ArrayHelper::isAssoc($original);
        self::assertFalse($actual);

        //字符串类型, 不是关联数组
        $original = "hello world";
        $actual   = ArrayHelper::isAssoc($original);
        self::assertFalse($actual);

        //包含字符串键, 是关联数组
        $original = ['name' => 'John'];
        $actual   = ArrayHelper::isAssoc($original);
        self::assertTrue($actual);

        //不从0开始, 是关联数组
        $original = [1 => 'a', 2 => 'b'];
        $actual   = ArrayHelper::isAssoc($original);
        self::assertTrue($actual);

        //键不连续, 是关联数组
        $original = [0 => 'a', 2 => 'b'];
        $actual   = ArrayHelper::isAssoc($original);
        self::assertTrue($actual);

        //混合键类型, 是关联数组
        $original = ['a', 'key' => 'b', 'c'];
        $actual   = ArrayHelper::isAssoc($original);
        self::assertTrue($actual);

        //键顺序错乱, 是关联数组
        $original = [1 => 'a', 0 => 'b'];
        $actual   = ArrayHelper::isAssoc($original);
        self::assertTrue($actual);
    }
}