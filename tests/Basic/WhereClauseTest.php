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

use WanRen\LogicLayer\GeneralLogic;
use PHPUnit\Framework\TestCase;

class WhereClauseTest extends TestCase
{
    protected function setUp(): void
    {
        DbAssert::initDb();
        $this->logic = new GeneralLogic("no_publish_c", true);
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
        $expect = "SELECT * FROM `m_no_publish_c` WHERE  `mobile` LIKE 'thinkphp%'  AND `name` LIKE '%thinkphp'";
        self::assertEquals($expect, $actual);
    }

    public function testConditionOr(): void
    {
        $where[]     = $this->map1;
        $where["or"] = $this->map2;
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `m_no_publish_c` WHERE  `mobile` LIKE 'thinkphp%' OR `name` LIKE '%thinkphp'";
        self::assertEquals($expect, $actual);
    }

    public function testConditionString(): void
    {
        $where = "id=1 and grade=3";
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `m_no_publish_c` WHERE  ( id=1 and grade=3 )";
        self::assertEquals($expect, $actual);
    }

    public function testConditionMultiDimensionArray1(): void
    {
        $where[] = [$this->map1, $this->map2]; //会自动加括号
        $where[] = [$this->map3, $this->map4]; //会自动加括号
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `m_no_publish_c` WHERE  ( `mobile` LIKE 'thinkphp%' AND `name` LIKE '%thinkphp' )  AND ( `grade` = 3 AND `id` = 1 )";
        self::assertEquals($expect, $actual);
    }

    public function testConditionMultiDimensionArray2(): void
    {
        $where[]     = [$this->map1, $this->map2]; //会自动加括号
        $where["or"] = [$this->map3, $this->map4]; //会自动加括号
        $this->logic->getEntities($where);

        $actual = $this->logic->getLastSql();
        $expect = "SELECT * FROM `m_no_publish_c` WHERE  ( `mobile` LIKE 'thinkphp%' AND `name` LIKE '%thinkphp' ) OR ( `grade` = 3 AND `id` = 1 )";
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
        $expect = "SELECT * FROM `m_no_publish_c` WHERE  (  `mobile` LIKE 'thinkphp%'  AND `name` LIKE '%thinkphp' )";
        self::assertEquals($expect, $actual);
    }


}