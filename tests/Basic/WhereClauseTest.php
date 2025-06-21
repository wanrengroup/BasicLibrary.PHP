<?php
/**
 * @file   : WhereClauseTest.php
 * @time   : 17:22
 * @date   : 2025/5/17
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\Test\Basic;

use WanRen\Environment\EnvHelper;
use WanRen\WorkLayer\GeneralLogic;

// +--------------------------------------------------------------------------
// |:TITLE:::::::| 使用说明
// ---------------------------------------------------------------------------
// |:DESCRIPTION:| 需要先配置数据库表，表结构为：`tests/.SQLs/m_abstract_logic_testing.sql`
// +--------------------------------------------------------------------------

class WhereClauseTest extends LocalTestCase
{
    private static string $targetTable = "abstract_logic_testing";

    private function getTargetTableWithPrefix(): string
    {
        return "m_" . self::$targetTable;
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->logic = new GeneralLogic(self::$targetTable);
    }

    private array $map1 = ['mobile', 'like', 'thinkphp%'];
    private array $map2 = ['name', 'like', '%thinkphp'];
    private array $map3 = ['grade', '=', 3];
    private array $map4 = ["id", "=", 1];

    public function testCondition1(): void
    {
        $where = ['grade' => 3, 'id' => 1];
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE `grade` = 3 AND `id` = 1 ";

        self::assertEquals($expect, $actual);
    }

    public function testCondition2(): void
    {
        $where["grade"] = 3;
        $where["id"]    = 1;
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE `grade` = 3 AND `id` = 1 ";

        self::assertEquals($expect, $actual);
    }

    public function testConditionSimple(): void
    {
        $where = $this->map1;
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE `mobile` LIKE 'thinkphp%' ";

        self::assertEquals($expect, $actual);
    }

    public function testConditionAnd1(): void
    {
        $where[] = $this->map1;
        $where[] = $this->map2;
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE `mobile` LIKE 'thinkphp%' AND `name` LIKE '%thinkphp' ";

        self::assertEquals($expect, $actual);
    }

    public function testConditionAnd2(): void
    {
        $where["__and__"] = [$this->map1, $this->map2];
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE (  (  `mobile` LIKE 'thinkphp%' ) AND (  `name` LIKE '%thinkphp' ) ) ";

        self::assertEquals($expect, $actual);
    }

    public function testConditionOr1(): void
    {
        $where["__or__"] = [$this->map1, $this->map2];
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE (  (  `mobile` LIKE 'thinkphp%' ) OR (  `name` LIKE '%thinkphp' ) ) ";

        self::assertEquals($expect, $actual);
    }

    public function testConditionOr2(): void
    {
        $where["__or__"] = [$this->map1];
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE (  (  `mobile` LIKE 'thinkphp%' ) ) ";

        self::assertEquals($expect, $actual);
    }

    public function testConditionOr3(): void
    {
        $where["__or__"] = $this->map1;
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE (  (  `mobile` LIKE 'thinkphp%' ) ) ";

        self::assertEquals($expect, $actual);
    }

    public function testConditionString(): void
    {
        $where = "id=1 and grade=3";
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE ( id=1 and grade=3 ) ";
        self::assertEquals($expect, $actual);
    }

    public function testConditionMultiDimensionArray1(): void
    {
        $where[] = [$this->map1, $this->map2]; //会自动加括号
        $where[] = [$this->map3, $this->map4]; //会自动加括号
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE  ( `mobile` LIKE 'thinkphp%' AND `name` LIKE '%thinkphp' )  AND ( `grade` = 3 AND `id` = 1 )";

        self::assertEquals($expect, $actual);
    }

    public function testConditionMultiDimensionArray2(): void
    {
        $where[]         = [$this->map1, $this->map2]; //会自动加括号
        $where["__or__"] = [$this->map3, $this->map4]; //会自动加括号
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE  ( `mobile` LIKE 'thinkphp%' AND `name` LIKE '%thinkphp' ) OR ( `grade` = 3 AND `id` = 1 )";

        self::assertEquals($expect, $actual);
    }

    public function testConditionClosure(): void
    {
        // TP3.2 的查询条件不支持闭包查询

        //$where = static function ($query) {
        //    $query
        //        ->where("mobile", "like", "thinkphp%")
        //        ->where("name", "like", "%thinkphp");
        //};
        //$this->logic->getEntities($where);

        $actual = 1 + 1;
        $expect = 2;
        self::assertEquals($expect, $actual);
    }

    public function testConditionIgnoreWhereConditionNames(): void
    {
        $where[]           = $this->map1;
        $where[]           = $this->map2;
        $where["password"] = "123456";

        $options = [
            "ignore_where_condition_names" => ["password"]
        ];
        $result  = $this->logic->getEntities($where, "", "", "", $options);

        print_r(PHP_EOL . "──结果为：───────────────────────────────────" . PHP_EOL);
        if ($result) {
            print_r("结果不为空");
        } else {
            print_r("结果为空");
        }

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE `mobile` LIKE 'thinkphp%' AND `name` LIKE '%thinkphp' ";

        self::assertEquals($expect, $actual);
    }
}