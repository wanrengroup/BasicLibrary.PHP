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
use WanRen\LogicLayer\GeneralLogic;
use PHPUnit\Framework\TestCase;

// +--------------------------------------------------------------------------
// |:TITLE:::::::| 使用说明
// ---------------------------------------------------------------------------
// |:DESCRIPTION:| 需要先配置数据库表，表结构为：`tests/.SQLs/m_abstract_logic_testing.sql`
// +--------------------------------------------------------------------------

class WhereClauseTest extends TestCase
{
    private static string $targetTable = "abstract_logic_testing";

    private function getTargetTableWithPrefix(): string
    {
        return "m_" . self::$targetTable;
    }

    private function getThinkOrmVersion(): string
    {
        return EnvHelper::getVendorLibraryVersion("topthink/think-orm");;
    }


    protected function setUp(): void
    {
        DbAssert::initDb();
        $this->logic = new GeneralLogic(self::$targetTable, true);
    }

    private array $map1 = ['mobile', 'like', 'thinkphp%'];
    private array $map2 = ['name', 'like', '%thinkphp'];
    private array $map3 = ['grade', '=', 3];
    private array $map4 = ["id", "=", 1];

    public function testConditionAnd(): void
    {
        $where[] = $this->map1;
        $where[] = $this->map2;
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE  `mobile` LIKE 'thinkphp%'  AND `name` LIKE '%thinkphp'";

        if (version_compare($this->getThinkOrmVersion(), "V3.0.21", ">=")) {
            $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE  (  `mobile` LIKE 'thinkphp%' )  AND (  `name` LIKE '%thinkphp' )";
        }

        self::assertEquals($expect, $actual);
    }

    public function testConditionOr(): void
    {
        // +--------------------------------------------------------------------------
        // |::说明·| WhereOr在3.0.20版本前连接外部用OR；3.0.21版本之后用AND连接外部条件。
        // +--------------------------------------------------------------------------

        $where["__and__"] = $this->map1;
        $where["__or__"]  = $this->map2;
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE  `mobile` LIKE 'thinkphp%' OR `name` LIKE '%thinkphp'";

        if (version_compare($this->getThinkOrmVersion(), "V3.0.21", ">=")) {
            $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE  (  `mobile` LIKE 'thinkphp%' )  AND (  `name` LIKE '%thinkphp' )";
        }
        self::assertEquals($expect, $actual);
    }

    public function testConditionString(): void
    {
        $where = "id=1 and grade=3";
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE  ( id=1 and grade=3 )";
        self::assertEquals($expect, $actual);
    }

    public function testConditionMultiDimensionArray1(): void
    {
        $where[] = [$this->map1, $this->map2]; //会自动加括号
        $where[] = [$this->map3, $this->map4]; //会自动加括号
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE  ( `mobile` LIKE 'thinkphp%' AND `name` LIKE '%thinkphp' )  AND ( `grade` = 3 AND `id` = 1 )";

        if (version_compare($this->getThinkOrmVersion(), "V3.0.21", ">=")) {
            $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE  (  ( `mobile` LIKE 'thinkphp%' AND `name` LIKE '%thinkphp' ) )  AND (  ( `grade` = 3 AND `id` = 1 ) )";
        }

        self::assertEquals($expect, $actual);
    }

    public function testConditionMultiDimensionArray2(): void
    {
        $where[]     = [$this->map1, $this->map2]; //会自动加括号
        $where["__or__"] = [$this->map3, $this->map4]; //会自动加括号
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE  ( `mobile` LIKE 'thinkphp%' AND `name` LIKE '%thinkphp' ) OR ( `grade` = 3 AND `id` = 1 )";

        if (version_compare($this->getThinkOrmVersion(), "V3.0.21", ">=")) {
            $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE  (  ( `mobile` LIKE 'thinkphp%' AND `name` LIKE '%thinkphp' ) )  AND (  `grade` = 3  OR `id` = 1 )";
        }
        self::assertEquals($expect, $actual);
    }

    public function testConditionClosure(): void
    {
        $where = function ($query) {
            $query
                ->where("mobile", "like", "thinkphp%")
                ->where("name", "like", "%thinkphp");
        };
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `{$this->getTargetTableWithPrefix()}` WHERE  (  `mobile` LIKE 'thinkphp%'  AND `name` LIKE '%thinkphp' )";
        self::assertEquals($expect, $actual);
    }


}