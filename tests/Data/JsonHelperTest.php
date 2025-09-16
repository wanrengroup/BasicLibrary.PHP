<?php

use PHPUnit\Framework\TestCase;
use WanRen\Data\JsonHelper;

/**
 * JsonHelper 类的单元测试
 */
class JsonHelperTest extends TestCase
{
    /**
     * 测试将对象转换为 JSON 字符串的正常情况
     */
    public function testEntity2StringHappyPath(): void
    {
        $entity     = ['name' => '张三', 'age' => 30];
        $jsonString = JsonHelper::entity2String($entity);
        $this->assertEquals('{"name":"张三","age":30}', $jsonString);
    }

    /**
     * 测试将对象转换为 JSON 字符串时保留中文
     */
    public function testEntity2StringRetainChinese(): void
    {
        $entity     = ['name' => '张三', 'age' => 30];
        $jsonString = JsonHelper::entity2String($entity, true);
        $this->assertEquals('{"name":"张三","age":30}', $jsonString);
    }

    /**
     * 测试将对象转换为 JSON 字符串时不保留中文
     */
    public function testEntity2StringNotRetainChinese(): void
    {
        $entity     = ['name' => '张三', 'age' => 30];
        $jsonString = JsonHelper::entity2String($entity, false);
        $this->assertEquals('{"name":"\u5f20\u4e09","age":30}', $jsonString);
    }

    /**
     * 测试将对象转换为 JSON 字符串时的边缘情况：空数组
     */
    public function testEntity2StringEdgeCaseEmptyArray(): void
    {
        $entity     = [];
        $jsonString = JsonHelper::entity2String($entity);
        $this->assertEquals('[]', $jsonString);
    }

    /**
     * 测试将对象转换为 JSON 字符串时的边缘情况：空对象
     */
    public function testEntity2StringEdgeCaseEmptyObject(): void
    {
        $entity     = new \stdClass();
        $jsonString = JsonHelper::entity2String($entity);
        $this->assertEquals('{}', $jsonString);
    }

    /**
     * 测试将对象转换为 JSON 字符串时的边缘情况：包含特殊字符
     */
    public function testEntity2StringEdgeCaseSpecialCharacters(): void
    {
        $entity     = ['name' => '张三', 'description' => "Hello\nWorld"];
        $jsonString = JsonHelper::entity2String($entity);
        $this->assertEquals('{"name":"张三","description":"Hello\\nWorld"}', $jsonString);
    }

    /**
     * 测试将对象转换为 JSON 字符串时的边缘情况：非对象或数组
     */
    public function testEntity2StringEdgeCaseInvalidInput(): void
    {
        $entity     = 'not an object or array';
        $jsonString = JsonHelper::entity2String($entity);
        //$this->assertFalse($jsonString);
        $this->assertEquals('"not an object or array"', $jsonString);
    }

    /**
     * 测试将 JSON 字符串转换为数组的正常情况
     */
    public function testString2ArrayHappyPath(): void
    {
        $jsonString = '{"name":"张三","age":30}';
        $array      = JsonHelper::string2Array($jsonString);
        $this->assertEquals(['name' => '张三', 'age' => 30], $array);
    }

    /**
     * 测试将 JSON 字符串转换为数组时的边缘情况：空 JSON 字符串
     */
    public function testString2ArrayEdgeCaseEmptyString(): void
    {
        $jsonString = '';
        $array      = JsonHelper::string2Array($jsonString);
        $this->assertNull($array);
    }

    /**
     * 测试将 JSON 字符串转换为数组时的边缘情况：无效的 JSON 字符串
     */
    public function testString2ArrayEdgeCaseInvalidJson(): void
    {
        $jsonString = '{name:"张三",age:30}'; // 缺少引号
        $array      = JsonHelper::string2Array($jsonString);
        $this->assertNull($array);
    }

    /**
     * 测试将 JSON 字符串转换为对象的正常情况
     */
    public function testString2ObjectHappyPath(): void
    {
        $jsonString = '{"name":"张三","age":30}';
        $object     = JsonHelper::string2Object($jsonString);
        $this->assertEquals((object)['name' => '张三', 'age' => 30], $object);
    }

    /**
     * 测试将 JSON 字符串转换为对象时的边缘情况：空 JSON 字符串
     */
    public function testString2ObjectEdgeCaseEmptyString(): void
    {
        $jsonString = '';
        $object     = JsonHelper::string2Object($jsonString);
        $this->assertNull($object);
    }

    /**
     * 测试将 JSON 字符串转换为对象时的边缘情况：无效的 JSON 字符串
     */
    public function testString2ObjectEdgeCaseInvalidJson(): void
    {
        $jsonString = '{name:"张三",age:30}'; // 缺少引号
        $object     = JsonHelper::string2Object($jsonString);
        $this->assertNull($object);
    }
}
