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
    public function testGet(): void
    {
        $original = ['a' => 1, 'b' => 2, 'c' => 3];
        $actual   = ArrayHelper::get($original, 'a');
        $expect   = 1;
        self::assertEquals($expect, $actual);

        $actual = ArrayHelper::get($original, 'b', 30);
        $expect = 2;
        self::assertEquals($expect, $actual);

        $actual = ArrayHelper::get($original, 'd', 30);
        $expect = 30;
        self::assertEquals($expect, $actual);

        $original = [];
        $actual   = ArrayHelper::get($original, 'a');
        $expect   = null;
        self::assertEquals($expect, $actual);

        $original = [10, 20, 30];
        $actual   = ArrayHelper::get($original, 2);
        $expect   = 30;
        self::assertEquals($expect, $actual);

        $original = [10, 20];
        $actual   = ArrayHelper::get($original, 3, 0);
        $expect   = 0;
        self::assertEquals($expect, $actual);
    }

    public function testGetDimensionCount(): void
    {
        $original = "hello world";
        $actual   = ArrayHelper::getDimensionCount($original);
        $expect   = 0;
        self::assertEquals($expect, $actual);

        $original = [];
        $actual   = ArrayHelper::getDimensionCount($original);
        $expect   = 1;
        self::assertEquals($expect, $actual);

        $original = [1, 2, 3];
        $actual   = ArrayHelper::getDimensionCount($original);
        self::assertEquals($expect, $actual);

        $original = [1, 2, [3, 4, 5]];
        $actual   = ArrayHelper::getDimensionCount($original);
        $expect   = 2;
        self::assertEquals($expect, $actual);

        $original = [1, 2, [3, 4, [5, 6, 7]]];
        $actual   = ArrayHelper::getDimensionCount($original);
        $expect   = 3;
        self::assertEquals($expect, $actual);

        $original = ["a" => 1, "b" => 2, "c" => [3, 4, 5]];
        $actual   = ArrayHelper::getDimensionCount($original);
        $expect   = 2;
        self::assertEquals($expect, $actual);
    }

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

    /**
     * 测试 generateNameValueItems 方法的正常情况
     */
    public function testGenerateNameValueItemsHappyPath(): void
    {
        $sourceArray = [
            ['name' => 'Name1', 'value' => 'Value1'],
            ['name' => 'Name2', 'value' => 'Value2'],
        ];
        $expected = [
            '' => '请选择...',
            'Value1' => 'Name1',
            'Value2' => 'Name2',
        ];
        $this->assertEquals($expected, ArrayHelper::generateNameValueItems($sourceArray, 'name', 'value'));
    }

    /**
     * 测试 generateNameValueItems 方法的空数组情况
     */
    public function testGenerateNameValueItemsEmptyArray(): void
    {
        $sourceArray = [];
        $expected = [
            '' => '请选择...',
        ];
        $this->assertEquals($expected, ArrayHelper::generateNameValueItems($sourceArray, 'name', 'value'));
    }

    /**
     * 测试 generateNameValueItems 方法的不插入空白项情况
     */
    public function testGenerateNameValueItemsWithoutBlank(): void
    {
        $sourceArray = [
            ['name' => 'Name1', 'value' => 'Value1'],
            ['name' => 'Name2', 'value' => 'Value2'],
        ];
        $expected = [
            'Value1' => 'Name1',
            'Value2' => 'Name2',
        ];
        $this->assertEquals($expected, ArrayHelper::generateNameValueItems($sourceArray, 'name', 'value', false));
    }

    /**
     * 测试 generateNameValueItems 方法的缺少列名情况
     */
    public function testGenerateNameValueItemsMissingColumnName(): void
    {
        $sourceArray = [
            ['name' => 'Name1', 'value' => 'Value1'],
            ['name' => 'Name2'], // 缺少 'value' 列
        ];
        $expected = [
            '' => '请选择...',
            'Value1' => 'Name1',
        ];
        $this->assertEquals($expected, ArrayHelper::generateNameValueItems($sourceArray, 'name', 'value'));
    }

    /**
     * 测试 generateNameValueItems 方法的空字符串列值情况
     */
    public function testGenerateNameValueItemsEmptyColumnValue(): void
    {
        $sourceArray = [
            ['name' => 'Name1', 'value' => ''],
            ['name' => 'Name2', 'value' => 'Value2'],
        ];
        $expected = [
            '' => '请选择...',
            'Value2' => 'Name2',
        ];
        $this->assertEquals($expected, ArrayHelper::generateNameValueItems($sourceArray, 'name', 'value'));
    }
}