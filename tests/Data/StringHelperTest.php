<?php
/**
 * @file   : DateHelperTest.php
 * @time   : 10:54
 * @date   : 2025/4/28
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\Test\Data;

use PHPUnit\Framework\TestCase;
use WanRen\Data\StringHelper;


class StringHelperTest extends TestCase
{
    public function testIsStartWith(): void
    {
        $original = "i like to eat apple!";
        $actual = StringHelper::isStartWith($original,"apple","boy");
        self::assertFalse($actual);

        $actual = StringHelper::isStartWith($original,"apple","i");
        self::assertTrue($actual);

        $actual = StringHelper::isStartWith($original,"apple","i ");
        self::assertTrue($actual);
    }

    public function testIsEndWith(): void
    {
        $original = "i like to eat apple!";
        $actual = StringHelper::isEndWith($original,"apple","boy");
        self::assertFalse($actual);

        $actual = StringHelper::isEndWith($original,"apple","!");
        self::assertTrue($actual);

        $actual = StringHelper::isEndWith($original,"apple!","i ");
        self::assertTrue($actual);
    }

    public function testIsContains(): void
    {
        $original = "i like to eat apple!";
        $actual = StringHelper::isContains($original,"apple1","boy");
        self::assertFalse($actual);

        $actual = StringHelper::isContains($original,"apple","!");
        self::assertTrue($actual);

        $actual = StringHelper::isContains($original,"apple!","i ");
        self::assertTrue($actual);
    }


    /**
     * 测试 interactCollectionItem 方法
     */
    public function testInteractCollectionItem(): void
    {
        // 测试用例 1: 添加一个新元素
        $collectionString = "key1:value1,key2:value2";
        $itemKey = "key3";
        $itemValue = "value3";
        $expectedResult = "key1:value1,key2:value2,key3:value3";
        $this->assertEquals($expectedResult, StringHelper::interactCollectionItem($collectionString, $itemKey, $itemValue));

        // 测试用例 2: 更新现有元素的值
        $collectionString2 = "key1:value1,key2:value2";
        $itemKey = "key2";
        $itemValue = "newValue2";
        $expectedResult = "key1:value1,key2:newValue2";
        $this->assertEquals($expectedResult, StringHelper::interactCollectionItem($collectionString2, $itemKey, $itemValue));

        // 测试用例 3: 使用不同的分隔符
        $collectionString3 = "key1-value1;key2-value2";
        $itemKey = "key3";
        $itemValue = "value3";
        $kvSeparator = "-";
        $itemsSeparator = ";";
        $expectedResult = "key1-value1;key2-value2;key3-value3";
        $this->assertEquals($expectedResult, StringHelper::interactCollectionItem($collectionString3, $itemKey, $itemValue, $kvSeparator, $itemsSeparator));

        // 测试用例 4: 添加一个新元素
        $collectionString4 = null;
        $itemKey = "key7";
        $itemValue = "value7";
        $expectedResult = "key7:value7";
        $this->assertEquals($expectedResult, StringHelper::interactCollectionItem($collectionString4, $itemKey, $itemValue));
    }

    /**
     * 测试 DeleteCollectionItem 方法
     */
    public function testDeleteCollectionItem(): void
    {
        // 测试用例 1: 删除一个现有元素
        $collectionString = "key1:value1,key2:value2,key3:value3";
        $itemKey = "key2";
        $expectedResult = "key1:value1,key3:value3";
        $this->assertEquals($expectedResult, StringHelper::deleteCollectionItem($collectionString, $itemKey));

        // 测试用例 2: 尝试删除一个不存在的元素
        $collectionString = "key1:value1,key2:value2";
        $itemKey = "key3";
        $expectedResult = "key1:value1,key2:value2";
        $this->assertEquals($expectedResult, StringHelper::deleteCollectionItem($collectionString, $itemKey));

        // 测试用例 3: 使用不同的分隔符
        $collectionString = "key1:value1;key2:value2;key3:value3";
        $itemKey = "key2";
        $kvSeparator = ":";
        $itemsSeparator = ";";
        $expectedResult = "key1:value1;key3:value3";
        $this->assertEquals($expectedResult, StringHelper::deleteCollectionItem($collectionString, $itemKey, $kvSeparator, $itemsSeparator));


    }
}